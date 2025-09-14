<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaddleService
{
    protected $baseURL = "";

    public function __construct()
    {
        $this->baseURL = config('paddle.base_url');
    }

    public function createProduct(array $data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('paddle.api_key')
            ])->post("{$this->baseURL}/products", [
                "tax_category" => "standard",
                ...$data,
            ]);

            if (!$response->successful()) {
                throw new Exception("An Error happens while creating a paddle product");
            }

            $product = $response->json();

            if (!isset($product['data']['id'])) {
                throw new Exception("No (Product Id) while creating a paddle product");
            }

            $product = $product['data'];

            return $product;
        } catch (Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            throw $e;
        }
    }

    public function createProductPrice(array $data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('paddle.api_key')
            ])->post("{$this->baseURL}/prices", [
                "billing_cycle" =>  [
                    "interval" =>  "month",
                    "frequency" =>  1
                ],
                "quantity" => [
                    "minimum" => 1,
                    "maximum" => 1,
                ],
                ...$data,
            ]);


            if (!$response->successful()) {
                throw new Exception("An Error happens while creating a paddle product price");
            }

            $price = $response->json();

            if (!isset($price['data']['id'])) {
                throw new Exception("No (Price Id) while creating a paddle product price");
            }

            $price = $price['data'];

            return $price;
        } catch (Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            throw $e;
        }
    }
}
