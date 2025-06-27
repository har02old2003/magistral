@extends('layouts.modern')

@section('title', 'Dashboard - Farmacia Magistral')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="page-header" data-aos="fade-down">
        <h1 class="page-title">
            <i class="bi bi-speedometer2 me-3"></i>Dashboard
        </h1>
        <p class="page-subtitle">Panel de control y estadísticas del sistema</p>
        
        @if(session('success'))
        <div class="alert alert-success alert-modern mt-3" data-aos="fade-in">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
        @endif
    </div>

    @php
        try {
            $totalProductos = \App\Models\Producto::count();
            $productosStockBajo = \App\Models\Producto::where('stock_actual', '<=', 10)->count();
            $totalVentas = \App\Models\Venta::count();
            $ventasHoy = \App\Models\Venta::whereDate('fecha', today())->count();
            $totalMarcas = \App\Models\Marca::count();
            $totalClientes = \App\Models\Cliente::count();
            $ingresosDia = \App\Models\Venta::whereDate('fecha', today())->sum('total');
            $ventasMes = \App\Models\Venta::whereMonth('fecha', now()->month)->count();
            $ingresosMes = \App\Models\Venta::whereMonth('fecha', now()->month)->sum('total');
            $clientesActivos = \App\Models\Cliente::where('activo', true)->count();
        } catch(\Exception $e) {
            $totalProductos = 0; $productosStockBajo = 0; $totalVentas = 0; $ventasHoy = 0;
            $totalMarcas = 0; $totalClientes = 0; $ingresosDia = 0; $ventasMes = 0; $ingresosMes = 0; $clientesActivos = 0;
        }
    @endphp

    <!-- Estadísticas Principales -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="100">
            <div class="stat-card primary">
                <div class="stat-icon text-primary">
                    <i class="bi bi-capsule"></i>
                </div>
                <div class="stat-value text-primary">{{ $totalProductos }}</div>
                <div class="stat-label">Total Productos</div>
                <div class="mt-3">
                    <a href="/productos" class="btn btn-primary-modern btn-modern btn-sm">
                        <i class="bi bi-arrow-right me-1"></i> Ver Productos
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="200">
            <div class="stat-card success">
                <div class="stat-icon text-success">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div class="stat-value text-success">{{ $ventasHoy }}</div>
                <div class="stat-label">Ventas Hoy</div>
                <div class="mt-3">
                    <a href="/ventas" class="btn btn-success-modern btn-modern btn-sm">
                        <i class="bi bi-arrow-right me-1"></i> Ver Ventas
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="300">
            <div class="stat-card warning">
                <div class="stat-icon text-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-value text-warning">{{ $productosStockBajo }}</div>
                <div class="stat-label">Stock Bajo</div>
                <div class="mt-3">
                    <button class="btn btn-warning-modern btn-modern btn-sm" onclick="verStockBajo()">
                        <i class="bi bi-eye me-1"></i> Revisar Stock
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="400">
            <div class="stat-card info">
                <div class="stat-icon text-info">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-value text-info">S/ {{ number_format($ingresosDia, 2) }}</div>
                <div class="stat-label">Ingresos Hoy</div>
                <div class="mt-3">
                    <button class="btn btn-info-modern btn-modern btn-sm" onclick="verIngresos()">
                        <i class="bi bi-graph-up me-1"></i> Ver Ingresos
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Secundarias -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4" data-aos="fade-right">
            <div class="modern-card">
                <h5 class="mb-4">
                    <i class="bi bi-calendar-month text-primary me-2"></i>
                    Estadísticas del Mes
                </h5>
                <div class="row">
                    <div class="col-6">
                        <div class="text-center p-3">
                            <div class="display-6 text-primary fw-bold">{{ $ventasMes }}</div>
                            <small class="text-muted">Ventas del Mes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3">
                            <div class="display-6 text-success fw-bold">S/ {{ number_format($ingresosMes, 2) }}</div>
                            <small class="text-muted">Ingresos del Mes</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4" data-aos="fade-left">
            <div class="modern-card">
                <h5 class="mb-4">
                    <i class="bi bi-people text-success me-2"></i>
                    Estado de Clientes
                </h5>
                <div class="row">
                    <div class="col-6">
                        <div class="text-center p-3">
                            <div class="display-6 text-info fw-bold">{{ $totalClientes }}</div>
                            <small class="text-muted">Total Clientes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3">
                            <div class="display-6 text-success fw-bold">{{ $clientesActivos }}</div>
                            <small class="text-muted">Clientes Activos</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas y Notificaciones -->
    @if($productosStockBajo > 0)
    <div class="row mb-4">
        <div class="col-12" data-aos="fade-up">
            <div class="alert alert-warning alert-modern">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-3" style="font-size: 2rem;"></i>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading mb-2">¡Atención!</h5>
                        <p class="mb-2">Hay <strong>{{ $productosStockBajo }}</strong> productos con stock bajo que necesitan reabastecimiento.</p>
                        <button class="btn btn-warning-modern btn-modern btn-sm" onclick="verStockBajo()">
                            <i class="bi bi-eye me-1"></i> Revisar productos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Acciones Rápidas -->
    <div class="row mb-4">
        <div class="col-12" data-aos="fade-up" data-aos-delay="100">
            <div class="modern-card">
                <h5 class="mb-4">
                    <i class="bi bi-lightning text-warning me-2"></i>
                    Acciones Rápidas
                </h5>
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="/ventas/create" class="btn btn-success-modern btn-modern w-100 py-3">
                            <i class="bi bi-plus-circle me-2"></i>
                            Nueva Venta
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="/productos/create" class="btn btn-primary-modern btn-modern w-100 py-3">
                            <i class="bi bi-plus-circle me-2"></i>
                            Nuevo Producto
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="/clientes/create" class="btn btn-info-modern btn-modern w-100 py-3">
                            <i class="bi bi-person-plus me-2"></i>
                            Nuevo Cliente
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <button class="btn btn-warning-modern btn-modern w-100 py-3" onclick="generarReporte()">
                            <i class="bi bi-file-earmark-pdf me-2"></i>
                            Generar Reporte
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen del Sistema -->
    <div class="row">
        <div class="col-12" data-aos="fade-up" data-aos-delay="200">
            <div class="modern-card">
                <h5 class="mb-4">
                    <i class="bi bi-bar-chart text-primary me-2"></i>
                    Resumen del Sistema
                </h5>
                <div class="row text-center">
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="p-3">
                            <i class="bi bi-capsule text-primary" style="font-size: 2rem;"></i>
                            <div class="fw-bold text-primary mt-2">{{ $totalProductos }}</div>
                            <small class="text-muted">Productos</small>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="p-3">
                            <i class="bi bi-tags text-info" style="font-size: 2rem;"></i>
                            <div class="fw-bold text-info mt-2">{{ $totalMarcas }}</div>
                            <small class="text-muted">Marcas</small>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="p-3">
                            <i class="bi bi-people text-success" style="font-size: 2rem;"></i>
                            <div class="fw-bold text-success mt-2">{{ $totalClientes }}</div>
                            <small class="text-muted">Clientes</small>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="p-3">
                            <i class="bi bi-cart-check text-warning" style="font-size: 2rem;"></i>
                            <div class="fw-bold text-warning mt-2">{{ $totalVentas }}</div>
                            <small class="text-muted">Ventas</small>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="p-3">
                            <i class="bi bi-currency-dollar text-danger" style="font-size: 2rem;"></i>
                            <div class="fw-bold text-danger mt-2">S/ {{ number_format($ingresosMes, 0) }}</div>
                            <small class="text-muted">Ingresos</small>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="p-3">
                            <i class="bi bi-calendar-check text-secondary" style="font-size: 2rem;"></i>
                            <div class="fw-bold text-secondary mt-2">{{ now()->format('d/m') }}</div>
                            <small class="text-muted">Hoy</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales -->
