<?php

namespace App\Http\Controllers;

use Omnipay\Omnipay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Supplies;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Cart;
use Srmklive\PayPal\Facades\PayPal;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{

    public function paymentListView(){
        return view('client_payment_page');
    }

    public function loadPayments(){
        if(Auth::user()->role == 'admin'){
            $paymentLists = Payment::select('*')->orderBy('id', 'desc')->get();

            // Render the view component with order items
            $view = View::make('components.client_payment_list', compact('paymentLists'))->render();

            // Return the rendered view as JSON
            return response()->json(['html' => $view]);
        }
        else{
            $paymentLists = Payment::where('user_id', Auth::id())->orderBy('id', 'desc')->get();

            // Render the view component with order items
            $view = View::make('components.client_payment_list', compact('paymentLists'))->render();

            // Return the rendered view as JSON
            return response()->json(['html' => $view]);
        }
    }
    
    public function paypal(Request $request){
        //Initialize User Inputs
        $totalPrice = $request->input('total_price');
        $items = $request->input('items');
        
        //Save as Session
        session()->put('items', $items);
        session()->put('totalPrice', $totalPrice);

        //Initialize Paypal API Access
        $provider = new PayPalClient();
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success'),
                "cancel_url" => route('paypal.cancel')
            ],
            "purchase_units" => [
                [
                    "amount"=>[
                        "currency_code" => "USD",
                        "value" => $totalPrice
                    ]
                ]
            ]
        ]);

        //Checking API Connection
        if(isset($response['id']) && $response['id'] != null){
            foreach($response['links']as $link){
                if($link['rel'] == 'approve'){
                    session()->put('items', $items);
                    return redirect()->away($link['href']);
                }
            }
        }
        else {
            return redirect()->route('cancel');
        }


    }

    public function success(Request $request){
        //Initialize API Access
        $provider = new PayPalClient();
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);
        //dd($response);
        
        $items = json_decode(session()->get('items', '[]'), true);
        $totalPrice = session()->get('totalPrice');
        if(isset($response['status']) && $response['status'] =='COMPLETED'){
            
            $orderStringID = Str::random(10);

            //Create Payment Insertion
            $payment = new Payment;
            $payment->payment_id = $response['id'];
            $payment->payer_name = $response['payer']['name']['given_name'];
            $payment->payer_email = $response['payer']['email_address'];
            $payment->user_id = Auth::id();
            $payment->order_id = $orderStringID;
            $payment->amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
            $payment->payment_status = $response['status'];
            $payment->save();

            foreach ($items as $item) {
                $product = Supplies::find($item['productId']);
                
                if ($product && $product->stock >= $item['quantity']) {
                    // Decrease the stock
                    $product->stock -= $item['quantity'];
                    $product->save();
                    
                    $quantityandPrice = $item['quantity'] * $product->price;
                    // Create order
                    Order::create([
                        'user_id' => Auth::id(),
                        'suppliesId' => $item['productId'], // Make sure this matches your column name in the orders table
                        'order_id' => $orderStringID,
                        'quantity' => $item['quantity'],
                        'price' => $quantityandPrice,
                        'status' => 'pending', // or any default status
                    ]);
    
                    // Delete from cart
                    $cartItem = Cart::where('user_Id', Auth::id())
                                    ->where('product_id', $item['productId'])
                                    ->first();
                                    
                    if ($cartItem) {
                        $cartItem->delete();
                    }
                    
                } 
                
                else {
                    return response()->json(['message' => 'Insufficient stock for product ID: ' . $item['productId']], 400);
                }

            }
            //Unset the Session
            Session::forget('items');
            Session::forget('totalPrice');

            return redirect()->route('payment-list');
        }
    }

    public function cancel(){

        return response()->json(['message' => 'Payment Cancelled '], 400);
    }

    
}
