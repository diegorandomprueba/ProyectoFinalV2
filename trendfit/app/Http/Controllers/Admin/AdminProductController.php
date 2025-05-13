<?php

namespace App\Http\Controllers\Admin;

use App\Models\Producto; 
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Comanda;
use App\Models\User;
use App\Models\ComandaProd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminProductController extends Controller
{
    
    public function index(Request $request)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $query = Producto::query();
        
        // Aplicar filtros
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('descr', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('subcategoria', function($q) use ($request) {
                $q->where('idCategoria', $request->category);
            });
        }
        
        if ($request->has('stock')) {
            if ($request->stock === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock === 'out_of_stock') {
                $query->where('stock', 0);
            }
        }
        
        // Ordenar
        $query->orderBy('id', 'desc');
        
        $products = $query->paginate(20);
        $categories = Categoria::all();
        
        return view('admin.products.index', compact('products', 'categories'));
    }
    
    public function create()
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $categories = Categoria::all();
        $subcategories = Subcategoria::all();
        return view('admin.products.create', compact('categories', 'subcategories'));
    }
    
    public function store(Request $request)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'descr' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'idCategoria' => 'required|exists:subcategoria,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Subir imagen
        $imagePath = $request->file('image')->store('products', 'public');
        
        // Crear producto
        $product = new Producto();
        $product->name = $request->name;
        $product->descr = $request->descr;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->idCategoria = $request->idCategoria;
        $product->image = $imagePath;
        $product->save();
        
        return redirect()->route('admin.products.index')->with('success', 'Producto creado correctamente');
    }
    
    public function edit($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $product = Producto::findOrFail($id);
        $categories = Categoria::all();
        $subcategories = Subcategoria::all();
        
        return view('admin.products.edit', compact('product', 'categories', 'subcategories'));
    }
    
    public function update(Request $request, $id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'descr' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'idCategoria' => 'required|exists:subcategoria,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $product = Producto::findOrFail($id);
        
        // Actualizar imagen si se proporciona una nueva
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
            // Subir la nueva imagen
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }
        
        // Actualizar producto
        $product->name = $request->name;
        $product->descr = $request->descr;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->idCategoria = $request->idCategoria;
        $product->save();
        
        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado correctamente');
    }
    
    public function destroy($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $product = Producto::findOrFail($id);
        
        // Comprobar si el producto está en algún pedido
        $inOrders = $product->comandas()->exists();
        
        if ($inOrders) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el producto porque está asociado a pedidos existentes'
            ]);
        }
        
        // Eliminar la imagen
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        // Eliminar el producto
        $product->delete();
        
        return response()->json(['success' => true]);
    }
    
    public function updatePrice(Request $request, $id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $request->validate([
            'price' => 'required|numeric|min:0'
        ]);
        
        $product = Producto::findOrFail($id);
        $product->price = $request->price;
        $product->save();
        
        return response()->json(['success' => true]);
    }
    
    public function updateStock(Request $request, $id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);
        
        $product = Producto::findOrFail($id);
        $product->stock = $request->stock;
        $product->save();
        
        return response()->json(['success' => true]);
    }
}