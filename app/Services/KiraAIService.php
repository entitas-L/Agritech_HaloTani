<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KiraAIService
{
    public static function generateSolusi(string $keluhanPetani, string $lokasiLahan): string
    {
        $apiKey = config('services.openrouter.key') ?? env('OPENROUTER_API_KEY');

        if (empty($apiKey)) {
            throw new \Exception('ERROR: OPENROUTER_API_KEY belum terdeteksi!');
        }

        $prompt = "Kamu adalah asisten ahli pertanian 'Agritech Assistant'.\n" .
            "Lokasi Lahan: {$lokasiLahan}\n" .
            "Keluhan Tanaman: {$keluhanPetani}\n\n" .
            "ATURAN FORMATTING:\n" .
            "1. JANGAN gunakan simbol '•' inline dalam satu paragraf.\n" .
            "2. WAJIB gunakan format Markdown list (gunakan tanda '-' atau angka di baris baru) untuk setiap poin agar mudah dibaca.\n" .
            "3. Tulis jawaban secara ringkas, padat, dan langsung ke poin utama.";

        $primaryModel = config('services.openrouter.model');
        $models = array_values(array_unique(array_filter([
            $primaryModel,
            'openrouter/free',
        ])));

        $lastError = null;

        foreach ($models as $model) {
            try {
                $response = Http::timeout(25)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                        'HTTP-Referer'  => config('app.url', 'https://www.snowfluff.cloud-ip.cc'),
                        'X-Title'       => config('app.name', 'HaloTani'),
                        'Content-Type'  => 'application/json',
                    ])
                    ->post('https://openrouter.ai/api/v1/chat/completions', [
                        'model' => $model,
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'max_tokens' => 1500,
                        'temperature' => 0.7,
                    ]);

                $content = $response->json('choices.0.message.content');

                if ($response->successful() && !empty($content)) {
                    return $content;
                }

                $lastError = "HTTP {$response->status()}: " . ($response->json('error.message') ?? $response->body());
                Log::warning('OpenRouter gagal merespons', [
                    'model' => $model,
                    'status' => $response->status(),
                    'error' => $response->json('error') ?? $response->body(),
                ]);
            } catch (\Exception $e) {
                $lastError = $e->getMessage();
                Log::warning('OpenRouter exception', [
                    'model' => $model,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        throw new \Exception('Sistem AI sedang mengalami antrean padat di semua server. Silakan klik tombol analisis sekali lagi.' . (config('app.debug') && $lastError ? " ({$lastError})" : ''));
    }
}