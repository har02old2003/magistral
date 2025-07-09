{{-- Modales del Sistema CRUD - Farmacia Magistral --}}

{{-- MODAL VENTAS - CREAR --}}
<div class="modal fade" id="modalVentasAgregar" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-plus-circle me-2"></i>Nueva Venta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formVentasAgregar">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cliente</label>
                                <select class="form-select" name="cliente_id">
                                    <option value="">Seleccionar cliente...</option>
                                    @php
                                        try {
                                            $clientes = \App\Models\Cliente::where('activo', true)->get();
                                            foreach($clientes as $cliente) {
                                                echo '<option value="'.$cliente->id.'">'.$cliente->nombres.' '.$cliente->apellidos.'</option>';
                                            }
                                        } catch(\Exception $e) {}
                                    @endphp
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Pago</label>
                                <select class="form-select" name="tipo_pago" required>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta">Tarjeta</option>
                                    <option value="transferencia">Transferencia</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Buscar Productos</label>
                        <input type="text" class="form-control" placeholder="Buscar por nombre o código...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Productos en Venta</label>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-cart-x" style="font-size: 2rem;"></i>
                            <p>No hay productos en el carrito</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" onclick="guardarRegistro('ventas', false)">
                        <i class="bi bi-save me-2"></i>Procesar Venta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL VENTAS - EDITAR --}}
<div class="modal fade" id="modalVentasEditar" tabindex="-1" aria-labelledby="modalVentasEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%); color: white;">
                <h5 class="modal-title fw-bold" id="modalVentasEditarLabel">
                    <i class="bi bi-pencil me-2"></i>Editar Venta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formVentasEditar">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_cliente_id" class="form-label">Cliente</label>
                                <select class="form-select" name="cliente_id" id="edit_cliente_id">
                                    <option value="">Seleccionar cliente...</option>
                                    @php
                                        try {
                                            $clientes = \App\Models\Cliente::where('activo', true)->get();
                                            foreach($clientes as $cliente) {
                                                echo '<option value="'.$cliente->id.'">'.$cliente->nombres.' '.$cliente->apellidos.'</option>';
                                            }
                                        } catch(\Exception $e) {}
                                    @endphp
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_tipo_pago" class="form-label">Tipo de Pago</label>
                                <select class="form-select" name="tipo_pago" id="edit_tipo_pago" required>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta">Tarjeta</option>
                                    <option value="transferencia">Transferencia</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" id="edit_observaciones" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-warning" onclick="guardarRegistro('Ventas', true)">
                        <i class="bi bi-save me-2"></i>Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL VENTAS - VER --}}
<div class="modal fade" id="modalVentasVer" tabindex="-1" aria-labelledby="modalVentasVerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%); color: white;">
                <h5 class="modal-title fw-bold" id="modalVentasVerLabel">
                    <i class="bi bi-eye me-2"></i>Detalles de Venta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detalles_venta">
                    <!-- Los detalles se cargarán aquí dinámicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="imprimirRegistro('ventas', registroActual)">
                    <i class="bi bi-printer me-2"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CAJAS - CREAR --}}
<div class="modal fade" id="modalCajasAgregar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%); color: white;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-cash-stack me-2"></i>Nuevo Movimiento de Caja
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form>
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Movimiento</label>
                                <select class="form-select" name="tipo_movimiento" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="ingreso">Ingreso</option>
                                    <option value="egreso">Egreso</option>
                                    <option value="apertura">Apertura de Caja</option>
                                    <option value="cierre">Cierre de Caja</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Monto</label>
                                <input type="number" class="form-control" name="monto" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Concepto</label>
                        <input type="text" class="form-control" name="concepto" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarRegistro('caja', false)">
                        <i class="bi bi-save me-2"></i>Registrar Movimiento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL PROFORMAS - CREAR --}}
<div class="modal fade" id="modalProformasAgregar" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%); color: white;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-file-earmark-text me-2"></i>Nueva Proforma
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form>
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cliente</label>
                                <select class="form-select" name="cliente_id" required>
                                    <option value="">Seleccionar cliente...</option>
                                    @php
                                        try {
                                            $clientes = \App\Models\Cliente::where('activo', true)->get();
                                            foreach($clientes as $cliente) {
                                                echo '<option value="'.$cliente->id.'">'.$cliente->nombres.' '.$cliente->apellidos.'</option>';
                                            }
                                        } catch(\Exception $e) {}
                                    @endphp
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha de Vencimiento</label>
                                <input type="date" class="form-control" name="fecha_vencimiento" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Buscar Productos</label>
                        <input type="text" class="form-control" placeholder="Buscar por nombre o código...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Productos en Proforma</label>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-list-ul" style="font-size: 2rem;"></i>
                            <p>No hay productos en la proforma</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-warning" onclick="guardarRegistro('proformas', false)">
                        <i class="bi bi-save me-2"></i>Crear Proforma
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL TERCEROS - CREAR --}}
<div class="modal fade" id="modalTercerosAgregar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%); color: white;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-person-plus me-2"></i>Nuevo Cliente/Proveedor
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form>
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo</label>
                                <select class="form-select" name="tipo" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="cliente">Cliente</option>
                                    <option value="proveedor">Proveedor</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Documento</label>
                                <input type="text" class="form-control" name="documento" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombres</label>
                                <input type="text" class="form-control" name="nombres" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Apellidos</label>
                                <input type="text" class="form-control" name="apellidos">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="telefono">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <textarea class="form-control" name="direccion" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" onclick="guardarRegistro('clientes', false)">
                        <i class="bi bi-save me-2"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL GENÉRICO DE CONFIRMACIÓN --}}
