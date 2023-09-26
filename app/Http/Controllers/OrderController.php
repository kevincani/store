<?php

namespace App\Http\Controllers;

use App\Repositories\InventaryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Refund;
use Stripe\Stripe;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    /** Deklarimi i repozitorit qe do te perdoret */
    protected OrderRepository $orderRepository;
    protected InventaryRepository $inventaryRepository;
    protected UserRepository $userRepository;
    public function __construct(OrderRepository $repository,InventaryRepository $inventaryRepository,UserRepository $userRepository){
        $this->orderRepository = $repository;
        $this->inventaryRepository = $inventaryRepository;
        $this->userRepository = $userRepository;
    }

    public function index(){
        return view('order.index');
    }

    public function getForDatatable(): JsonResponse
    {
        // Nqs eshte admin/manager shiko te gjitha porosite, perndryshe vetem te tijat
        $user = auth()->user();
        if ($user->hasRole(['admin','manager'])) {
            $orders = $this->orderRepository->query()->with('orderDetails')->get();
        } else {
            $orders = $this->orderRepository->query()->with('orderDetails')->where('user_id',$user->id)->get();
        }



        //cdo detaj te produkteve te cdo orderi i kalojme ne array-n order
        foreach ($orders as $order){
            foreach ($order->orderDetails as $orderDetail){
                $id = $orderDetail->pivot_id;
                $inventary = $this->inventaryRepository->fullInventary($id);
                $orderDetail->product = $inventary;
            }
            if (!isset($order->client)){
                $useri = $this->userRepository->find($order->user_id);
                $order->client = $useri->first_name . " " . $user->last_name;
            }
        }

        return DataTables::of($orders)->addIndexColumn()
            ->addColumn('date', function ($orders) {
                $carbonTimestamp = Carbon::parse( $orders->created_at);
                return $carbonTimestamp->format('Y-m-d H:i:s');
            })
            ->addColumn('totalPrice', function ($orders) {
                $sign = "$";
                $totalPrice = "$orders->total_price$sign";
                return $totalPrice;
            })
            ->addColumn('status', function ($orders) {
                $status = $orders->is_refunded ? "Refunded" : "Not Refunded";
                return $status;
            })
            ->addColumn('action', function ($orders) {
                $user = auth()->user();
                if ($user->hasRole(['admin','manager'])){
                    $button = '<button type="button" id="'.$orders->id.'" name="refund" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"></span>Refund</button>';
                }
                else{
                    $button = '';
                }
                return $button;
            })
            ->make(true);

    }

    public function refund($id){
        Stripe::setApiKey(config('stripe.sk'));

        // Orderi dhe detajet per userin qe eshte loguar
        $order = $this->orderRepository->query()->with('orderDetails')->findOrFail($id);

        // Nqs i ka bere refund me perpara e nderpresim dhe i kthejme pergjigje
        if ($order->is_refunded) {
            return response()->json(['message' => 'This order is already refunded'], 409);
        }

        //Nqs kane kaluar me shume se 48 ore qe nga porosia nuk lejojme refund
        $twoDaysAgo = Carbon::now()->subDays(2);
        if ($order->created_at < $twoDaysAgo) {
            return response()->json(['message' => '48 hours have passed since you ordered'], 422);
        }

        //Bejme refund dhe e ruajme ne databaze
        $paymentIntent = $order->payment_intent;
        $refund = Refund::create([
            'payment_intent' => $paymentIntent,
        ]);
        $order->is_refunded = true;
        $order->save();

        //Rrisim quantity ne inventar te produktit qe i beme refund
        foreach ($order->orderDetails as $orderDetail){
            $inventary = $this->inventaryRepository->find($orderDetail->pivot_id);
            $newQuantity = $inventary->quantity + $orderDetail->quantity;
            $this->inventaryRepository->update($inventary,['quantity'=>$newQuantity]);
        }

        return response()->json(['message' => "The order is refunded successfully"], 200);
    }

}
