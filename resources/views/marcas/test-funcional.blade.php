<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Marcas - Test Funcional</title>
    
    <!-- jQuery PRIMERO -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap después -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>🧪 Test Marcas - AJAX Funcional</h1>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <button class="btn btn-success" onclick="testCrearMarca()">
                    <i class="bi bi-plus-circle me-2"></i>Test Crear Marca
                </button>
            </div>
            <div class="col-md-6">
                <div id="resultado" class="alert alert-info">
                    Esperando test...
                </div>
            </div>
        </div>
        
        <!-- Lista de marcas existentes -->
        <div class="mt-4">
            <h3>Marcas Existentes:</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\Marca::all() as $marca)
                    <tr>
                        <td>{{ $marca->id }}</td>
                        <td>{{ $marca->nombre }}</td>
                        <td>
                            @if($marca->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="testEditarMarca({{ $marca->id }}, '{{ $marca->nombre }}', {{ $marca->activo ? 'true' : 'false' }})">
                                <i class="bi bi-pencil"></i> Test Editar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // CSRF Token configurado correctamente
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function testCrearMarca() {
            const nombre = 'Marca Test ' + Date.now();
            
            $('#resultado').removeClass().addClass('alert alert-warning').text('🔄 Creando marca...');
            
            $.ajax({
                url: '/marcas',
                method: 'POST',
                data: {
                    nombre: nombre,
                    descripcion: 'Marca de prueba creada automáticamente',
                    activo: 1,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#resultado').removeClass().addClass('alert alert-success').html(
                            '<i class="bi bi-check-circle me-2"></i><strong>✅ ÉXITO:</strong> ' + response.message
                        );
                        // Recargar página después de 2 segundos
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        $('#resultado').removeClass().addClass('alert alert-danger').html(
                            '<i class="bi bi-x-circle me-2"></i><strong>❌ ERROR:</strong> ' + response.message
                        );
                    }
                },
                error: function(xhr) {
                    let message = 'Error desconocido';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            message = Object.values(xhr.responseJSON.errors).flat().join(', ');
                        } else if (xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                    }
                    $('#resultado').removeClass().addClass('alert alert-danger').html(
                        '<i class="bi bi-exclamation-triangle me-2"></i><strong>🚨 ERROR AJAX:</strong> ' + message
                    );
                }
            });
        }

        function testEditarMarca(id, nombre, activo) {
            const nuevoNombre = nombre + ' EDITADO';
            
            $('#resultado').removeClass().addClass('alert alert-warning').text('🔄 Editando marca...');
            
            $.ajax({
                url: `/marcas/${id}`,
                method: 'PUT',
                data: {
                    nombre: nuevoNombre,
                    descripcion: 'Marca editada en test',
                    activo: activo ? 0 : 1, // Cambiar estado
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'PUT'
                },
                success: function(response) {
                    if (response.success) {
                        $('#resultado').removeClass().addClass('alert alert-success').html(
                            '<i class="bi bi-check-circle me-2"></i><strong>✅ EDITADO:</strong> ' + response.message
                        );
                        // Recargar página después de 2 segundos
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        $('#resultado').removeClass().addClass('alert alert-danger').html(
                            '<i class="bi bi-x-circle me-2"></i><strong>❌ ERROR:</strong> ' + response.message
                        );
                    }
                },
                error: function(xhr) {
                    let message = 'Error desconocido';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            message = Object.values(xhr.responseJSON.errors).flat().join(', ');
                        } else if (xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                    }
                    $('#resultado').removeClass().addClass('alert alert-danger').html(
                        '<i class="bi bi-exclamation-triangle me-2"></i><strong>🚨 ERROR AJAX:</strong> ' + message
                    );
                }
            });
        }

        // Test inicial cuando la página carga
        $(document).ready(function() {
            $('#resultado').removeClass().addClass('alert alert-success').html(
                '<i class="bi bi-check-circle me-2"></i><strong>✅ LISTO:</strong> jQuery cargado, CSRF configurado, AJAX preparado'
            );
        });
    </script>
</body>
</html> 