<div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold" id="modalConfirmacionLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirmación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="mensajeConfirmacion">¿Estás seguro de realizar esta acción?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" id="btnConfirmar">Confirmar</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DE BÚSQUEDA AVANZADA --}}
<div class="modal fade" id="modalBusquedaAvanzada" tabindex="-1" aria-labelledby="modalBusquedaAvanzadaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #6f42c1 0%, #007bff 100%); color: white;">
                <h5 class="modal-title fw-bold" id="modalBusquedaAvanzadaLabel">
                    <i class="bi bi-search me-2"></i>Búsqueda Avanzada
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formBusquedaAvanzada">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="buscar_fecha_desde" class="form-label">Fecha Desde</label>
                                <input type="date" class="form-control" id="buscar_fecha_desde">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="buscar_fecha_hasta" class="form-label">Fecha Hasta</label>
                                <input type="date" class="form-control" id="buscar_fecha_hasta">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="buscar_texto" class="form-label">Texto de Búsqueda</label>
                        <input type="text" class="form-control" id="buscar_texto" placeholder="Buscar en todos los campos...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="buscar_estado" class="form-label">Estado</label>
                        <select class="form-select" id="buscar_estado">
                            <option value="">Todos los estados</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                            <option value="pendiente">Pendiente</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="aplicarBusquedaAvanzada()">
                    <i class="bi bi-search me-2"></i>Buscar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PEDIDOS - CREAR --}}
<div class="modal fade" id="modalPedidosAgregar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%); color: white;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-clipboard-plus me-2"></i>Nuevo Pedido
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form>
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Proveedor</label>
                                <select class="form-select" name="proveedor_id" required>
                                    <option value="">Seleccionar proveedor...</option>
                                    @php
                                        try {
                                            $proveedores = \App\Models\Proveedor::where('activo', true)->get();
                                            foreach($proveedores as $proveedor) {
                                                echo '<option value="'.$proveedor->id.'">'.$proveedor->nombre.'</option>';
                                            }
                                        } catch(\Exception $e) {}
                                    @endphp
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha de Entrega</label>
                                <input type="date" class="form-control" name="fecha_entrega" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Buscar Productos</label>
                        <input type="text" class="form-control" placeholder="Buscar por nombre o código...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Productos en Pedido</label>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-list-ul" style="font-size: 2rem;"></i>
                            <p>No hay productos en el pedido</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarRegistro('pedidos', false)">
                        <i class="bi bi-save me-2"></i>Crear Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL HISTORIA CLÍNICA - CREAR --}}
<div class="modal fade" id="modalHistoriaClinicaAgregar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #e83e8c 0%, #fd7e14 100%); color: white;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-journal-medical me-2"></i>Nueva Historia Clínica
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form>
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cliente</label>
                                <select class="form-select" name="cliente_id" required>
                                    <option value="">Seleccionar cliente...</option>
                                    @php
                                        try {
                                            $clientes = \App\Models\Cliente::where('activo', true)->get();
                                            foreach($clientes as $cliente) {
                                                echo '<option value="'.$cliente->id.'">'.$cliente->nombres.' '.$cliente->apellidos.'</option>';
                                            }
                                        } catch(\Exception $e) {}
                                    @endphp
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha de Apertura</label>
                                <input type="date" class="form-control" name="fecha_apertura" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Peso (kg)</label>
                                <input type="number" class="form-control" name="peso" step="0.1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Altura (cm)</label>
                                <input type="number" class="form-control" name="altura">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alergias Conocidas</label>
                        <textarea class="form-control" name="alergias" rows="2"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Antecedentes Médicos</label>
                        <textarea class="form-control" name="antecedentes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" onclick="guardarRegistro('historia-clinica', false)">
                        <i class="bi bi-save me-2"></i>Crear Historia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DELIVERY - CREAR --}}
