<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpinionService
{
    protected $apiUrl;
    
    public function __construct()
    {
        $this->apiUrl = env('API_URL', 'http://localhost:8080');
    }
    
    public function getOpinions($productId)
    {
        try {
            $response = Http::get("{$this->apiUrl}/api/opinions/{$productId}");
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return [];
        } catch (\Exception $e) {
            \Log::error('Error fetching opinions: ' . $e->getMessage());
            return [];
        }
    }
    
    public function sendOpinion($data)
    {
        try {
            $response = Http::post("{$this->apiUrl}/api/opinions", $data);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }
            
            return [
                'success' => false,
                'message' => $response->json()['message'] ?? 'Error al enviar la opinión'
            ];
        } catch (\Exception $e) {
            \Log::error('Error sending opinion: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error de conexión con la API'
            ];
        }
    }
    
    public function getTopRatedProducts($limit = 10)
    {
        try {
            $response = Http::get("{$this->apiUrl}/api/ratings", [
                'limit' => $limit
            ]);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return [];
        } catch (\Exception $e) {
            \Log::error('Error fetching top rated products: ' . $e->getMessage());
            return [];
        }
    }
}