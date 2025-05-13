<?php

namespace App\Http\Controllers\Admin;

use App\Models\Producto; 
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Comanda;
use App\Models\User;
use App\Models\ComandaProd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function index()
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }


        $totalSales = ComandaProd::join('producte', 'comanda_prod.idProducte', '=', 'producte.id')
        ->selectRaw('SUM(producte.price * comanda_prod.cant) as total_sales')
        ->first()->total_sales ?? 0;

        // Pedidos totales
        $totalOrders = Comanda::count();

        // Clientes totales (usuarios que no son administradores)
        $totalCustomers = User::where('isAdmin', false)->count();

        // Productos totales
        $totalProducts = Producto::count();

        // Productos sin stock
        $productsOutOfStock = Producto::where('stock', 0)->count();

        // Cálculo del crecimiento de ventas mes actual vs mes anterior
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lastMonth = now()->subMonth()->month;
        $lastYear = now()->subMonth()->year;

        // Ventas mes actual
        $currentMonthSales = DB::table('comanda_prod')
                    ->join('producte', 'comanda_prod.idProducte', '=', 'producte.id')
                    ->join('comanda', 'comanda_prod.idComanda', '=', 'comanda.id')
                    ->whereMonth('comanda.created_at', $currentMonth)
                    ->whereYear('comanda.created_at', $currentYear)
                    ->selectRaw('SUM(producte.price * comanda_prod.cant) as total')
                    ->first()->total ?? 0;

        // Ventas mes anterior
        $lastMonthSales = DB::table('comanda_prod')
                    ->join('producte', 'comanda_prod.idProducte', '=', 'producte.id')
                    ->join('comanda', 'comanda_prod.idComanda', '=', 'comanda.id')
                    ->whereMonth('comanda.created_at', $lastMonth)
                    ->whereYear('comanda.created_at', $lastYear)
                    ->selectRaw('SUM(producte.price * comanda_prod.cant) as total')
                    ->first()->total ?? 0;

        // Calcular crecimiento de ventas
        $salesGrowth = $lastMonthSales > 0 
                ? (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100 
                : 100;

        // Crecimiento de pedidos mes actual vs mes anterior
        $currentMonthOrders = Comanda::whereMonth('created_at', $currentMonth)
                            ->whereYear('created_at', $currentYear)
                            ->count();

        $lastMonthOrders = Comanda::whereMonth('created_at', $lastMonth)
                        ->whereYear('created_at', $lastYear)
                        ->count();

        $ordersGrowth = $lastMonthOrders > 0 
                ? (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 
                : 100;

        // Crecimiento de clientes mes actual vs mes anterior
        $currentMonthCustomers = User::where('isAdmin', false)
                        ->whereMonth('created_at', $currentMonth)
                        ->whereYear('created_at', $currentYear)
                        ->count();

        $lastMonthCustomers = User::where('isAdmin', false)
                        ->whereMonth('created_at', $lastMonth)
                        ->whereYear('created_at', $lastYear)
                        ->count();

        $customersGrowth = $lastMonthCustomers > 0 
                    ? (($currentMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100 
                    : 100;

        // Datos para el gráfico de ventas mensuales (últimos 6 meses)
        $monthlySalesData = [];
        $monthlySalesLabels = [];

        for ($i = 5; $i >= 0; $i--) {
        $month = now()->subMonths($i);
        $monthlySalesLabels[] = $month->format('M Y');

        // Calcular ventas para cada mes
        $sales = DB::table('comanda_prod')
            ->join('producte', 'comanda_prod.idProducte', '=', 'producte.id')
            ->join('comanda', 'comanda_prod.idComanda', '=', 'comanda.id')
            ->whereMonth('comanda.created_at', $month->month)
            ->whereYear('comanda.created_at', $month->year)
            ->selectRaw('SUM(producte.price * comanda_prod.cant) as total')
            ->first()->total ?? 0;

        $monthlySalesData[] = $sales;
        }

        // Datos para el gráfico de productos más vendidos
        $topProducts = DB::table('comanda_prod')
                ->select('idProducte', DB::raw('SUM(cant) as total_sold'))
                ->groupBy('idProducte')
                ->orderByDesc('total_sold')
                ->take(5)
                ->get();

        $topProductsData = [];
        $topProductsLabels = [];

        foreach ($topProducts as $product) {
        $productInfo = Producto::find($product->idProducte);
        if ($productInfo) {
        $topProductsLabels[] = $productInfo->name;
        $topProductsData[] = $product->total_sold;
        }
        }

        // Últimos pedidos
        $latestOrders = Comanda::with('user')
                    ->latest()
                    ->take(10)
                    ->get();

        return view('admin.dashboard', compact(
        'totalSales', 'totalOrders', 'totalCustomers', 'totalProducts',
        'productsOutOfStock', 'salesGrowth', 'ordersGrowth', 'customersGrowth',
        'monthlySalesLabels', 'monthlySalesData', 'topProductsLabels', 'topProductsData',
        'latestOrders'
        ));
    }
    
    public function orders()
    {

        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $orders = Comanda::with('user')
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);
        
        return view('admin.orders.index', compact('orders'));
    }
    
    public function showOrder($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $order = Comanda::with(['user', 'products'])->findOrFail($id);
        
        // Calcular subtotal, impuestos y envío
        $subtotal = 0;
        foreach ($order->products as $product) {
            $subtotal += $product->pivot->price * $product->pivot->cant;
        }
        
        $tax = $subtotal * 0.21;
        $shipping = 4.99;
        $discount = $order->discount ?? 0;
        
        return view('admin.orders.show', compact('order', 'subtotal', 'tax', 'shipping', 'discount'));
    }
    
    public function updateOrderStatus(Request $request, $id)
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
    
    public function users()
    {

        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }
    
    public function showUser($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $user = User::with('comandas')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }
    
    public function updateUser(Request $request, $id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'idTipoUser' => 'required|exists:tipo_users,id'
        ]);
        
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->idTipoUser = $request->idTipoUser;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();
        
        return redirect()->back()->with('success', 'Usuario actualizado correctamente');
    }
}