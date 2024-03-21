<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function storeCard(Request $request)
    {
        $request->validate([
            'card'=> 'required'
        ]);

        $user = auth()->user();

        $stripe = new \Stripe\StripeClient("sk_test_51OkclSL4tJJe6uDw32VnV8I1sqyoCRmJs10oGZApZeG4JQuP1rHeAnOwjOJrsPGlecS7LbYC9vObiLSU4bp0TcIh00NfFbEFhK");
        $paymentMethod = $stripe->paymentMethods->create([
            'type' => 'card',
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 8,
                'exp_year' => 2026,
                'cvc' => '314',
            ],
        ]);


        $stripe->paymentMethods->attach(
        'pm_1MqM05LkdIwHu7ixlDxxO6Mc',
        ['customer' => $user->stripe_client_id]
        );

        return $paymentMethod;
    }
}
