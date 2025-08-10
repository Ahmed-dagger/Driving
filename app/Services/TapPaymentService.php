<?php



namespace App\Services;

use Illuminate\Support\Facades\Http;

class TapPaymentService
{
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('services.tap.secret_key');
        $this->baseUrl = config('services.tap.base_url');
    }

    public function createCharge(array $data)
    {
        return Http::withToken($this->secretKey)
            ->post($this->baseUrl . '/charges', $data)
            ->json();
    }

    public function retrieveCharge($chargeId)
    {
        return Http::withToken($this->secretKey)
            ->get($this->baseUrl . "/charges/{$chargeId}")
            ->json();
    }
}
