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
            ])->timeout(120) // Increased timeout for longer content generation
                ->withOptions([
                    'ssl_version' => CURL_SSLVERSION_TLSv1_2
                ])
                ->post($this->url, [
                    'model' => 'mistralai/Mistral-7B-Instruct-v0.3', // Working serverless model
                    'prompt' => $this->prompt,
                    'max_tokens' => 4000, // Increased token limit for longer content
                    'temperature' => 0.7, // Add creativity while maintaining coherence
                    'top_p' => 0.9, // Nucleus sampling for better quality
                    'frequency_penalty' => 0.1, // Reduce repetition
                    'presence_penalty' => 0.1, // Encourage topic diversity
                    'stop' => null, // Let the model complete naturally
                ]);

            if (!$response->successful()) {
                Log::error('AI API Error: ' . $response->body());
                
                // Try fallback model if primary fails
                return $this->sendWithFallback();
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('AI Service Error: ' . $e->getMessage());
            
            // Try fallback model
            return $this->sendWithFallback();
        }
    }

    private function sendWithFallback()
    {
        try {
            Log::info('Attempting fallback model for AI generation');
            
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->timeout(90)
                ->withOptions([
                    'ssl_version' => CURL_SSLVERSION_TLSv1_2
                ])
                ->post($this->url, [
                    'model' => 'NousResearch/Nous-Hermes-2-Mixtral-8x7B-DPO', // Alternative fallback model
                    'prompt' => $this->prompt,
                    'max_tokens' => 2000,
                    'temperature' => 0.7,
                    'top_p' => 0.9,
                ]);

            if (!$response->successful()) {
                Log::error('Fallback AI API Error: ' . $response->body());
                throw new Exception('Both primary and fallback AI services are currently unavailable. Please try again later.');
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('Fallback AI Service Error: ' . $e->getMessage());
            throw new Exception('Failed to generate content with both primary and fallback models. Please try again.');
        }
    }
}
