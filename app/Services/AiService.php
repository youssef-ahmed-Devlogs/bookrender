<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->timeout(90) // Standard timeout for Mistral model
                ->withOptions([
                    'ssl_version' => CURL_SSLVERSION_TLSv1_2
                ])
                ->post($this->url, [
                    'model' => 'mistralai/Mistral-7B-Instruct-v0.3', // Revert to original working model
                    'prompt' => $this->prompt,
                    'max_tokens' => 2000, // Reasonable token limit for the model
                    'temperature' => 0.7, // Add some creativity
                    'top_p' => 0.9,
                ]);

            if (!$response->successful()) {
                Log::error('AI API Error: ' . $response->body());
                throw new Exception('AI service is currently unavailable. Please try again later.');
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('AI Service Error: ' . $e->getMessage());
            throw new Exception('Failed to generate content. Please try again.');
        }
    }
}
