<?php
namespace App\Adapters;

use App\Contracts\PaymentGatewayInterface;

class PaymentGateway
{
    public $gateway;

    public function __construct( $gateway) {

        $this->gateway = $gateway;
    }

    public function addClient(Array $client)
    {
        $client = $this->gateway->customers->create($client);
        return $client;
    }
    public function removeClient()
    {

    }
    public function updateClient()
    {

    }
}
