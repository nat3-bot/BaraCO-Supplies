<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Supplies;
use App\Models\User;
use App\Models\Cart;

class CartController extends Controller
{
    
    public function addToCart(Request $request){
        $userId = Auth::user();
        $productId = Supplies::find($request->suppliesId);
        
        $cart = Cart::updateOrCreate([
            'user_id' => $userId->id,
            'product_id' => $productId->id,
            'quantity'=> $request->productQuantity,
        ]);
        
        return response()->json($cart);
    }

    public function delete(Request $request)
    {
        $productId = $request->input('product_id');

        $cartItem = Cart::where('user_Id', Auth::id())
                        ->where('product_id', $productId)
                        ->first();

        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['success' => true, 'message' => 'Item deleted successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }
    }

    public function userCartView(){
       return view('layouts.user_cart');
    }

    public function loadCart()
    {
        $cartItems = Cart::where('user_id', Auth::user()->id)->get(); // use user_id with a lowercase 'i'
    
        // Eager load the products related to the cart items
        $cartItems->load('product');
        $view = View::make('components.user_cart_items', compact('cartItems'))->render();
        return response()->json(['html' => $view]);
    }
    
    
    public function cartPage(){
        $cart = Supplies::find(Auth::user()->id);

        return view('product_page', ['cart' => $cart]);
    }

}
