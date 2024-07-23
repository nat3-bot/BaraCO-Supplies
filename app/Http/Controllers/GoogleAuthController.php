<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleAuthController extends Controller
{
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle(){
        
        
        $google_user = Socialite::driver('google')->user();

        $findUser = User::where('google_id', $google_user->id)->first();

        if($findUser){
            Auth::login($findUser);
        }
        else{
        
            $user = User::updateOrCreate([
                'name' => $google_user->getName(),
                'email' => $google_user->getEmail(),
                'google_id' => $google_user->getId()
            ]);

            Auth::login($user);
            return redirect()->route('profile.edit');
            
        }
        
        return redirect()->route('BaraCoSupplies');

        
        
    }
}
