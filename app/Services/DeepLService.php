<?php

namespace App\Services;

use GuzzleHttp\Client;

class DeepLService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('d70dd048-3a18-490b-bfe8-e6819a2c0bdb:fx', 'https://api-free.deepl.com/v2/translate'),
        ]);
    }

    public function translate(string $text, string $to = 'EN'): string
    {
        $from = $to == 'EN' ? 'PT' : 'EN';

        try {
            $response = $this->client->post('', [
                'form_params' => [
                    'auth_key' => env('DEEPL_API_KEY'),
                    'text' => $text,
                    'source_lang' => strtoupper($from),
                    'target_lang' => strtoupper($to),
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            return $body['translations'][0]['text'] ?? $text;
        } catch (\Exception $e) {
            // fallback em caso de erro
            return $text;
        }
    }
}