<div class="modal fade" id="modalDeliveryAgregar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-truck me-2"></i>Nuevo Delivery
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form>
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Venta Asociada</label>
                                <select class="form-select" name="venta_id" required>
                                    <option value="">Seleccionar venta...</option>
                                    @php
                                        try {
                                            $ventas = \App\Models\Venta::with('cliente')->latest()->limit(50)->get();
                                            foreach($ventas as $venta) {
                                                echo '<option value="'.$venta->id.'">Venta #'.$venta->numero_ticket.' - '.$venta->cliente?->nombres.'</option>';
                                            }
                                        } catch(\Exception $e) {}
                                    @endphp
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha de Entrega</label>
                                <input type="datetime-local" class="form-control" name="fecha_entrega" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Repartidor</label>
                                <select class="form-select" name="repartidor_id">
                                    <option value="">Asignar después...</option>
                                    @php
                                        try {
                                            $repartidores = \App\Models\User::where('role', 'repartidor')->get();
                                            foreach($repartidores as $repartidor) {
                                                echo '<option value="'.$repartidor->id.'">'.$repartidor->name.'</option>';
                                            }
                                        } catch(\Exception $e) {}
                                    @endphp
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Costo de Envío</label>
                                <input type="number" class="form-control" name="costo_envio" step="0.01" value="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Dirección de Entrega</label>
                        <textarea class="form-control" name="direccion_entrega" rows="2" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" onclick="guardarRegistro('delivery', false)">
                        <i class="bi bi-save me-2"></i>Programar Delivery
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL CONTABILIDAD - CREAR --}}
<div class="modal fade" id="modalContabilidadAgregar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #6f42c1 0%, #495057 100%); color: white;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-journal-bookmark me-2"></i>Nuevo Asiento Contable
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form>
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha del Asiento</label>
                                <input type="date" class="form-control" name="fecha" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Asiento</label>
                                <select class="form-select" name="tipo_asiento" required>
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="venta">Venta</option>
                                    <option value="compra">Compra</option>
                                    <option value="gasto">Gasto</option>
                                    <option value="ingreso">Ingreso</option>
                                    <option value="ajuste">Ajuste</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <input type="text" class="form-control" name="descripcion" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cuenta Débito</label>
                                <select class="form-select" name="cuenta_debito" required>
                                    <option value="">Seleccionar cuenta...</option>
                                    <option value="1010">1010 - Caja</option>
                                    <option value="1020">1020 - Bancos</option>
                                    <option value="1030">1030 - Inventarios</option>
                                    <option value="1040">1040 - Cuentas por Cobrar</option>
                                    <option value="2010">2010 - Cuentas por Pagar</option>
                                    <option value="4010">4010 - Ventas</option>
                                    <option value="5010">5010 - Costo de Ventas</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cuenta Crédito</label>
                                <select class="form-select" name="cuenta_credito" required>
                                    <option value="">Seleccionar cuenta...</option>
                                    <option value="1010">1010 - Caja</option>
                                    <option value="1020">1020 - Bancos</option>
                                    <option value="1030">1030 - Inventarios</option>
                                    <option value="1040">1040 - Cuentas por Cobrar</option>
                                    <option value="2010">2010 - Cuentas por Pagar</option>
                                    <option value="4010">4010 - Ventas</option>
                                    <option value="5010">5010 - Costo de Ventas</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Monto Débito</label>
                                <input type="number" class="form-control" name="monto_debito" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Monto Crédito</label>
                                <input type="number" class="form-control" name="monto_credito" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarRegistro('contabilidad', false)">
                        <i class="bi bi-save me-2"></i>Registrar Asiento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Universal de Cerrar Sesión -->
<div class="modal fade" id="modalCerrarSesion" tabindex="-1" aria-labelledby="modalCerrarSesionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); color: white; border: none;">
                <h5 class="modal-title fw-bold" id="modalCerrarSesionLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirmar Cierre de Sesión
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <i class="bi bi-person-x text-danger" style="font-size: 4rem; opacity: 0.8;"></i>
                </div>
                <h6 class="mb-3">¿Está seguro de que desea cerrar sesión?</h6>
                <p class="text-muted mb-4">
                    Se cerrará su sesión actual y será redirigido al login.<br>
                    <small><strong>Usuario:</strong> {{ auth()->user()->name ?? 'Usuario' }}</small>
                </p>
                <div class="progress mb-3" id="logout-progress" style="display: none; height: 8px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" style="width: 100%"></div>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-danger btn-lg px-4 ms-3" onclick="ejecutarCerrarSesionUniversal()">
                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para aplicar búsqueda avanzada
function aplicarBusquedaAvanzada() {
    const fechaDesde = document.getElementById('buscar_fecha_desde').value;
    const fechaHasta = document.getElementById('buscar_fecha_hasta').value;
    const texto = document.getElementById('buscar_texto').value;
    const estado = document.getElementById('buscar_estado').value;
    
    // Implementar lógica de búsqueda
    console.log('Aplicando búsqueda avanzada:', { fechaDesde, fechaHasta, texto, estado });
    
    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalBusquedaAvanzada'));
    modal.hide();
}
</script> 