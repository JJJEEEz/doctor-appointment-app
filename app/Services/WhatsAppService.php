<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function sendMessage(string $phone, string $message): bool
    {
        if (! (bool) config('services.whatsapp.enabled', false)) {
            Log::info('WhatsApp deshabilitado por configuracion.', ['phone' => $phone]);

            return false;
        }

        $provider = (string) config('services.whatsapp.provider', 'callmebot');

        if ($provider !== 'callmebot') {
            Log::warning('Proveedor de WhatsApp no soportado.', ['provider' => $provider]);

            return false;
        }

        return $this->sendViaCallMeBot($phone, $message);
    }

    private function sendViaCallMeBot(string $phone, string $message): bool
    {
        $apiKey = (string) config('services.whatsapp.callmebot_api_key');

        if ($apiKey === '') {
            Log::warning('API key de CallMeBot no configurada.');

            return false;
        }

        $normalizedPhone = preg_replace('/\D+/', '', $phone) ?? '';
        $defaultPrefix = (string) config('services.whatsapp.default_country_prefix', '');

        if (strlen($normalizedPhone) === 10 && $defaultPrefix !== '') {
            $normalizedPhone = $defaultPrefix.$normalizedPhone;
        }

        if ($normalizedPhone === '') {
            Log::warning('Telefono invalido para WhatsApp.', ['phone' => $phone]);

            return false;
        }

        $baseUrl = (string) config('services.whatsapp.callmebot_base_url', 'https://api.callmebot.com/whatsapp.php');

        $response = Http::timeout(10)->get($baseUrl, [
            'phone' => $normalizedPhone,
            'text' => $message,
            'apikey' => $apiKey,
        ]);

        if (! $response->successful()) {
            Log::warning('Error al enviar WhatsApp con CallMeBot.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        }

        $body = strtolower($response->body());

        if (str_contains($body, 'error') || str_contains($body, 'invalid') || str_contains($body, 'missing')) {
            Log::warning('Respuesta invalida de CallMeBot al enviar WhatsApp.', [
                'phone' => $normalizedPhone,
                'body' => $response->body(),
            ]);

            return false;
        }

        return true;
    }
}