<div class="modal fade" id="stockBajoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    Productos con Stock Bajo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="stockBajoContent"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script>
function verStockBajo() {
    fetch('/dashboard/stock-bajo')
        .then(response => response.json())
        .then(data => {
            let html = '<div class="table-responsive"><table class="table">';
            html += '<thead class="table-dark"><tr><th>Producto</th><th>Stock</th><th>Estado</th></tr></thead><tbody>';
            
            data.productos.forEach(producto => {
                html += `<tr>
                    <td>${producto.nombre}</td>
                    <td><span class="badge bg-warning">${producto.stock_actual}</span></td>
                    <td><span class="badge bg-danger">Stock Bajo</span></td>
                </tr>`;
            });
            
            html += '</tbody></table></div>';
            document.getElementById('stockBajoContent').innerHTML = html;
            new bootstrap.Modal(document.getElementById('stockBajoModal')).show();
        })
        .catch(error => {
            document.getElementById('stockBajoContent').innerHTML = 
                '<div class="alert alert-danger">Error al cargar los datos</div>';
            new bootstrap.Modal(document.getElementById('stockBajoModal')).show();
        });
}

function verIngresos() {
    window.location.href = '/ventas';
}

function generarReporte() {
    alert('Funcionalidad de reportes en desarrollo');
}

// Efectos adicionales
document.addEventListener('DOMContentLoaded', function() {
    // Animación de contadores
    const counters = document.querySelectorAll('.stat-value');
    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
        let current = 0;
        const increment = target / 20;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = counter.textContent.replace(/\d+/, target);
                clearInterval(timer);
            } else {
                counter.textContent = counter.textContent.replace(/\d+/, Math.floor(current));
            }
        }, 100);
    });
});
</script>
@endsection 