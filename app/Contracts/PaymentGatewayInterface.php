<?

namespace App\Contracts;

interface PaymentGatewayInterface {
    public function addClient(Array $client);
}
