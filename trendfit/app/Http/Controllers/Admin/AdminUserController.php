<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;



class AdminUserController extends Controller
{
    
    public function index(Request $request)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }
    
        $query = User::query();
        
        // Aplicar filtro de búsqueda
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Aplicar filtro de rol
        if ($request->has('role') && !empty($request->role)) {
            if ($request->role === 'admin') {
                $query->where('isAdmin', true);
            } elseif ($request->role === 'client') {
                $query->where('isAdmin', false);
            }
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }
    
    public function create()
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        return view('admin.users.create');
    }
    
    public function store(Request $request)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'isAdmin' => 'boolean',
        ]);
        
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->isAdmin = $request->has('isAdmin') ? true : false;
        $user->save();
        
        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente');
    }
    
    public function show($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $user = User::with('comandes')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }
    
    public function edit($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    
    public function update(Request $request, $id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'isAdmin' => 'boolean',
        ]);
        
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->isAdmin = $request->has('isAdmin') ? true : false;
        
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8',
            ]);
            
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente');
    }
    
    public function destroy($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $user = User::findOrFail($id);
        
        // Verificar si el usuario tiene pedidos asociados
        if ($user->comandes()->exists()) {
            return redirect()->route('admin.users.index')->with('error', 'No se puede eliminar el usuario porque tiene pedidos asociados');
        }
        
        // Verificar que no sea el último administrador
        $adminCount = User::where('isAdmin', true)->count();
        if ($user->isAdmin && $adminCount <= 1) {
            return redirect()->route('admin.users.index')->with('error', 'No se puede eliminar el último administrador del sistema');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente');
    }
}