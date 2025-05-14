<?php

namespace App\Http\Controllers;

use App\Models\Comanda;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para finalizar la compra.');
        }

        // Recuperar los items del carrito desde la sesión
        $cartItems = [];
        $subtotal = 0;
        $tax = 0;
        $shipping = 4.99;
        $discount = 0;
        $total = 0;
        
        if (session()->has('cart_items')) {
            $cartItemsData = session('cart_items');
            
            // Recuperar los productos de la base de datos para asegurar precios correctos
            foreach ($cartItemsData as $item) {
                $product = Producto::find($item['id']);
                
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
        
        return view('pages.checkout', compact('cartItems', 'subtotal', 'tax', 'shipping', 'discount', 'total'));
    }
    
    // En el método process de CheckoutController.php
    public function process(Request $request)
    {
        \Log::info('Datos del formulario: ' . json_encode($request->all()));
        
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para finalizar la compra.');
        }

        // Validar el formulario
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|regex:/^[0-9]{9,10}$/',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_code' => 'required|string|regex:/^[0-9]{5}$/',
            'shipping_province' => 'required|string|max:255',
            'payment_method' => 'required|in:card,paypal,transfer',
        ]);
        
        // Si es pago con tarjeta, validar los datos pero NO los guardamos
        if ($request->payment_method === 'card') {
            $request->validate([
                'card_number' => 'required|string|regex:/^[0-9]{16}$/',
                'card_name' => 'required|string|max:255',
                'card_expiry' => [
                    'required',
                    'string',
                    'regex:/^(0[1-9]|1[0-2])\/[0-9]{2}$/'
                ],                
                'card_cvv' => 'required|string|regex:/^[0-9]{3,4}$/',
            ]);
        }
        
        try {
            // Crear un nuevo pedido
            $order = new Comanda();
            $order->idUsuari = Auth::id();
            $order->name = $request->shipping_name;
            $order->address = $request->shipping_address;
            $order->city = $request->shipping_city;
            $order->codigo_postal = $request->shipping_code;
            $order->provincia = $request->shipping_province;
            $order->phone = $request->shipping_phone;
            $order->status = 'pending';
            $order->payment_method = $request->payment_method;
            $order->date = now(); // Asegurarse de que la fecha se establece
            
            // Calcular el total del pedido
            $subtotal = 0;
            $cartItems = session('cart_items', []);
            
            foreach ($cartItems as $item) {
                $product = Producto::find($item['id']);
                
                if ($product) {
                    $subtotal += $product->price * $item['quantity'];
                }
            }
            
            $tax = $subtotal * 0.21;
            $shipping = 4.99;
            $discount = 0;
            $total = $subtotal + $tax + $shipping - $discount;
            
            $order->total = $total;
            $order->save();
            
            // Añadir los productos al pedido
            foreach ($cartItems as $item) {
                $product = Producto::find($item['id']);
                
                if ($product) {
                    // Verificar si hay suficiente stock
                    if ($product->stock < $item['quantity']) {
                        return redirect()->back()->with('error', "No hay suficiente stock del producto {$product->name}. Stock disponible: {$product->stock}");
                    }
                    
                    // Reducir el stock
                    $product->stock -= $item['quantity'];
                    $product->save();
                    
                    // Añadir al pedido
                    $order->productes()->attach($product->id, [
                        'cant' => $item['quantity'],
                        'has_to_comment' => true,
                        'size' => $item['size'] ?? null
                    ]);
                }
            }
            
            // Limpiar el carrito
            session()->forget('cart_items');
            
            // Redireccionar a la página de éxito
            return redirect()->route('checkout.success', ['id' => $order->id]);
            
        } catch (\Exception $e) {
            // Registrar el error
            \Log::error('Error al procesar el pedido: ' . $e->getMessage());
            
            // Redireccionar con mensaje de error
            return redirect()->back()->with('error', 'Ha ocurrido un error al procesar tu pedido. Por favor, inténtalo de nuevo.');
        }
    }
    
    public function success($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Comanda::with(['user', 'productes'])->findOrFail($id);
        
        // Verificar que el usuario autenticado es el propietario del pedido
        if (Auth::id() !== $order->idUsuari) {
            return redirect()->route('home')->with('error', 'No tienes permiso para ver este pedido');
        }
        
        return view('pages.success', compact('order'));
    }
}