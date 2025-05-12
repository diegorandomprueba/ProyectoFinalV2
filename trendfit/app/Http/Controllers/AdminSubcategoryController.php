<?php

namespace App\Http\Controllers;

use App\Models\Subcategoria;
use App\Models\Categoria;
use Illuminate\Http\Request;

class AdminSubcategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    public function index()
    {
        $subcategories = Subcategoria::with('categoria')->paginate(20);
        return view('admin.subcategories.index', compact('subcategories'));
    }
    
    public function create()
    {
        $categories = Categoria::all();
        return view('admin.subcategories.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'descr' => 'nullable|string',
            'idCategoria' => 'required|exists:categorias,id',
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
        $subcategory = Subcategoria::findOrFail($id);
        $categories = Categoria::all();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'descr' => 'nullable|string',
            'idCategoria' => 'required|exists:categorias,id',
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
        $subcategory = Subcategoria::findOrFail($id);
        
        // Comprobar si hay productos asociados a esta subcategoría
        if ($subcategory->products()->exists()) {
            return redirect()->route('admin.subcategories.index')->with('error', 'No se puede eliminar la subcategoría porque tiene productos asociados');
        }
        
        $subcategory->delete();
        
        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategoría eliminada correctamente');
    }
    
    public function getSubcategoriesByCategory($categoryId)
    {
        $subcategories = Subcategoria::where('idCategoria', $categoryId)->get();
        return response()->json($subcategories);
    }
}