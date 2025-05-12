<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Comanda;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    public function index()
    {
        // Estadísticas para el dashboard
        
        // Ventas totales
        $totalSales = Comanda::sum('total');
        
        // Pedidos totales
        $totalOrders = Comanda::count();
        
        // Clientes totales
        $totalCustomers = User::where('idTipoUser', 2)->count(); // Tipo 2 = cliente
        
        // Productos totales
        $totalProducts = Producto::count();
        
        // Productos sin stock
        $productsOutOfStock = Producto::where('stock', 0)->count();
        
        // Crecimiento de ventas respecto al mes anterior
        $currentMonthSales = Comanda::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->sum('total');
        
        $lastMonthSales = Comanda::whereMonth('created_at', now()->subMonth()->month)
                                ->whereYear('created_at', now()->subMonth()->year)
                                ->sum('total');
        
        $salesGrowth = $lastMonthSales > 0 
                        ? (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100 
                        : 100;
        
        // Crecimiento de pedidos respecto al mes anterior
        $currentMonthOrders = Comanda::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count();
        
        $lastMonthOrders = Comanda::whereMonth('created_at', now()->subMonth()->month)
                                ->whereYear('created_at', now()->subMonth()->year)
                                ->count();
        
        $ordersGrowth = $lastMonthOrders > 0 
                        ? (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 
                        : 100;
        
        // Crecimiento de clientes respecto al mes anterior
        $currentMonthCustomers = User::where('idTipoUser', 2)
                                    ->whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count();
        
        $lastMonthCustomers = User::where('idTipoUser', 2)
                                ->whereMonth('created_at', now()->subMonth()->month)
                                ->whereYear('created_at', now()->subMonth()->year)
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
            
            $sales = Comanda::whereMonth('created_at', $month->month)
                           ->whereYear('created_at', $month->year)
                           ->sum('total');
            
            $monthlySalesData[] = $sales;
        }
        
        // Datos para el gráfico de productos más vendidos
        $topProducts = Comanda::join('comanda_prods', 'comandas.id', '=', 'comanda_prods.idComanda')
                             ->select('comanda_prods.idProducte', \DB::raw('SUM(comanda_prods.cant) as total_sold'))
                             ->groupBy('comanda_prods.idProducte')
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
            'monthlySalesData', 'monthlySalesLabels', 'topProductsData', 'topProductsLabels',
            'latestOrders'
        ));
    }
    
    public function orders()
    {
        $orders = Comanda::with('user')
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);
        
        return view('admin.orders.index', compact('orders'));
    }
    
    public function showOrder($id)
    {
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
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }
    
    public function showUser($id)
    {
        $user = User::with('comandas')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }
    
    public function updateUser(Request $request, $id)
    {
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