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
                ->connectTimeout(30) // Add connection timeout
                ->withOptions([
                    'ssl_version' => CURL_SSLVERSION_TLSv1_2,
                    'verify' => true, // Verify SSL certificates
                    'curl' => [
                        CURLOPT_CONNECTTIMEOUT => 30,
                        CURLOPT_TIMEOUT => 120,
                        CURLOPT_SSL_VERIFYPEER => true,
                        CURLOPT_SSL_VERIFYHOST => 2,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_MAXREDIRS => 3,
                    ]
                ])
                ->retry(2, 1000) // Retry 2 times with 1 second delay
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
                ->connectTimeout(20) // Shorter connection timeout for fallback
                ->withOptions([
                    'ssl_version' => CURL_SSLVERSION_TLSv1_2,
                    'verify' => true,
                    'curl' => [
                        CURLOPT_CONNECTTIMEOUT => 20,
                        CURLOPT_TIMEOUT => 90,
                        CURLOPT_SSL_VERIFYPEER => true,
                        CURLOPT_SSL_VERIFYHOST => 2,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_MAXREDIRS => 3,
                    ]
                ])
                ->retry(1, 500) // Single retry with shorter delay for fallback
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
    
    public function testConnection(): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->timeout(10)
                ->connectTimeout(5)
                ->withOptions([
                    'ssl_version' => CURL_SSLVERSION_TLSv1_2,
                    'verify' => true,
                ])
                ->post($this->url, [
                    'model' => 'mistralai/Mistral-7B-Instruct-v0.3',
                    'prompt' => 'Test connection',
                    'max_tokens' => 10,
                    'temperature' => 0.1,
                ]);
                
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('AI Service Connection Test Failed: ' . $e->getMessage());
            return false;
        }
    }
    
    public function getConnectionStatus(): array
    {
        $isConnected = $this->testConnection();
        
        return [
            'connected' => $isConnected,
            'api_key_configured' => !empty($this->apiKey),
            'endpoint' => $this->url,
            'message' => $isConnected 
                ? 'AI service is available' 
                : 'AI service is currently unavailable. Please check your internet connection and API configuration.'
        ];
    }
}
