<?php

namespace App\Http\Controllers;

use App\Models\Comanda;
use App\Models\ComandaProd;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use PDF;

class CheckoutController extends Controller
{
    
    public function index()
    {
        // Obtener los items del carrito
        $cartItems = $this->getCartItems();
        
        // Si el carrito está vacío, redirigir al carrito
        if (count($cartItems) === 0) {
            return redirect()->route('cart')->with('error', 'Tu carrito está vacío');
        }
        
        // Calcular totales
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['product']->price * $item['quantity'];
        }
        
        // Aplicar descuento si hay un cupón
        $discount = 0;
        if (session()->has('coupon')) {
            $coupon = session()->get('coupon');
            $discount = ($subtotal * $coupon['discount']) / 100;
        }
        
        $tax = ($subtotal - $discount) * 0.21; // IVA del 21%
        $shipping = 4.99; // Costo de envío fijo
        $total = ($subtotal - $discount) + $tax + $shipping;
        
        return view('pages.checkout', compact('cartItems', 'subtotal', 'discount', 'tax', 'shipping', 'total'));
    }
    
    public function process(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_code' => 'required|string|max:10',
            'shipping_province' => 'required|string|max:255',
            'payment_method' => 'required|in:card,paypal,transfer',
            'card_number' => 'required_if:payment_method,card|nullable|string',
            'card_name' => 'required_if:payment_method,card|nullable|string',
            'card_expiry' => 'required_if:payment_method,card|nullable|string',
            'card_cvv' => 'required_if:payment_method,card|nullable|string',
        ]);
        
        // Obtener los items del carrito
        $cartItems = $this->getCartItems();
        
        // Si el carrito está vacío, redirigir al carrito
        if (count($cartItems) === 0) {
            return redirect()->route('cart')->with('error', 'Tu carrito está vacío');
        }
        
        // Calcular totales
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['product']->price * $item['quantity'];
        }
        
        // Aplicar descuento si hay un cupón
        $discount = 0;
        if (session()->has('coupon')) {
            $coupon = session()->get('coupon');
            $discount = ($subtotal * $coupon['discount']) / 100;
        }
        
        $tax = ($subtotal - $discount) * 0.21; // IVA del 21%
        $shipping = 4.99; // Costo de envío fijo
        $total = ($subtotal - $discount) + $tax + $shipping;
        
        // Iniciar transacción para garantizar consistencia en la BD
        DB::beginTransaction();
        
        try {
            // Crear el pedido
            $order = new Comanda();
            $order->idUsuari = auth()->id();
            $order->name = $validated['shipping_name'];
            $order->address = $validated['shipping_address'];
            $order->city = $validated['shipping_city'];
            $order->codigo_postal = $validated['shipping_code'];
            $order->provincia = $validated['shipping_province'];
            $order->phone = $validated['shipping_phone'];
            $order->payment_method = $validated['payment_method'];
            $order->status = 'pending';
            $order->total = $total;
            $order->subtotal = $subtotal;
            $order->tax = $tax;
            $order->shipping = $shipping;
            $order->discount = $discount;
            
            // Si el método de pago es tarjeta, guardamos los últimos 4 dígitos
            if ($validated['payment_method'] === 'card') {
                $order->card_number = substr(preg_replace('/\s+/', '', $validated['card_number']), -4);
            }
            
            $order->date = now();
            $order->save();
            
            // Guardar los productos del pedido
            foreach ($cartItems as $item) {
                $orderItem = new ComandaProd();
                $orderItem->idComanda = $order->id;
                $orderItem->idProducte = $item['product']->id;
                $orderItem->cant = $item['quantity'];
                $orderItem->price = $item['product']->price;
                $orderItem->size = $item['size'];
                $orderItem->has_to_comment = false; // Inicialmente no tiene valoración
                $orderItem->save();
                
                // Actualizar el stock del producto
                $product = Producto::find($item['product']->id);
                $product->stock -= $item['quantity'];
                $product->save();
            }
            
            // Generar la factura en PDF
            $pdf = PDF::loadView('pages.orders.invoice', [
                'order' => $order,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'discount' => $discount,
                'total' => $total
            ]);
            
            // Guardar la factura
            $invoicePath = 'invoices/' . $order->id . '.pdf';
            Storage::put('public/' . $invoicePath, $pdf->output());
            
            // Actualizar el pedido con la ruta de la factura
            $order->invoice_path = $invoicePath;
            $order->save();
            
            // Enviar correo de confirmación
            Mail::to(auth()->user()->email)->send(new OrderConfirmation($order));
            
            // Confirmar la transacción
            DB::commit();
            
            // Limpiar el carrito y el cupón
            session()->forget(['cart', 'coupon']);
            
            // Redirigir a la página de confirmación
            return redirect()->route('checkout.success', ['order' => $order->id]);
            
        } catch (\Exception $e) {
            // Si hay algún error, revertir la transacción
            DB::rollBack();
            
            return redirect()->back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }
    
    public function success($orderId)
    {
        $order = Comanda::where('id', $orderId)
                       ->where('idUsuari', auth()->id())
                       ->firstOrFail();
        
        // Calcular subtotal, impuestos y envío
        $subtotal = 0;
        foreach ($order->products as $product) {
            $subtotal += $product->price * $product->pivot->cant;
        }
        
        $tax = $subtotal * 0.21;
        $shipping = 4.99;
        $discount = $order->discount ?? 0;
        
        return view('pages.checkout.success', compact('order', 'subtotal', 'tax', 'shipping', 'discount'));
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