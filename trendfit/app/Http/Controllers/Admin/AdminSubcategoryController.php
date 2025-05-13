<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subcategoria;
use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class AdminSubcategoryController extends Controller
{
    
    public function index(Request $request)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }
    
        $query = Subcategoria::with('categoria');
        
        // Aplicar filtros
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('idCategoria', $request->category_id);
        }
        
        $subcategories = $query->paginate(20);
        $categories = Categoria::all(); // Añadir esta línea
        
        return view('admin.subcategories.index', compact('subcategories', 'categories'));
    }
    
    public function create()
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $categories = Categoria::all();
        return view('admin.subcategories.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'descr' => 'nullable|string',
            'idCategoria' => 'required|exists:categoria,id',
        ]);
        
        $subcategory = new Subcategoria();
        $subcategory->name = $request->name;
        $subcategory->descr = $request->descr;
        $subcategory->idCategoria = $request->idCategoria;
        $subcategory->save();
        
        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategoría creada correctamente');
    }
    
    public function edit($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $subcategory = Subcategoria::findOrFail($id);
        $categories = Categoria::all();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }
    
    public function update(Request $request, $id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'descr' => 'nullable|string',
            'idCategoria' => 'required|exists:categoria,id',
        ]);
        
        $subcategory = Subcategoria::findOrFail($id);
        $subcategory->name = $request->name;
        $subcategory->descr = $request->descr;
        $subcategory->idCategoria = $request->idCategoria;
        $subcategory->save();
        
        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategoría actualizada correctamente');
    }
    
    public function destroy($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $subcategory = Subcategoria::findOrFail($id);
        
        // Comprobar si hay productos asociados a esta subcategoría
        if ($subcategory->productes()->exists()) {
            return redirect()->route('admin.subcategories.index')->with('error', 'No se puede eliminar la subcategoría porque tiene productos asociados');
        }
        
        $subcategory->delete();
        
        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategoría eliminada correctamente');
    }
    
    public function getSubcategoriesByCategory($categoryId)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $subcategories = Subcategoria::where('idCategoria', $categoryId)->get();
        return response()->json($subcategories);
    }
}