<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LahzaService
{
    /**
     * المفتاح السري
     */
    protected static function secretKey(): ?string
    {
        return config('services.lahza.secret_key');
    }

    /**
     * رابط API الأساسي
     */
    protected static function baseUrl(): string
    {
        return config('services.lahza.base_url', 'https://api.lahza.io');
    }

    /**
     * هل الدفع الإلكتروني مُفعّل؟
     */
    public static function isEnabled(): bool
    {
        return config('services.lahza.enabled', false) 
            && !empty(self::secretKey());
    }

    /**
     * بدء عملية دفع جديدة
     * 
     * @return array{success: bool, authorization_url?: string, reference?: string, message?: string}
     */
    public static function initializeTransaction(Order $order): array
    {
        try {
            $payload = [
                'amount' => (int) round($order->total * 100), // الشيكل × 100 = أغورة
                'email' => $order->customer->email,
                'currency' => config('services.lahza.currency', 'ILS'),
                'reference' => $order->order_number,
                'callback_url' => route('checkout.lahza.callback', $order),
                'channels' => ['card', 'palpay', 'bank_of_palestine'],
                'metadata' => [
                    'order_id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'store_id' => $order->store_id,
                ],
            ];

            $response = Http::withToken(self::secretKey())
                ->acceptJson()
                ->timeout(30)
                ->post(self::baseUrl() . '/transaction/initialize', $payload);

            $data = $response->json();

            Log::info('Lahza: initialize response', [
                'status' => $response->status(),
                'order' => $order->order_number,
                'data' => $data,
            ]);

            if ($response->successful() && !empty($data['data']['authorization_url'])) {
                return [
                    'success' => true,
                    'authorization_url' => $data['data']['authorization_url'],
                    'reference' => $data['data']['reference'] ?? $order->order_number,
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'فشل الاتصال ببوابة الدفع',
            ];

        } catch (\Throwable $e) {
            Log::error('Lahza: initialize failed', [
                'order' => $order->order_number,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'حدث خطأ في الاتصال ببوابة الدفع: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * التحقق من صحة معاملة
     */
    public static function verifyTransaction(string $reference): array
    {
        try {
            $response = Http::withToken(self::secretKey())
                ->acceptJson()
                ->timeout(30)
                ->get(self::baseUrl() . '/transaction/verify/' . urlencode($reference));

            $data = $response->json();

            Log::info('Lahza: verify response', [
                'reference' => $reference,
                'status' => $response->status(),
                'data' => $data,
            ]);

            if ($response->successful() && isset($data['data']['status'])) {
                return [
                    'success' => true,
                    'status' => $data['data']['status'], // success / failed / abandoned / pending
                    'amount' => $data['data']['amount'] ?? 0,
                    'currency' => $data['data']['currency'] ?? 'ILS',
                    'raw' => $data['data'],
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'فشل التحقق من المعاملة',
            ];

        } catch (\Throwable $e) {
            Log::error('Lahza: verify failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'خطأ في التحقق: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * استخراج مرجع الطلب من Webhook
     */
    public static function extractReferenceFromWebhook(array $payload): ?string
    {
        return $payload['data']['reference'] 
            ?? $payload['reference'] 
            ?? null;
    }

    /**
     * استخراج الحالة من Webhook
     */
    public static function extractStatusFromWebhook(array $payload): ?string
    {
        return $payload['data']['status'] 
            ?? $payload['status'] 
            ?? null;
    }
}