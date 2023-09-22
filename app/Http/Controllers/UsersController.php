<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Inventary;
use App\Models\Product;
use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Refund;
use Stripe\Stripe;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    protected $user;
    public function __construct(UserRepository $repository){
        $this->user = $repository;
    }
    public function view(Request $request){

        if ($request->ajax()) {
            $data = User::with('roles');

            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($data) {
                    // Add any additional columns or computed values
                    // that you want to display in the DataTable
                    $button = '<button type="button" id="'.$data->id.'" name="edit" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"></span>Edit</button>';
                    $button .= '<button type="button" id="'.$data->id.'" name="delete"  class="text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" style="background-color: red ">Delete</button>';
                    return $button;
                })
                ->addColumn('role',function($data){
                    return $data->getRoleNames()->first();
                })
                ->make(true);
        }

        return view('users.index');
    }


    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = User::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    public function store(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'address' => 'required',
            'phone' => 'required',
        ]);

        // If validation fails, return the error messages
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        unset($validatedData['password_confirmation']);
        $user = $this->user->create($validatedData);
        $user->assignRole('client');

        return response()->json(['message' => 'User created successfully'], 200);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $form_data = [
            'username' => $request->input('username'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
        ];


        User::whereId($request->hiddenId)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);
    }

    public function destroy($id)
    {
        $data = User::findOrFail($id);
        $data->delete();
    }


    public function list(Request $request){

        if ($request->ajax()){
            $user = auth()->user();
            $orders = Order::where('user_id',$user->id)->with('orderDetails')->get();
//        dd($orders[3]->orderDetails[1]->id);
//            $test = [];
            foreach ($orders as $order){
                foreach ($order->orderDetails as $orderDetail){

                    $id = $orderDetail->pivot_id;
                    $inventary = Inventary::where('id',$id)->first();
                    $product = Product::with(['colors', 'categories', 'discounts', 'sizes', 'inventary' => function ($query) use ($id) {
                        $query->where('inventary.id', '=', $id);
                    }])->find($inventary->product_id);
                    $orderDetail->product = $product;
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

        return view('users.purchased');

    }

    public function refund(Request $request){
        Stripe::setApiKey(config('stripe.sk'));


        $id = $request->input('id');

        $order = Order::with('orderDetails')->find($id);
        if ($order->is_refunded) {
            return response()->json(['message' => 'This order is already refunded'], 400);
        }
        $twoDaysAgo = Carbon::now()->subDays(2);
        if ($order->created_at < $twoDaysAgo) {
            return response()->json(['message' => '48 hours have passed since you ordered'], 400);
        }

        $paymentIntent = $order->payment_intent;
        $refund = Refund::create([
            'payment_intent' => $paymentIntent,
        ]);

        $order->is_refunded = true;
        $order->save();
        foreach ($order->orderDetails as $orderDetail){
            $inventary = Inventary::find($orderDetail->pivot_id);
            $newQuantity = $inventary->quantity + $orderDetail->quantity;
            $inventary->update([
                'quantity' => $newQuantity,
            ]);
        }


        return response()->json(['message' => "The order is refunded successfully"], 200);
    }
}
