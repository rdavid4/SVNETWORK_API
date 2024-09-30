<?php
namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
    }

    public function sendVerificationCode($toPhoneNumber, $verificationCode)
    {
        $message = "Welcome to ".config('app.name'). "\n Your verification code is: " . $verificationCode;

        $this->twilio->messages->create($toPhoneNumber, [
            'from' => config('services.twilio.phone_number'),
            'body' => $message,
        ]);
    }
}
?>
