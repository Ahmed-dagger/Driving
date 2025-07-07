<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioVerifyService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function sendVerification($phoneNumber)
    {
        return $this->twilio->verify->v2->services(config('services.twilio.verify_sid'))
            ->verifications
            ->create($phoneNumber, 'sms');
    }

    public function checkVerification($phoneNumber, $code)
    {
        return $this->twilio->verify->v2->services(config('services.twilio.verify_sid'))
            ->verificationChecks
            ->create([
                'to' => $phoneNumber,
                'code' => $code,
            ]);
    }
}
