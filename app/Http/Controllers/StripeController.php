<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Inventary;
use App\Repositories\InventaryRepository;
use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeController extends Controller
{
    protected OrderRepository $orderRepository;
    protected OrderDetailRepository $orderDetailRepository;
    protected InventaryRepository $inventaryRepository;
    public function __construct(OrderRepository $orderRepository,OrderDetailRepository $orderDetailRepository,InventaryRepository $inventaryRepository){
        $this->orderRepository = $orderRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->inventaryRepository = $inventaryRepository;
    }

    public function session(Request $request){
        Stripe::setApiKey(config('stripe.sk'));
        //Array ku do fusim te dhenat dhe e vendosimne Stripe
        $productItems = [];
        //Array ku eshte inventary me te gjitha relations e tij
        $inventaries = json_decode(json_encode($request->input('inventaries')));

        $user = auth()->user();

        $totalPrice = 0; //Inicializojme cmimin ne fiilim me 0
        //E krijome nje porosi ne databaze pa futur te dhenat e stripe
        $order = $this->orderRepository->create([
            'user_id' => $user->id,
            'payment_intent' => '',
            'total_price' => $totalPrice,
            'session_id' => ''
        ]);

        // Nje porosi mund te kete disa produkte, bejme loop
        foreach ($inventaries as $inventary) {
            $productName = $inventary->products->name; //Emri
            //Vendosim cmimin ne varesi te uljes
            $discount = $inventary->discounts->discount_percent / 100;
            $productPrice = $inventary->price - ($inventary->price * $discount);
            $productQuantity = $inventary->quantity; //Sasia
            //Rrisim cmimin total me cmimin e cdo produkti * sasine
            $totalPrice += $productPrice * $productQuantity;

            //Per cdo produkt qe ka orderi krijojme nje orderDetail qe mban karakteristikat e produkti dhe Id e orderit
            $this->orderDetailRepository->create([
                'order_id' => $order->id,
                'pivot_id' => $inventary->id,
                'quantity' => $inventary->quantity,
            ]);

            // I shtojme cmimit '00' sepse Stripe mer ne cent dhe jo dollar
            $two0 = "00";
            $unit_amount = "$productPrice$two0";

            //Mbushim vektorin qe do mer Stripe
            $productItems[] = [
                'price_data' => [
                    'product_data' => [
                        'name' => $productName,
                    ],
                    'currency'     => 'USD',
                    'unit_amount'  => $unit_amount,
                ],
                'quantity' => $productQuantity
            ];

        }
        // Llogarisim kohen qe deri 30 minuta me vone te skadoje sesioni nqs nuk perfundon
        $twoMinutesLater = Carbon::now()->addMinutes(30);
        $unixTimestamp = $twoMinutesLater->timestamp;

        //Krijojme sessionin e Stripe
        $checkoutSession = Session::create([
            'line_items'            => [$productItems],
            'mode'                  => 'payment',
            'expires_at'            => $unixTimestamp,
            'allow_promotion_codes' => true,
            'metadata'              => [
                'user_id' => $user->id
            ],
            'customer_email' => $user->email,
            //Ne rast suksesi kalojme order dhe id e session ne routerin e suksesit
            'success_url' => route('checkout.success', ['order' => $order->id]) . "?session_id={CHECKOUT_SESSION_ID}" ,
            'cancel_url'  => route('checkout.cancel', ['order' => $order->id]),
        ]);
        //Updatojme orderin me te id e session dhe cmimin total
        $this->orderRepository->update($order,[
            'total_price' => $totalPrice,
            'session_id' => $checkoutSession->id,
        ]);
        //Kthejme Url qe behet blerja me Stripe
        return response()->json(['checkout_url' => $checkoutSession->url]);

    }


    public function success(Request $request,Order $order)
    {
        Stripe::setApiKey(config('stripe.sk'));
        //Id e sessionit
        $checkoutSessionId = $request->get('session_id');
        //Marim te dhenat e sesionit me ane te Id
        $session = Session::retrieve($checkoutSessionId);
        // Updatojme orderin me payment_intent
        $this->orderRepository->update($order,[
        'payment_intent' => $session->payment_intent,
        ]);

        //Marim te gjitha tabelat orderDetail te orderit aktual sepse na duhen Id e inventarit te secilit produkt te sapo shitur
        $orderDetails = $this->orderDetailRepository->query()->where('order_id', $order->id)->get();

        //Ulim sasine ne secilin ineventar te produkteve te shitur
        foreach ($orderDetails as $orderDetail) {
            $inventary = $this->inventaryRepository->find($orderDetail->pivot_id);
            $newQuantity = $inventary->quantity - $orderDetail->quantity;
            $this->inventaryRepository->update($inventary,[
                'quantity' => $newQuantity,
            ]);
        }
        return view('stripe.success');
    }

    public function cancel(Request $request,Order $order)
    {
        //Ne rast se klienti heq dore fshijme orderin qe patem krijuar
        $this->orderRepository->delete($order);
        return view('stripe.cancel');
    }
    public function handleWebhook(){


// This is your Stripe CLI webhook secret for testing your endpoint locally.
//        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        $endpoint_secret = 'whsec_a7bf96f66ded71bae26e598fadfd85484a615c50ceda2ad0a64c10a7f25bb0fd';

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

// Ne rast se sesioni expire fshijme orderin qe kemi krijuar
        switch ($event->type) {
            case 'checkout.session.expired':
                $seesion_id = $event->data->object->id;
                $order = $this->orderRepository->query()->where('session_id',$seesion_id )->first();
                if ($order) {
                    $this->orderRepository->delete($order);
                }

            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        http_response_code(200);
    }

}


//namespace App\Http\Controllers;
//
//use App\Models\Order;
//use App\Models\OrderDetail;
//use App\Models\Inventary;
//use App\Models\User;
//use Carbon\Carbon;
//use Illuminate\Http\Request;
//use Stripe\Checkout\Session;
//use Stripe\Exception\SignatureVerificationException;
//use Stripe\Stripe;
//use Stripe\Webhook;
//
//class StripeController extends Controller
//{
//    public function session(Request $request)
//    {
//        Stripe::setApiKey(config('stripe.sk'));
//
//        $productItems = [];
//
//        $products = $request->input('products');
//
//        $user = auth()->user();
//
//        $totalPrice = 0;
//        $order = Order::create([
//            'user_id' => $user->id,
//            'payment_intent' => '',
//            'total_price' => $totalPrice,
//            'session_id' => ''
//        ]);
//
//        foreach ($products as $product) {
//            $productName = $product['name'];
//            $discount = $product['discounts'][0]['discount_percent'] / 100;
//            $productPrice = $product['inventary'][0]['price'] - ($product['inventary'][0]['price'] * $discount);
//            $productQuantity = $product['inventary'][0]['quantity'];
//            $totalPrice += $productPrice * $productQuantity;
//
//            OrderDetail::create([
//                'order_id' => $order->id,
//                'pivot_id' => $product['inventary'][0]['id'],
//                'quantity' => $product['inventary'][0]['quantity'],
//            ]);
//
//            $two0 = "00";
//            $unit_amount = "$productPrice$two0";
//
//            $productItems[] = [
//                'price_data' => [
//                    'product_data' => [
//                        'name' => $productName,
//                    ],
//                    'currency' => 'USD',
//                    'unit_amount' => $unit_amount,
//                ],
//                'quantity' => $productQuantity
//            ];
//
//        }
//
//        $twoMinutesLater = Carbon::now()->addMinutes(30);
//        $unixTimestamp = $twoMinutesLater->timestamp;
//
//        $checkoutSession = Session::create([
//            'line_items' => [$productItems],
//            'mode' => 'payment',
//            'expires_at' => $unixTimestamp,
//            'allow_promotion_codes' => true,
//            'metadata' => [
//                'user_id' => $user->id
//            ],
//            'customer_email' => $user->email,
//            'success_url' => route('checkout.success', ['order' => $order->id]) . "?session_id={CHECKOUT_SESSION_ID}",
//            'cancel_url' => route('checkout.cancel', ['order' => $order->id]),
//        ]);
//
//        $order->update([
//            'total_price' => $totalPrice,
//            'session_id' => $checkoutSession->id,
//        ]);
//        return response()->json(['checkout_url' => $checkoutSession->url]);
//
//    }
//
//
//    public function success(Request $request, Order $order)
//    {
//        Stripe::setApiKey(config('stripe.sk'));
//
//        $checkoutSessionId = $request->get('session_id');
//        $session = Session::retrieve($checkoutSessionId);
//        $order->update([
//            'payment_intent' => $session->payment_intent,
//        ]);
//
//        $orderDetails = OrderDetail::where('order_id', $order->id)->get();
//
//        foreach ($orderDetails as $orderDetail) {
//            $inventary = Inventary::find($orderDetail->pivot_id);
//            $newQuantity = $inventary->quantity - $orderDetail->quantity;
//            $inventary->update([
//                'quantity' => $newQuantity,
//            ]);
//        }
//
//        return "Thanks for you order You have just completed your payment. The seller will reach out to you as soon as possible";
//    }
//
//    public function cancel(Request $request, Order $order)
//    {
//        $order->delete();
//        return " Canceled";
//    }
//
//    public function handleWebhook()
//    {
//
//
//// This is your Stripe CLI webhook secret for testing your endpoint locally.
////        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
//        $endpoint_secret = 'whsec_a7bf96f66ded71bae26e598fadfd85484a615c50ceda2ad0a64c10a7f25bb0fd';
//
//        $payload = @file_get_contents('php://input');
//        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
//        $event = null;
//
//        try {
//            $event = Webhook::constructEvent(
//                $payload, $sig_header, $endpoint_secret
//            );
//        } catch (\UnexpectedValueException $e) {
//            // Invalid payload
//            http_response_code(400);
//            exit();
//        } catch (SignatureVerificationException $e) {
//            // Invalid signature
//            http_response_code(400);
//            exit();
//        }
//
//// Handle the event
//        switch ($event->type) {
//            case 'checkout.session.expired':
//                $seesion_id = $event->data->object->id;
//                $order = Order::where('session_id', $seesion_id)->first();
//                if ($order) {
//                    $order->delete();
//                }
//
//            // ... handle other event types
//            default:
//                echo 'Received unknown event type ' . $event->type;
//        }
//
//        http_response_code(200);
//    }
//
//}

