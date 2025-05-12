<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    public function index()
    {
        $categories = Categoria::paginate(20);
        return view('admin.categories.index', compact('categories'));
    }
    
    public function create()
    {
        return view('admin.categories.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categorias',
            'descr' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $category = new Categoria();
        $category->name = $request->name;
        $category->descr = $request->descr;
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $category->image = $imagePath;
        }
        
        $category->save();
        
        return redirect()->route('admin.categories.index')->with('success', 'Categoría creada correctamente');
    }
    
    public function edit($id)
    {
        $category = Categoria::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categorias,name,' . $id,
            'descr' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $category = Categoria::findOrFail($id);
        $category->name = $request->name;
        $category->descr = $request->descr;
        
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            
            $imagePath = $request->file('image')->store('categories', 'public');
            $category->image = $imagePath;
        }
        
        $category->save();
        
        return redirect()->route('admin.categories.index')->with('success', 'Categoría actualizada correctamente');
    }
    
    public function destroy($id)
    {
        $category = Categoria::findOrFail($id);
        
        // Comprobar si la categoría tiene subcategorías
        if ($category->subcategorias()->exists()) {
            return redirect()->route('admin.categories.index')->with('error', 'No se puede eliminar la categoría porque tiene subcategorías asociadas');
        }
        
        // Eliminar la imagen si existe
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')->with('success', 'Categoría eliminada correctamente');
    }
}