<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TipoUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Verificar si hay productos pendientes de valoración
            $user = Auth::user();
            
            // Consulta corregida: comandas → comanda
            $pendingReviews = \DB::table('comanda_prod')
                                ->join('comanda', 'comanda_prod.idComanda', '=', 'comanda.id')
                                ->where('comanda.idUsuari', $user->id)
                                ->where('comanda_prod.has_to_comment', true)
                                ->exists();
            
            if ($pendingReviews) {
                session()->flash('review_reminder', true);
            }
            
            // Redirigir según el tipo de usuario
            if ($user->isAdmin) {
                return redirect()->intended(route('admin.dashboard'));
            } else {
                return redirect()->intended(route('home'));
            }
        }
        
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->withInput($request->only('email', 'remember'));
    }
    
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->isAdmin = false; // Cambiado a false para que los nuevos usuarios no sean administradores por defecto
        $user->save();
        
        Auth::login($user);
        
        return redirect()->route('home');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home');
    }
}