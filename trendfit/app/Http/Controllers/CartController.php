<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();
        
        // Calcular el precio total
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item['product']->price * $item['quantity'];
        }
        
        return view('pages.cart', compact('cartItems', 'totalPrice'));
    }
    
    public function add(Request $request)
    {
        $request->validate([
            'productId' => 'required|integer|exists:productes,id',
            'quantity' => 'nullable|integer|min:1', // Cambiar a nullable
            'size' => 'nullable|string',
        ]);
        
        $productId = $request->productId;
        $quantity = $request->quantity ?? 1; // Establecer valor por defecto si es null
        $size = $request->size ?? null;
        
        // Comprobar si el producto existe y tiene suficiente stock
        $product = Producto::findOrFail($productId);
        
        if ($product->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'No hay suficiente stock disponible'
            ]);
        }
        
        // Obtener el carrito actual
        $cart = session()->get('cart', []);
        
        // Generar un ID único para este ítem del carrito (producto + talla)
        $cartItemId = $productId . '_' . ($size ?? 'default');
        
        // Comprobar si el producto ya está en el carrito
        if (isset($cart[$cartItemId])) {
            // Actualizar la cantidad
            $cart[$cartItemId]['quantity'] += $quantity;
            
            // Comprobar que no exceda el stock disponible
            if ($cart[$cartItemId]['quantity'] > $product->stock) {
                $cart[$cartItemId]['quantity'] = $product->stock;
            }
        } else {
            // Añadir nuevo ítem al carrito
            $cart[$cartItemId] = [
                'productId' => $productId,
                'quantity' => $quantity,
                'size' => $size,
            ];
        }
        
        // Guardar el carrito en la sesión
        session()->put('cart', $cart);
        
        // Contar el número total de ítems en el carrito
        $totalItems = 0;
        foreach ($cart as $item) {
            $totalItems += $item['quantity'];
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Producto añadido al carrito',
            'totalItems' => $totalItems
        ]);
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'productId' => 'required|integer|exists:productes,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string',
        ]);
        
        $productId = $request->productId;
        $quantity = $request->quantity;
        $size = $request->size ?? null;
        
        // Obtener el producto para verificar stock
        $product = Producto::findOrFail($productId);
        
        if ($product->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'No hay suficiente stock disponible'
            ]);
        }
        
        // Obtener el carrito actual
        $cart = session()->get('cart', []);
        
        // Generar ID del ítem del carrito
        $cartItemId = $productId . '_' . ($size ?? 'default');
        
        // Actualizar la cantidad si el ítem existe
        if (isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] = $quantity;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'El producto no está en el carrito'
            ]);
        }
        
        // Guardar el carrito actualizado
        session()->put('cart', $cart);
        
        // Calcular el subtotal de este producto
        $subtotal = $product->price * $quantity;
        
        // Calcular el total del carrito
        $total = 0;
        foreach ($cart as $item) {
            $itemProduct = Producto::find($item['productId']);
            $total += $itemProduct->price * $item['quantity'];
        }
        
        // Total con IVA
        $totalWithTax = $total * 1.21;
        
        // Contar el número total de ítems
        $totalItems = 0;
        foreach ($cart as $item) {
            $totalItems += $item['quantity'];
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Carrito actualizado',
            'subtotal' => $subtotal,
            'total' => $totalWithTax,
            'totalItems' => $totalItems
        ]);
    }
    
    public function remove(Request $request)
    {
        $request->validate([
            'productId' => 'required|integer|exists:productes,id',
            'size' => 'nullable|string',
        ]);
        
        $productId = $request->productId;
        $size = $request->size ?? null;
        
        // Obtener el carrito actual
        $cart = session()->get('cart', []);
        
        // Generar ID del ítem del carrito
        $cartItemId = $productId . '_' . ($size ?? 'default');
        
        // Eliminar el ítem si existe
        if (isset($cart[$cartItemId])) {
            unset($cart[$cartItemId]);
            session()->put('cart', $cart);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'El producto no está en el carrito'
            ]);
        }
        
        // Calcular el total del carrito
        $total = 0;
        foreach ($cart as $item) {
            $product = Producto::find($item['productId']);
            $total += $product->price * $item['quantity'];
        }
        
        // Total con IVA
        $totalWithTax = $total * 1.21;
        
        // Contar el número total de ítems
        $totalItems = 0;
        foreach ($cart as $item) {
            $totalItems += $item['quantity'];
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado del carrito',
            'total' => $totalWithTax,
            'totalItems' => $totalItems
        ]);
    }
    
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'couponCode' => 'required|string',
        ]);
        
        $couponCode = $request->couponCode;
        
        // Aquí deberías validar el cupón en la base de datos
        // y calcular el descuento correspondiente
        // Este es solo un ejemplo
        
        $validCoupons = [
            'WELCOME10' => 10, // 10% de descuento
            'SUMMER20' => 20,  // 20% de descuento
        ];
        
        if (!isset($validCoupons[$couponCode])) {
            return response()->json([
                'success' => false,
                'message' => 'Cupón inválido'
            ]);
        }
        
        $discountPercentage = $validCoupons[$couponCode];
        
        // Guardar el cupón en la sesión
        session()->put('coupon', [
            'code' => $couponCode,
            'discount' => $discountPercentage
        ]);
        
        // Obtener el carrito y calcular el nuevo total
        $cartItems = $this->getCartItems();
        
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['product']->price * $item['quantity'];
        }
        
        $discount = ($subtotal * $discountPercentage) / 100;
        $total = ($subtotal - $discount) * 1.21; // Aplicar IVA después del descuento
        
        return response()->json([
            'success' => true,
            'message' => "Cupón aplicado: {$discountPercentage}% de descuento",
            'discount' => $discount,
            'total' => $total
        ]);
    }
    
    public function count()
    {
        $cart = session()->get('cart', []);
        
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        
        return response()->json([
            'count' => $count
        ]);
    }
    
    private function getCartItems()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        
        foreach ($cart as $item) {
            $product = Producto::find($item['productId']);
            
            if ($product && $product->stock > 0) {
                // Ajustar la cantidad si es mayor que el stock disponible
                if ($item['quantity'] > $product->stock) {
                    $item['quantity'] = $product->stock;
                    
                    // Actualizar el carrito en la sesión
                    $cartItemId = $item['productId'] . '_' . ($item['size'] ?? 'default');
                    $cart[$cartItemId]['quantity'] = $product->stock;
                    session()->put('cart', $cart);
                }
                
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'size' => $item['size'],
                ];
            }
        }
        
        return $cartItems;
    }
}