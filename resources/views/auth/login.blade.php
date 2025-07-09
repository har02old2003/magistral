<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Farmacia Magistral</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            margin: 20px;
        }
        
        .login-left {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .login-right {
            padding: 4rem 3rem;
        }
        
        .logo {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            padding: 12px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }
        
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .input-group .form-control {
            padding-left: 50px;
        }
        
        .input-group-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            z-index: 5;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .feature-item i {
            margin-right: 1rem;
            font-size: 1.2rem;
        }
        
        @media (max-width: 768px) {
            .login-left {
                display: none;
            }
            
            .login-right {
                padding: 2rem 1.5rem;
            }
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .users-demo {
            background-color: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .user-demo-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card animate-fade-in">
            <div class="row g-0 h-100">
                <!-- Panel Izquierdo -->
                <div class="col-lg-6 login-left">
                    <div>
                        <img src="/logo-farmacia.png" alt="Farmacia Magistral" style="max-width: 90px; margin-bottom: 1rem;">
                        <h2 class="mb-3">Farmacia Magistral</h2>
                        <p class="mb-4">Sistema Integral de Gestión Farmacéutica</p>
                        
                        <div class="feature-item">
                            <i class="bi bi-check-circle"></i>
                            <span>Control de Inventario Inteligente</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-check-circle"></i>
                            <span>Gestión de Ventas Automatizada</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-check-circle"></i>
                            <span>Alertas de Vencimiento</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-check-circle"></i>
                            <span>Reportes en Tiempo Real</span>
                        </div>
                        
                        <div class="users-demo">
                            <h6 class="mb-3"><i class="bi bi-info-circle me-2"></i>Usuarios de Prueba</h6>
                            <div class="user-demo-item">
                                <strong>Administrador:</strong>
                                <span>admin@farmacia.com / admin123</span>
                            </div>
                            <div class="user-demo-item">
                                <strong>Empleado:</strong>
                                <span>empleado@farmacia.com / empleado123</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Panel Derecho - Formulario -->
                <div class="col-lg-6 login-right">
                    <div>
                        <div class="text-center mb-4">
                            <img src="/logo-farmacia.png" alt="Farmacia Magistral" style="max-width: 70px; margin-bottom: 0.5rem;">
                            <h3 class="mb-2">Farmacia Magistral</h3>
                            <p class="text-muted">¡Bienvenido de nuevo! Ingresa tus credenciales para acceder al sistema</p>
                        </div>
                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                {{ $errors->first() }}
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <div class="input-group">
                                <i class="bi bi-envelope input-group-icon"></i>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="Correo electrónico"
                                       required
                                       autofocus>
                            </div>
                            
                            <div class="input-group">
                                <i class="bi bi-lock input-group-icon"></i>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" 
                                       placeholder="Contraseña"
                                       required>
                            </div>
                            
                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Recordar sesión
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Iniciar Sesión
                            </button>
                            
                            <div class="text-center">
                                <small class="text-muted">
                                    Sistema protegido • Farmacia Magistral © {{ date('Y') }}
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Efecto de typing en el placeholder
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                input.addEventListener('blur', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html> 