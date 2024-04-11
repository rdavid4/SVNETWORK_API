<?php

namespace App\Listeners;

use App\Adapters\PaymentGateway;
use App\Events\UserContractorRegistered;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Stripe\StripeClient;

class CreateStripeClient
{
    /**
     * Create the event listener.
     */
    public $paymentGateway;
    public function __construct()
    {
        $gateway = new StripeClient("sk_test_51OkclSL4tJJe6uDw32VnV8I1sqyoCRmJs10oGZApZeG4JQuP1rHeAnOwjOJrsPGlecS7LbYC9vObiLSU4bp0TcIh00NfFbEFhK");
        $this->paymentGateway = new PaymentGateway($gateway);
    }

    /**
     * Handle the event.
     */
    public function handle(UserContractorRegistered $event): void
    {
        $user = $event->user;
        $fullName = $user->name.' '.$user->surname;

        $gateway = $this->paymentGateway;

        $user = User::where('email', $event->user->email)->first();
        if(!$user->stripe_client_id){
            $response = $gateway->addClient([
            'name' => $fullName,
            'email' => $user->email,
            ]);
            $user->stripe_client_id = $response->id;
            $user->save();
        }
    }
}
