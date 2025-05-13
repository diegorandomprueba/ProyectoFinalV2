<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comanda;
use App\Models\User;
use App\Models\Producto;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;


class AdminOrderController extends Controller
{
    
    public function index()
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $orders = Comanda::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }
    
    public function show($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $order = Comanda::with(['user', 'productes'])->findOrFail($id);
        
        // Calcular subtotal, impuestos y envío
        $subtotal = 0;
        foreach ($order->productes as $product) {
            $subtotal += $product->price * $product->pivot->cant;
        }
        
        $tax = $subtotal * 0.21;
        $shipping = 4.99;
        $discount = $order->discount ?? 0;
        $total = $subtotal + $tax + $shipping - $discount;
        
        return view('admin.orders.show', compact('order', 'subtotal', 'tax', 'shipping', 'discount', 'total'));
    }
    
    public function edit($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }
    
        $order = Comanda::with(['user', 'productes'])->findOrFail($id);
        $users = User::all();
        $products = Producto::where('stock', '>', 0)->get();
        
        // Calcular subtotal, impuestos y envío
        $subtotal = 0;
        foreach ($order->productes as $product) {
            $subtotal += $product->price * $product->pivot->cant;
        }
        
        $tax = $subtotal * 0.21;
        $shipping = 4.99;
        $discount = $order->discount ?? 0;
        $total = $subtotal + $tax + $shipping - $discount;
        
        return view('admin.orders.edit', compact('order', 'users', 'products', 'subtotal', 'tax', 'shipping', 'discount', 'total'));
    }
    
    public function update(Request $request, $id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $order = Comanda::findOrFail($id);
        
        $validated = $request->validate([
            'idUsuari' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:10',
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);
        
        $order->update($validated);
        
        return redirect()->route('admin.orders.index')->with('success', 'Pedido actualizado correctamente');
    }
    
    public function updateStatus(Request $request, $id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);
        
        $order = Comanda::findOrFail($id);
        $order->status = $request->status;
        $order->save();
        
        return redirect()->back()->with('success', 'Estado del pedido actualizado correctamente');
    }
    
    public function destroy($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $order = Comanda::findOrFail($id);
        
        // Primero eliminar las relaciones en la tabla pivote
        $order->productes()->detach();
        
        // Luego eliminar el pedido
        $order->delete();
        
        return redirect()->route('admin.orders.index')->with('success', 'Pedido eliminado correctamente');
    }
    
    public function generateInvoice($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $order = Comanda::with(['user', 'productes'])->findOrFail($id);
        
        // Calcular subtotal, impuestos y envío
        $subtotal = 0;
        foreach ($order->productes as $product) {
            $subtotal += $product->price * $product->pivot->cant;
        }
        
        $tax = $subtotal * 0.21;
        $shipping = 4.99;
        $discount = $order->discount ?? 0;
        $total = $subtotal + $tax + $shipping - $discount;
        
        $pdf = PDF::loadView('admin.orders.invoice', compact('order', 'subtotal', 'tax', 'shipping', 'discount', 'total'));
        
        return $pdf->download('factura-' . $order->id . '.pdf');
    }
}