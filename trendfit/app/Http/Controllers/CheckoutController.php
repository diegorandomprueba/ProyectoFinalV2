<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        // Recuperar los items del carrito desde la sesión o localStorage
        // Esto dependerá de tu implementación específica
        
        $cartItems = [];
        $subtotal = 0;
        $tax = 0;
        $shipping = 4.99;
        $discount = 0;
        $total = 0;
        
        // Aquí necesitamos acceder a la información del carrito
        // Como el carrito está implementado en JavaScript y se almacena en localStorage,
        // necesitamos pasar estos datos desde el cliente al servidor
        
        // La forma más sencilla es almacenar en la sesión cuando el usuario hace clic en "Checkout"
        if (session()->has('cart_items')) {
            $cartItemsData = session('cart_items');
            
            // Recuperar los productos de la base de datos para asegurar precios correctos
            foreach ($cartItemsData as $item) {
                $product = \App\Models\Producto::find($item['id']);
                
                if ($product) {
                    $cartItems[] = [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'size' => $item['size'] ?? null
                    ];
                    
                    $subtotal += $product->price * $item['quantity'];
                }
            }
            
            $tax = $subtotal * 0.21;
            $total = $subtotal + $tax + $shipping - $discount;
        }
        
        return view('checkout', compact('cartItems', 'subtotal', 'tax', 'shipping', 'discount', 'total'));
    }
    
    public function process(Request $request)
    {
        // Validación de datos
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:10',
            'payment_method' => 'required|in:card,paypal,transfer',
        ]);
        
        // Si es pago con tarjeta, validar los datos de la tarjeta
        if ($request->payment_method === 'card') {
            $request->validate([
                'card_number' => 'required|string',
                'card_name' => 'required|string',
                'card_expiry' => 'required|string',
                'card_cvv' => 'required|string',
            ]);
        }
        
        // Crear un nuevo pedido
        $order = new \App\Models\Comanda();
        $order->idUsuari = Auth::id();
        $order->name = $request->name;
        $order->address = $request->address;
        $order->city = $request->city;
        $order->provincia = $request->provincia;
        $order->codigo_postal = $request->codigo_postal;
        $order->status = 'pending';
        $order->payment_method = $request->payment_method;
        
        // Si es pago con tarjeta, guardar los últimos 4 dígitos
        if ($request->payment_method === 'card') {
            $order->card_number = substr($request->card_number, -4);
        }
        
        $order->save();
        
        // Obtener los items del carrito y guardarlos en la tabla pivote
        if (session()->has('cart_items')) {
            $cartItems = session('cart_items');
            
            foreach ($cartItems as $item) {
                $product = \App\Models\Producto::find($item['id']);
                
                if ($product) {
                    // Reducir el stock
                    $product->stock -= $item['quantity'];
                    $product->save();
                    
                    // Añadir al pedido
                    $order->productes()->attach($product->id, [
                        'cant' => $item['quantity'],
                        'has_to_comment' => true
                    ]);
                }
            }
        }
        
        // Limpiar el carrito
        session()->forget('cart_items');
        
        return redirect()->route('checkout.success', ['id' => $order->id]);
    }
    
    public function success($id)
    {
        $order = \App\Models\Comanda::with(['user', 'productes'])->findOrFail($id);
        
        // Verificar que el usuario autenticado es el propietario del pedido
        if (Auth::id() !== $order->idUsuari) {
            return redirect()->route('home')->with('error', 'No tienes permiso para ver este pedido');
        }
        
        return view('success', compact('order'));
    }
}