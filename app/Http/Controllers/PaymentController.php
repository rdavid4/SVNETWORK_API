<?php

namespace App\Http\Controllers;

use App\Http\Resources\BalanceResource;
use App\Http\Resources\PaymentResource;
use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Object_;
use Stripe\Balance;

class PaymentController extends Controller
{
    public function getMethodCard()
    {
        $stripe = new \Stripe\StripeClient(config('app.stripe_pk'));
        $user = auth()->user();
        if($user->stripe_client_id){
            $methods = $stripe->paymentMethods->all([
                'customer' => $user->stripe_client_id,
                'type' => 'card',
            ]);
            return $methods;
        }else{
            return null;
        }
    }
    public function getCharges()
    {
        $stripe = new \Stripe\StripeClient(config('app.stripe_pk'));
        $user = auth()->user();

        if($user->stripe_client_id){
            try{
                $charges = $stripe?->charges->all([
                    'customer' => $user->stripe_client_id,
                ]);
            }catch(Exception $e){

            }
            return new PaymentResource($charges);
        }else{
            return null;
        }

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
        $stripe = new \Stripe\StripeClient(config('app.stripe_pk'));
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

        $stripe = new \Stripe\StripeClient(config('app.stripe_pk'));
        $charges = $stripe->charges->all([
            'customer' => 'cus_PZnSg9mitjTacZ',
        ]);

        return $charges;
    }

    public function getAllCharges()
    {
        $stripe = new \Stripe\StripeClient(config('app.stripe_pk'));
            $user = auth()->user();


            try{
                $charges = $stripe?->charges->all();
            }catch(Exception $e){

            }
            return new PaymentResource($charges);
    }
    public function getBalance()
    {
        $stripe = new \Stripe\StripeClient(config('app.stripe_pk'));
            $user = auth()->user();
            $balance = $stripe->balance->retrieve([]);
            return new BalanceResource($balance);
    }
}
