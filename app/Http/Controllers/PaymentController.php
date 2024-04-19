<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getMethodCard()
    {
        $stripe = new \Stripe\StripeClient(config('app.stripe_pk'));
        $user = auth()->user();
        $methods = $stripe->paymentMethods->all([
            'customer' => $user->stripe_client_id,
            'type' => 'card',
        ]);
        return $methods;
    }
    public function getCharges()
    {
        $stripe = new \Stripe\StripeClient(config('app.stripe_pk'));
        $user = auth()->user();
        $charges = $stripe->charges->all([
            'customer' => $user->stripe_client_id,
        ]);

        return new PaymentResource($charges);
    }
    public function checkout()
    {
        $stripe = new \Stripe\StripeClient(config('app.stripe_pk'));
        $checkout_session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'mode' => 'setup',
            'customer' => 'cus_Pl1B6MehO6aoIV',
            'ui_mode' => 'embedded',
            'return_url' => 'http://localhost:8004/payments/checkout?session_id={CHECKOUT_SESSION_ID}',
        ]);
        //   $cliente = \Stripe\Customer::update(
        //     'cus_Pl1B6MehO6aoIV',
        //     ['invoice_settings' => ['default_payment_method' => $metodo_pago_id]]
        //   );
        return $checkout_session;
    }

    public function customCard()
    {
        $stripe = new \Stripe\StripeClient(config('app.stripe_pk'));
        $user = auth()->user();
        $intent = $stripe->setupIntents->create([
            'customer' => $user->stripe_client_id,
            'payment_method_types' => ['card'],
            "usage" => "off_session"
        ]);

        return $intent;
    }

    public function addPaymentMethod($setup)
    {
        $stripe = new \Stripe\StripeClient(config('app.stripe_pk'));
        return $stripe->setupIntents->retrieve('seti_1EzVO3HssDVaQm2PJjXHmLlM', []);
    }
    public function retrieveSession($id)
    {
        $stripe = new \Stripe\StripeClient(config('app.stripe_pk'));
        $session =  $stripe->checkout->sessions->retrieve(
            $id,
            []
        );
        $setup =  $stripe->setupIntents->retrieve($session->setup_intent, []);

        // $stripe->paymentMethods->attach(
        //     $setup->payment_method,
        //     ['customer' => $setup->customer]
        // );
        return $setup;
    }
    public function retrieveIntent($id)
    {
        $stripe = new \Stripe\StripeClient(config('app.stripe_pk'));

        $setup =  $stripe->setupIntents->retrieve($id, []);

        // $stripe->paymentMethods->attach(
        //     $setup->payment_method,
        //     ['customer' => $setup->customer]
        // );
        return $setup;
    }

    public function payment()
    {
        $stripe = new \Stripe\StripeClient('sk_test_51OkclSL4tJJe6uDw32VnV8I1sqyoCRmJs10oGZApZeG4JQuP1rHeAnOwjOJrsPGlecS7LbYC9vObiLSU4bp0TcIh00NfFbEFhK');
        $payment = $stripe->paymentIntents->create([
            'amount' => 2000,
            'currency' => 'usd',
            'customer' => 'cus_Pl1B6MehO6aoIV',
            'payment_method' => 'pm_1P1oDQL4tJJe6uDw7H3JfkwU',
            'confirm' => true,
            'confirmation_method' => 'automatic', // Utiliza 'automatic' para pagos automáticos
            'return_url' => 'https://example.com/success'
        ]);

        return $payment;
    }
    public function getCustomer()
    {

        $stripe = new \Stripe\StripeClient('sk_test_51OkclSL4tJJe6uDw32VnV8I1sqyoCRmJs10oGZApZeG4JQuP1rHeAnOwjOJrsPGlecS7LbYC9vObiLSU4bp0TcIh00NfFbEFhK');
        $charges = $stripe->charges->all([
            'customer' => 'cus_PZnSg9mitjTacZ',
        ]);

        return $charges;
    }
}
