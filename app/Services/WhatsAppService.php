<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class WhatsAppService
{
    protected $client;
    protected $lastSentTime = 0;
    protected $rateLimit = 12; // 12 saniye bekle (5 mesaj/dakika)

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 30,
            'verify' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ]
        ]);
    }

    public function sendMessage($phone, $message)
    {
        try {
            // Rate limit kontrolü
            $this->checkRateLimit();

            // Telefon numarasını formatla
            $formattedPhone = $this->formatPhone($phone);

            // WhatsApp Cloud API endpoint (gerçek API için)
            $phoneNumberId = config('whatsapp.phone_number_id', 'TEST_PHONE_ID');
            $accessToken = config('whatsapp.access_token', 'TEST_TOKEN');

            $response = $this->client->post("https://graph.facebook.com/v18.0/{$phoneNumberId}/messages", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'messaging_product' => 'whatsapp',
                    'to' => $formattedPhone,
                    'type' => 'text',
                    'text' => [
                        'body' => $message
                    ]
                ]
            ]);

            // Rate limit güncelle
            $this->lastSentTime = time();

            return [
                'success' => true,
                'message' => 'Mesaj gönderildi!',
                'phone' => $formattedPhone
            ];

        } catch (RequestException $e) {
            return [
                'success' => false,
                'error' => 'Mesaj gönderilemedi: ' . $e->getMessage()
            ];
        }
    }

    private function formatPhone($phone)
    {
        // Telefon numarasını temizle
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // + ile başlamıyorsa ekle
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        return $phone;
    }

    private function checkRateLimit()
    {
        $currentTime = time();
        $timeDiff = $currentTime - $this->lastSentTime;

        if ($timeDiff < $this->rateLimit) {
            $waitTime = $this->rateLimit - $timeDiff;
            sleep($waitTime);
        }
    }
}
