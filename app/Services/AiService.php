<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class AiService
{
    private string $apiKey;
    private string $url;
    private string $prompt;

    public function __construct()
    {
        $this->apiKey = config('services.togetherai.api_key');
        $this->url = "https://api.together.xyz/v1/completions";
    }

    public function buildPrompt(callable $callback)
    {
        $this->prompt = $callback();
        return $this;
    }

    public function send()
    {
        if ($this->prompt === "") throw new Exception('Please buildPrompt first.');

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type' => 'application/json',
        ])->timeout(90)
            ->withOptions([
                'ssl_version' => CURL_SSLVERSION_TLSv1_2
            ])
            ->post($this->url, [
                'model' => 'mistralai/Mistral-7B-Instruct-v0.3',
                'prompt' => $this->prompt,
                'max_tokens' => 1024,
            ]);

        return $response;
    }
}
