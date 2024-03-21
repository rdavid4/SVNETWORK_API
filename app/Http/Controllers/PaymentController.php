<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function checkout(){
        $stripe = new \Stripe\StripeClient("sk_test_51OkclSL4tJJe6uDw32VnV8I1sqyoCRmJs10oGZApZeG4JQuP1rHeAnOwjOJrsPGlecS7LbYC9vObiLSU4bp0TcIh00NfFbEFhK");
        $checkout = $stripe->checkout->sessions->create([
            'line_items' => [
              [
                'price_data' => [
                    'currency' => 'USD',
                    'product_data' => [
                        'name' => 'Pago por matches'
                    ],
                    'unit_amount' => 3000
                ],
                'quantity' => 1,
              ],
            ],
            'mode' => 'payment',
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel'
          ]);

        return $checkout;
    }

    public function payment(){
        $stripe = new \Stripe\StripeClient('sk_test_51OkclSL4tJJe6uDw32VnV8I1sqyoCRmJs10oGZApZeG4JQuP1rHeAnOwjOJrsPGlecS7LbYC9vObiLSU4bp0TcIh00NfFbEFhK');
        $payment = $stripe->paymentIntents->create([
            'amount' => 2000,
            'currency' => 'usd',
            'customer' => 'cus_PZnSg9mitjTacZ',
            'payment_method' => 'pm_1OkdskL4tJJe6uDwCYMHdw5t',
            'confirm' => true,
            'confirmation_method' => 'automatic', // Utiliza 'automatic' para pagos automáticos
            'return_url'=>'https://example.com/success'
        ]);

        return $payment;
    }
    public function getCustomer(){

        $stripe = new \Stripe\StripeClient('sk_test_51OkclSL4tJJe6uDw32VnV8I1sqyoCRmJs10oGZApZeG4JQuP1rHeAnOwjOJrsPGlecS7LbYC9vObiLSU4bp0TcIh00NfFbEFhK');
        $charges = $stripe->charges->all([
            'customer' => 'cus_PZnSg9mitjTacZ',
        ]);

        return $charges;
    }

}
