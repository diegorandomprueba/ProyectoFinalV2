<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Factura #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.5;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #ff6600;
            margin-bottom: 10px;
        }
        .invoice-title {
            font-size: 20px;
            margin-bottom: 5px;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        .invoice-details div {
            margin-bottom: 5px;
        }
        .addresses {
            display: flex;
            margin-bottom: 30px;
        }
        .address {
            flex: 1;
        }
        .address h3 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f5f5f5;
        }
        .totals {
            float: right;
            width: 300px;
        }
        .totals div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .totals .total {
            font-weight: bold;
            border-top: 1px solid #ddd;
            padding-top: 5px;
            margin-top: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Trendfit</div>
            <div class="invoice-title">FACTURA</div>
            <div>Nº: {{ $order->id }}</div>
        </div>
        
        <div class="invoice-details">
            <div><strong>Fecha:</strong> {{ $order->date }}</div>
            <div><strong>Cliente:</strong> {{ $order->user ? $order->user->name : 'Cliente no disponible' }}</div>
            <div><strong>Email:</strong> {{ $order->user ? $order->user->email : 'Email no disponible' }}</div>
        </div>
        
        <div class="addresses">
            <div class="address">
                <h3>Dirección de Envío</h3>
                <div>{{ $order->name }}</div>
                <div>{{ $order->address }}</div>
                <div>{{ $order->city }}, {{ $order->codigo_postal }}</div>
                <div>{{ $order->provincia }}</div>
            </div>
            
            <div class="address">
                <h3>Datos del Vendedor</h3>
                <div>Trendfit S.L.</div>
                <div>Carrer de l'Exiample, 123</div>
                <div>08800 Vilanova i la Geltrú</div>
                <div>Barcelona, España</div>
                <div>CIF: B-12345678</div>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->productes as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->pivot->cant }}</td>
                        <td>{{ number_format($product->price, 2) }}€</td>
                        <td>{{ number_format($product->price * $product->pivot->cant, 2) }}€</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="totals">
            <div>
                <span>Subtotal:</span>
                <span>{{ number_format($subtotal, 2) }}€</span>
            </div>
            <div>
                <span>IVA (21%):</span>
                <span>{{ number_format($tax, 2) }}€</span>
            </div>
            <div>
                <span>Gastos de envío:</span>
                <span>{{ number_format($shipping, 2) }}€</span>
            </div>
            @if($discount > 0)
                <div>
                    <span>Descuento:</span>
                    <span>-{{ number_format($discount, 2) }}€</span>
                </div>
            @endif
            <div class="total">
                <span>Total:</span>
                <span>{{ number_format($total, 2) }}€</span>
            </div>
        </div>
        
        <div style="clear: both;"></div>
        
        <div class="footer">
            <p>Gracias por su compra en Trendfit. Para cualquier consulta, contáctenos en info@trendfit.com o llame al +34 93 123 45 67.</p>
            <p>Esta factura ha sido generada automáticamente y es válida sin firma ni sello.</p>
        </div>
    </div>
</body>
</html>