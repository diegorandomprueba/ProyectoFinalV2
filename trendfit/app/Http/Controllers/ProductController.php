<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Services\OpinionService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $opinionService;
    
    public function __construct(OpinionService $opinionService)
    {
        $this->opinionService = $opinionService;
    }
    
    public function index(Request $request)
    {
        $query = Producto::query();
        
        // Aplicar filtros si existen
        if ($request->has('categoria')) {
            $query->whereHas('subcategoria', function($q) use ($request) {
                $q->where('idCategoria', $request->categoria);
            });
        }
        
        if ($request->has('subcategoria')) {
            $query->where('idCategoria', $request->subcategoria);
        }
        
        // Buscar por nombre o descripción
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('descr', 'like', "%{$search}%");
            });
        }
        
        // Filtrar por rango de precio
        if ($request->has('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Ordenar productos
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('id', 'desc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc'); // Por defecto, productos más recientes
        }
        
        // Solo mostrar productos con stock
        $query->where('stock', '>', 0);
        
        $productos = $query->paginate(12);
        $categorias = Categoria::all();
        $subcategorias = Subcategoria::all();
        
        return view('pages.shop', compact('productos', 'categorias', 'subcategorias'));
    }
    
    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        
        // Obtener la valoración media del producto
        $opinions = $this->opinionService->getOpinions($producto->id);
        
        if (!empty($opinions)) {
            // Calcular la valoración media
            $totalRating = 0;
            foreach ($opinions as $opinion) {
                $totalRating += $opinion['rating'];
            }
            $producto->averageRating = $totalRating / count($opinions);
            $producto->numRatings = count($opinions);
        } else {
            $producto->averageRating = 0;
            $producto->numRatings = 0;
        }
        
        // Obtener productos relacionados
        $relatedProducts = Producto::where('idCategoria', $producto->idCategoria)
                                    ->where('id', '!=', $producto->id)
                                    ->where('stock', '>', 0)
                                    ->take(4)
                                    ->get();
        
        return view('pages.product.show', compact('producto', 'relatedProducts'));
    }
}