<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SuppliesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

//Login Page
Route::get('/', function () {return view('auth.login');});

//Admin Routes
Route::get('admin',function(){return view('admin_dashboard');})->middleware(['auth'])->name('admin');

//Product CRUD
Route::post('addSupplies', [SuppliesController::class, 'addSupplies']);
Route::post('editSupplies', [SuppliesController::class, 'editSupplies']);
Route::post('deleteSupplies', [SuppliesController::class, 'deleteSupplies']);
Route::get('suppliesTable', [SuppliesController::class, 'getSupplies']);

//User List
Route::get('users-list', [UserController::class, 'userListView'])->name('user-list');
Route::get('usersTable', [UserController::class, 'userTable']);

//Order List
Route::get('order-list', [OrderController::class, 'orderListView'])->name('order-list');
Route::get('order-load', [OrderController::class, 'loadOrders'])->name('order.load');
Route::post('/update-order-status', [OrderController::class, 'updateOrderStatus'])->name('update.order.status');

//Payment List
Route::get('payment-list', [PaymentController::class, 'paymentListView'])->name('payment-list');
Route::get('payment-load', [PaymentController::class, 'loadPayments'])->name('payment.load');

//Import Users List
Route::post('importUsers', [UserController::class, 'importUsers'])->name('import.users');
//Export Users List
Route::get('exportUsers',[UserController::class, 'exportUsers']);

//Client User Routes
Route::get('BaraCoSupplies',function(){return view('product_list');})->middleware(['auth'])->name('BaraCoSupplies');

//Product Page and list and Search
Route::get('product-list', [SuppliesController::class, 'productListView'])->name('product-list');
Route::get('product-search',[SuppliesController::class, 'searchProducts'])->name('products.search');
Route::get('product-load', [SuppliesController::class, 'loadProducts'])->name('products.load');
Route::get('product-page/{id}', [SuppliesController::class, 'productPage'])->name('product.page');
Route::post('importSupplies', [SuppliesController::class, 'importSupplies'])->name('importSupplies');
Route::get('exportSupplies', [SuppliesController::class, 'exportSupplies'])->name('exportSupplies');

//Add to Cart 
Route::post('add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
Route::get('userCart', [CartController::class, 'userCartView'])->name('cart');
Route::get('cart-load', [CartController::class, 'loadCart'])->name('cart.load');

//Delete Item from Cart
Route::delete('/cart/delete', [CartController::class, 'delete'])->name('cart.delete');

//Checkout
Route::post('checkout', [OrderController::class, 'checkout'])->name('checkout');

//Paypal Controller
Route::post('paypal', [PaymentController::class,'paypal'])->name('paypal');
Route::get('paypal/success', [PaymentController::class, 'success'])->name('paypal.success');
Route::get('paypal/cancel', [PaymentController::class, 'cancel'])->name('paypal.cancel');


//Google Socialite
Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
Route::get('auth/google/callback', [GoogleAuthController::class,'callbackGoogle']);


Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
