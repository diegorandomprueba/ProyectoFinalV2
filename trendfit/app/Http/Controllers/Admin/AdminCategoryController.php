<?php

namespace App\Http\Controllers\Admin;

use App\Models\Categoria;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminCategoryController extends Controller
{
    
    public function index()
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $categories = Categoria::paginate(20);
        return view('admin.categories.index', compact('categories'));
    }
    
    public function create()
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        return view('admin.categories.create');
    }
    
    public function store(Request $request)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

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
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $category = Categoria::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }
    
    public function update(Request $request, $id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

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
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

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