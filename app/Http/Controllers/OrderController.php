<?php

namespace App\Http\Controllers;

use App\Repositories\InventaryRepository;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Refund;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    /** Deklarimi i repozitorit qe do te perdoret */
    protected OrderRepository $orderRepository;
    protected InventaryRepository $inventaryRepository;
    public function __construct(OrderRepository $repository,InventaryRepository $inventaryRepository){
        $this->orderRepository = $repository;
        $this->inventaryRepository = $inventaryRepository;
    }

    public function index(){
        return view('order.index');
    }

    public function getForDatatable(){
        $orders = $this->orderRepository->allUsersOrdersWithDetails(); // Te gjithe porosite e userit te loguar

        //cdo detaj te produkteve te cdo orderi i kalojme ne array-n order
        foreach ($orders as $order){
            foreach ($order->orderDetails as $orderDetail){
                $id = $orderDetail->pivot_id;
                $inventary = $this->inventaryRepository->fullInventary($id);
                $orderDetail->product = $inventary;
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
                $button = '<button type="button" id="'.$orders->id.'" name="refund" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"></span>Refund</button>';
                return $button;
            })
            ->make(true);

    }

    public function refund($id){
        $order = $this->orderRepository->orderWithDetails($id); // Orderi dhe detajet per userin qe eshe loguar

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
            $inventary = $this->inventaryRepository->findInventary($orderDetail->pivot_id);
            $newQuantity = $inventary->quantity + $orderDetail->quantity;
            $this->inventaryRepository->update($inventary,$newQuantity);
        }

        return response()->json(['message' => "The order is refunded successfully"], 200);
    }

}
