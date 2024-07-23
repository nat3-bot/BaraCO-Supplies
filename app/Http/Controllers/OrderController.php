<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Supplies;


class OrderController extends Controller
{
    public function orderListView(){
        return view('order_page');
    }

    public function loadOrders(){
        // Retrieve all order items

        if(Auth::user()->role == 'admin'){
        
            $orderItems = Order::with(['product', 'user'])->orderBy('created_at', 'desc')->get();

            // Render the view component with order items
            $view = View::make('components.order_list', compact('orderItems'))->render();

            // Return the rendered view as JSON
            return response()->json(['html' => $view]);
        }
        else{
            $orderItems = Order::with(['product'])->orderBy('created_at', 'desc')->where('user_id', Auth::id())->get();

            // Render the view component with order items
            $view = View::make('components.order_list', compact('orderItems'))->render();

            // Return the rendered view as JSON
            return response()->json(['html' => $view]);
        }
    }
    
    public function updateOrderStatus(Request $request)
    {
        $order = Order::find($request->input('order_id'));
        if ($order) {
            $order->status = $request->input('status');
            $order->save();

            return response()->json(['message' => 'Order status updated successfully']);
        } else {
            return response()->json(['message' => 'Order not found'], 404);
        }
    }

}
