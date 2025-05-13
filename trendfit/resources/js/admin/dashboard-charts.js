document.addEventListener('DOMContentLoaded', function() {
    // Obtener datos pasados desde la vista
    const salesLabels = window.dashboardData.monthlySalesLabels;
    const salesData = window.dashboardData.monthlySalesData;
    const productLabels = window.dashboardData.topProductsLabels;
    const productData = window.dashboardData.topProductsData;

    // Inicializar los gráficos
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const productsCtx = document.getElementById('productsChart').getContext('2d');

    // Dibujar gráficos
    drawSalesChart(salesCtx, salesLabels, salesData);
    drawProductsChart(productsCtx, productLabels, productData);
});

/**
 * Dibuja un gráfico de líneas para las ventas mensuales usando canvas
 * @param {CanvasRenderingContext2D} ctx - Contexto del canvas
 * @param {Array} labels - Etiquetas para el eje X (meses)
 * @param {Array} data - Datos de ventas
 */
function drawSalesChart(ctx, labels, data) {
    // Limpiar canvas
    const canvas = ctx.canvas;
    const width = canvas.width;
    const height = canvas.height;
    ctx.clearRect(0, 0, width, height);
    
    // Si no hay datos, mostrar mensaje
    if (!data || data.length === 0) {
        ctx.font = '14px Arial';
        ctx.fillStyle = '#666';
        ctx.textAlign = 'center';
        ctx.fillText('No hay datos disponibles', width / 2, height / 2);
        return;
    }
    
    // Calcular valores para la escala
    const maxValue = Math.max(...data) * 1.2; // Añadir 20% para margen superior
    const padding = 40; // Espacio para texto y ejes
    const chartAreaWidth = width - (padding * 2);
    const chartAreaHeight = height - (padding * 2);
    
    // Dibujar ejes
    ctx.beginPath();
    ctx.moveTo(padding, padding);
    ctx.lineTo(padding, height - padding);
    ctx.lineTo(width - padding, height - padding);
    ctx.strokeStyle = '#ccc';
    ctx.stroke();
    
    // Dibujar líneas horizontales de la cuadrícula y etiquetas del eje Y
    const numHorizontalLines = 5;
    ctx.font = '12px Arial';
    ctx.textAlign = 'right';
    ctx.fillStyle = '#666';
    
    for (let i = 0; i <= numHorizontalLines; i++) {
        const y = padding + ((numHorizontalLines - i) / numHorizontalLines) * chartAreaHeight;
        const value = (i / numHorizontalLines) * maxValue;
        
        // Dibujar línea horizontal
        ctx.beginPath();
        ctx.moveTo(padding, y);
        ctx.lineTo(width - padding, y);
        ctx.strokeStyle = '#eee';
        ctx.stroke();
        
        // Dibujar etiqueta de valor
        ctx.fillText(value.toFixed(2) + '€', padding - 5, y + 4);
    }
    
    // Dibujar etiquetas del eje X (meses)
    const xStep = chartAreaWidth / (labels.length - 1);
    ctx.textAlign = 'center';
    ctx.fillStyle = '#666';
    
    labels.forEach((label, i) => {
        const x = padding + (i * xStep);
        ctx.fillText(label, x, height - padding + 15);
    });
    
    // Preparar para dibujar la línea de datos
    ctx.beginPath();
    ctx.lineWidth = 2;
    ctx.strokeStyle = 'rgba(59, 130, 246, 1)'; // Azul similar al de Tailwind
    
    // Dibujar puntos de datos y línea que los conecta
    data.forEach((value, i) => {
        const x = padding + (i * xStep);
        const y = height - padding - (value / maxValue * chartAreaHeight);
        
        if (i === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
        
        // También dibujar círculos en cada punto de datos
        ctx.fillStyle = 'white';
        ctx.beginPath();
        ctx.arc(x, y, 4, 0, Math.PI * 2);
        ctx.fill();
        ctx.strokeStyle = 'rgba(59, 130, 246, 1)';
        ctx.stroke();
    });
    
    // Dibujar la línea de tendencia de ventas
    ctx.strokeStyle = 'rgba(59, 130, 246, 1)';
    ctx.stroke();
    
    // Añadir relleno degradado bajo la línea
    const gradient = ctx.createLinearGradient(0, padding, 0, height - padding);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
    
    // Cerrar y rellenar el área bajo la línea
    ctx.lineTo(width - padding, height - padding);
    ctx.lineTo(padding, height - padding);
    ctx.fillStyle = gradient;
    ctx.fill();
}

/**
 * Dibuja un gráfico de barras para los productos más vendidos usando canvas
 * @param {CanvasRenderingContext2D} ctx - Contexto del canvas
 * @param {Array} labels - Etiquetas para cada barra (nombre de producto)
 * @param {Array} data - Datos de ventas por producto
 */
function drawProductsChart(ctx, labels, data) {
    // Limpiar canvas
    const canvas = ctx.canvas;
    const width = canvas.width;
    const height = canvas.height;
    ctx.clearRect(0, 0, width, height);
    
    // Si no hay datos, mostrar mensaje
    if (!data || data.length === 0) {
        ctx.font = '14px Arial';
        ctx.fillStyle = '#666';
        ctx.textAlign = 'center';
        ctx.fillText('No hay datos disponibles', width / 2, height / 2);
        return;
    }
    
    // Calcular valores para la escala
    const maxValue = Math.max(...data) * 1.2; // Añadir 20% para margen superior
    const padding = 50; // Espacio para texto y ejes
    const chartAreaWidth = width - (padding * 2);
    const chartAreaHeight = height - (padding * 2);
    
    // Dibujar ejes
    ctx.beginPath();
    ctx.moveTo(padding, padding);
    ctx.lineTo(padding, height - padding);
    ctx.lineTo(width - padding, height - padding);
    ctx.strokeStyle = '#ccc';
    ctx.stroke();
    
    // Dibujar líneas horizontales de la cuadrícula y etiquetas del eje Y
    const numHorizontalLines = 5;
    ctx.font = '12px Arial';
    ctx.textAlign = 'right';
    ctx.fillStyle = '#666';
    
    for (let i = 0; i <= numHorizontalLines; i++) {
        const y = padding + ((numHorizontalLines - i) / numHorizontalLines) * chartAreaHeight;
        const value = (i / numHorizontalLines) * maxValue;
        
        // Dibujar línea horizontal
        ctx.beginPath();
        ctx.moveTo(padding, y);
        ctx.lineTo(width - padding, y);
        ctx.strokeStyle = '#eee';
        ctx.stroke();
        
        // Dibujar etiqueta de valor (número entero para las unidades vendidas)
        ctx.fillText(Math.round(value), padding - 5, y + 4);
    }
    
    // Calcular ancho de las barras y espacio entre ellas
    const numBars = data.length;
    const barWidth = (chartAreaWidth / numBars) * 0.6; // 60% del espacio disponible
    const barSpacing = (chartAreaWidth / numBars) * 0.4 / 2; // 40% del espacio disponible dividido entre 2
    
    // Colores para las barras (más llamativos como en la imagen de ejemplo)
    const barColors = [
        '#2ecc71', // Verde
        '#e74c3c', // Rojo
        '#3498db', // Azul
        '#e67e22', // Naranja
        '#f1c40f'  // Amarillo
    ];
    
    // Dibujar barras y etiquetas
    data.forEach((value, i) => {
        // Calcular posición X de la barra
        const barX = padding + (i * (barWidth + barSpacing * 2)) + barSpacing;
        
        // Calcular altura y posición Y de la barra
        const barHeight = (value / maxValue) * chartAreaHeight;
        const barY = height - padding - barHeight;
        
        // Dibujar la barra
        ctx.fillStyle = barColors[i % barColors.length];
        ctx.fillRect(barX, barY, barWidth, barHeight);
        
        // No dibujar borde para que se asemeje más a la imagen
        
        // Dibujar el valor encima de la barra
        ctx.fillStyle = '#333';
        ctx.textAlign = 'center';
        ctx.font = '12px Arial';
        ctx.fillText(value, barX + barWidth / 2, barY - 5);
        
        // Dibujar etiqueta del producto (nombre) abajo
        ctx.fillStyle = '#666';
        ctx.font = '10px Arial';
        
        // Acortar el nombre si es muy largo
        let label = labels[i];
        if (label && label.length > 10) {
            label = label.substring(0, 8) + '...';
        }
        
        ctx.fillText(label || 'Sin nombre', barX + barWidth / 2, height - padding + 15);
    });
    
    // Añadir texto de "Gestió de estoc" en la parte inferior central
    ctx.fillStyle = '#333';
    ctx.font = 'bold 12px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('Gestió de estoc', width / 2, height - 5);
}