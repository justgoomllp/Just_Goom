<?php

namespace App\Services\Front;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class RazorpayService
{
    private const API_BASE = 'https://api.razorpay.com/v1';

    public function key(): string
    {
        $key = (string) config('services.razorpay.key');

        if ($key === '') {
            throw new RuntimeException('Razorpay key is not configured.');
        }

        return $key;
    }

    public function secret(): string
    {
        $secret = (string) config('services.razorpay.secret');

        if ($secret === '') {
            throw new RuntimeException('Razorpay secret is not configured.');
        }

        return $secret;
    }

    /**
     * @param  array<string, mixed>  $notes
     * @return array<string, mixed>
     */
    public function createOrder(int $amountPaise, string $receipt, array $notes = []): array
    {
        return $this->request('post', '/orders', [
            'amount' => $amountPaise,
            'currency' => 'INR',
            'receipt' => $receipt,
            'payment_capture' => 1,
            'notes' => $notes,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function fetchPayment(string $paymentId): array
    {
        return $this->request('get', '/payments/'.$paymentId);
    }

    public function verifyCheckoutSignature(string $orderId, string $paymentId, string $signature): bool
    {
        $expected = hash_hmac('sha256', $orderId.'|'.$paymentId, $this->secret());

        return hash_equals($expected, $signature);
    }

    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $secret = (string) config('services.razorpay.webhook_secret');

        if ($secret === '') {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    private function request(string $method, string $path, array $body = []): array
    {
        $pending = Http::withBasicAuth($this->key(), $this->secret())
            ->acceptJson()
            ->timeout(20)
            ->retry(1, 250);

        try {
            $response = $method === 'get'
                ? $pending->get(self::API_BASE.$path)
                : $pending->post(self::API_BASE.$path, $body);
        } catch (ConnectionException|RequestException $e) {
            throw new RuntimeException('Unable to reach Razorpay. Please try again.', 0, $e);
        }

        if ($response->failed()) {
            $message = $response->json('error.description')
                ?? $response->json('error.reason')
                ?? 'Razorpay request failed.';

            throw new RuntimeException((string) $message);
        }

        return $response->json() ?? [];
    }
}
