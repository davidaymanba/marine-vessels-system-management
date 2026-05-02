<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        if (!config('services.whatsapp.enabled')) {
            return;
        }

        $phone = $notifiable->phone ?? null;
        $message = method_exists($notification, 'toWhatsapp')
            ? $notification->toWhatsapp($notifiable)
            : null;

        if (!$phone || !$message) {
            return;
        }

        $token = config('services.whatsapp.token');
        $phoneNumberId = config('services.whatsapp.phone_number_id');
        $version = config('services.whatsapp.version', 'v20.0');

        if (!$token || !$phoneNumberId) {
            Log::warning('WhatsApp notification skipped because API credentials are missing.');
            return;
        }

        $response = Http::withToken($token)
            ->acceptJson()
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $message,
                ],
            ]);

        if (!$response->successful()) {
            Log::error('WhatsApp notification failed.', [
                'phone' => $phone,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
    }
}
