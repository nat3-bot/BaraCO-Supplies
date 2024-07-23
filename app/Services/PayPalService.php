<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayPalService
{
    public static function getAccessToken()
    {
        try {
            $response = Http::withBasicAuth(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'))
                            ->post('https://api.sandbox.paypal.com/v1/oauth2/token', [
                                'grant_type' => 'client_credentials'
                            ]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            } else {
                // Handle error
                return null;
            }
        } catch (\Throwable $th) {
            // Handle exceptions
            return null;
        }
    }
}
