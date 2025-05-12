<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Services\OpinionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    protected $opinionService;
    
    public function __construct(OpinionService $opinionService)
    {
        $this->opinionService = $opinionService;
    }
    
    public function index()
    {
        // Obtener productos destacados
        $featuredProducts = Producto::where('stock', '>', 0)
                                    ->orderBy('id', 'desc')
                                    ->take(8)
                                    ->get();
                                    
        // Obtener categorías populares
        $popularCategories = Categoria::take(3)->get();
        
        // Obtener productos mejor valorados
        $topRated = $this->opinionService->getTopRatedProducts(10);
        $topRatedProducts = [];
        
        // Si hay productos mejor valorados, obtener sus detalles
        if (!empty($topRated)) {
            $productIds = array_column($topRated, 'productId');
            $products = Producto::whereIn('id', $productIds)->get();
            
            // Combinar los datos de valoración con los productos
            foreach ($topRated as $ratedProduct) {
                $product = $products->firstWhere('id', $ratedProduct['productId']);
                if ($product) {
                    $product->weightedRating = $ratedProduct['weightedRating'];
                    $product->numRatings = $ratedProduct['numRatings'];
                    $topRatedProducts[] = $product;
                }
            }
        }
        
        return view('pages.home', compact('featuredProducts', 'popularCategories', 'topRatedProducts'));
    }
    
    public function about()
    {
        return view('pages.about');
    }
    
    public function where()
    {
        return view('pages.where');
    }
    
    public function contact()
    {
        return view('pages.contact');
    }
    
    public function editProfile()
    {
        $user = auth()->user();
        return view('profile.profile', compact('user'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('pages.profile-view', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ];
        
        // Si está enviando una nueva contraseña, valida la contraseña actual
        if ($request->filled('password')) {
            $rules['current_password'] = 'required';
        }
        
        $validated = $request->validate($rules);
        
        // Validar manualmente la contraseña actual si está intentando cambiarla
        if ($request->filled('password') && !Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'La contraseña actual no es correcta'])
                ->withInput($request->except('password', 'password_confirmation'));
        }
        
        // Actualizar información básica
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        // Actualizar contraseña si se proporciona
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();
        
        return redirect()->route('profile')->with('success', 'Perfil actualizado correctamente');
    }
    
    public function orders()
    {
        $orders = auth()->user()->comandes()->latest()->paginate(10);
        return view('pages.orders.index', compact('orders'));
    }
    
    public function showOrder($id)
    {
        $order = auth()->user()->comandes()->findOrFail($id);
        
        // Calcular subtotal, impuestos y envío
        $subtotal = 0;
        foreach ($order->products as $product) {
            $subtotal += $product->price * $product->pivot->cant;
        }
        
        $tax = $subtotal * 0.21;
        $shipping = 4.99;
        $discount = 0;
        
        // Verificar si todos los productos han sido revisados
        $allProductsReviewed = true;
        foreach ($order->products as $product) {
            if (!$product->pivot->has_comment) {
                $allProductsReviewed = false;
                break;
            }
        }
        
        return view('pages.orders.show', compact('order', 'subtotal', 'tax', 'shipping', 'discount', 'allProductsReviewed'));
    }
    
    public function terms()
    {
        return view('pages.legal.terms');
    }
    
    public function cookies()
    {
        return view('pages.legal.cookies');
    }
    
    public function privacy()
    {
        return view('pages.legal.privacy');
    }

    public function destroyProfile(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = auth()->user();
        
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'La contraseña proporcionada no es correcta.',
            ]);
        }

        Auth::logout();
        
        $user->delete();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Tu cuenta ha sido eliminada correctamente');
    }
}