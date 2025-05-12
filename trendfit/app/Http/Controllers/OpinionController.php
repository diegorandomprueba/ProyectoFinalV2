<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpinionService;
use App\Models\ComandaProd;
use Illuminate\Support\Facades\Auth;

class OpinionController extends Controller
{
    protected $opinionService;
    
    public function __construct(OpinionService $opinionService)
    {
        $this->opinionService = $opinionService;
    }
    
    public function getOpinions($productId)
    {
        try {
            $opinions = $this->opinionService->getOpinions($productId);
            return response()->json($opinions);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener las opiniones: ' . $e->getMessage()], 500);
        }
    }
    
    public function storeOpinion(Request $request)
    {
        $request->validate([
            'productId' => 'required|integer|exists:productes,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:150',
        ]);
        
        // Verificar si el usuario ha comprado el producto
        $user = Auth::user();
        $hasPurchased = ComandaProd::whereHas('comanda', function($query) use ($user) {
                                    $query->where('idUsuari', $user->id);
                                })
                                ->where('idProducte', $request->productId)
                                ->exists();
        
        if (!$hasPurchased && !$user->isAdmin()) {
            return response()->json(['error' => 'Debes comprar el producto antes de valorarlo'], 403);
        }
        
        try {
            $data = [
                'productId' => $request->productId,
                'userId' => $user->id,
                'userName' => $user->name,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'date' => now()->format('Y-m-d H:i:s')
            ];
            
            $result = $this->opinionService->sendOpinion($data);
            
            if ($result['success']) {
                // Marcar el producto como valorado en las órdenes del usuario
                ComandaProd::whereHas('comanda', function($query) use ($user) {
                                $query->where('idUsuari', $user->id);
                            })
                            ->where('idProducte', $request->productId)
                            ->update(['has_to_comment' => false]);
                
                return response()->json(['success' => true, 'data' => $result['data']]);
            } else {
                return response()->json(['error' => $result['message']], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al guardar la opinión: ' . $e->getMessage()], 500);
        }
    }
    
    public function getRating($limit = 10)
    {
        try {
            $ratings = $this->opinionService->getTopRatedProducts($limit);
            return response()->json($ratings);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los ratings: ' . $e->getMessage()], 500);
        }
    }
    
    public function showOrderReviews($orderId)
    {
        $user = Auth::user();
        $order = $user->comandas()->findOrFail($orderId);
        
        return view('pages.orders.reviews', compact('order'));
    }
    
    public function skipReview(Request $request, $productId)
    {
        $user = Auth::user();
        
        // Marcar el producto como "no quiere valorar" en todas las órdenes del usuario
        ComandaProd::whereHas('comanda', function($query) use ($user) {
                        $query->where('idUsuari', $user->id);
                    })
                    ->where('idProducte', $productId)
                    ->update(['has_to_comment' => false]);
        
        return response()->json(['success' => true]);
    }
    
    public function remindLater($orderId)
    {
        $user = Auth::user();
        $order = $user->comandas()->findOrFail($orderId);
        
        // Marcar todos los productos del pedido para recordar más tarde
        foreach ($order->products as $product) {
            ComandaProd::where('idComanda', $order->id)
                      ->where('idProducte', $product->id)
                      ->update(['has_to_comment' => true]);
        }
        
        return response()->json(['success' => true]);
    }
    
    public function pendingReviews()
    {
        $user = Auth::user();
        
        // Obtener los productos pendientes de valorar
        $pendingProducts = ComandaProd::whereHas('comanda', function($query) use ($user) {
                                $query->where('idUsuari', $user->id);
                            })
                            ->where('has_to_comment', true)
                            ->with(['producto', 'comanda'])
                            ->get();
        
        if ($pendingProducts->count() > 0) {
            return response()->json([
                'hasPending' => true,
                'products' => $pendingProducts
            ]);
        }
        
        return response()->json(['hasPending' => false]);
    }
}