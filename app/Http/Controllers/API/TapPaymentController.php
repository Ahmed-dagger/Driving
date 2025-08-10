<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TapPaymentController extends Controller
{
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('services.tap.secret_key');
        $this->baseUrl = config('services.tap.base_url', 'https://api.tap.company/v2');
    }

    /**
     * Create a charge (payment request)
     */
    public function createCharge(Request $request)
    {
        $response = Http::withToken($this->secretKey)
            ->post($this->baseUrl . '/charges', [
                "amount" => $request->amount,
                "currency" => $request->currency ?? 'SAR',
                "threeDSecure" => true,
                "save_card" => false,
                "description" => $request->description ?? "Payment via Tap",
                "statement_descriptor" => "Darrbiny",
                "metadata" => [
                    "order_id" => $request->order_id ?? "12345"
                ],
                "reference" => [
                    "transaction" => "txn_001",
                    "order" => $request->order_id ?? "ord_001"
                ],
                "receipt" => [
                    "email" => true,
                    "sms" => true
                ],
                "customer" => [
                    "first_name" => $request->first_name ?? "Test",
                    "last_name" => $request->last_name ?? "User",
                    "email" => $request->email ?? "test@example.com",
                    "phone" => [
                        "country_code" => $request->country_code ?? "965",
                        "number" => $request->phone ?? "50000000"
                    ]
                ],
                "source" => [
                    "id" => "src_all"  // or use a token if available from frontend
                ],
                "redirect" => [
                    "url" => route('tap.callback') // Laravel route for callback
                ]
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate payment',
                'error' => $response->json()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'redirect_url' => $response['transaction']['url'],
            'charge_id' => $response['id']
        ]);
    }

    /**
     * Handle Tap's redirect after payment
     */
    public function handleCallback(Request $request)
    {
        $tap_id = $request->tap_id;

        if (!$tap_id) {
            return response('Missing tap_id', 400);
        }

        $response = Http::withToken($this->secretKey)
            ->get($this->baseUrl . "/charges/{$tap_id}");

        $data = $response->json();

        if ($data['status'] === 'CAPTURED') {
            // ✅ Payment success — mark order as paid
            return redirect()->route('payment.success');
        }

        // ❌ Payment failed or canceled
        return redirect()->route('payment.failed');
    }


    public function retrieveCharge($id)
    {
        $response = Http::withToken($this->secretKey)
            ->get($this->baseUrl . "/charges/{$id}");

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Charge not found',
                'error' => $response->json()
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $response->json()
        ]);
    }
}
