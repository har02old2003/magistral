# Sistema de Gestión para Farmacia Magistral

## Descripción
Sistema web desarrollado en Laravel para la gestión integral de ventas de la Farmacia Magistral. Incluye manejo de productos farmacéuticos con fechas de vencimiento, control de stock, generación de tickets de venta, y sistema de roles (administrador/empleado).

## Características Principales

### Funcionalidades Generales
- ✅ Sistema de autenticación nativo de Laravel
- ✅ Dashboard con estadísticas en tiempo real
- ✅ Notificaciones de productos con stock bajo
- ✅ Alertas de productos próximos a vencer (30 días)
- ✅ Gestión de roles: Administrador y Empleado
- ✅ Interfaz moderna y responsiva con Bootstrap 5

### Módulos del Sistema

#### Dashboard
- Estadísticas de ventas del día y mes
- Productos con stock bajo
- Productos próximos a vencer
- Ventas recientes
- Gráficos de ventas de los últimos 7 días

#### Productos
- CRUD completo de productos farmacéuticos
- Control de lotes y fechas de vencimiento (12, 18, 24 meses)
- Gestión de stock con alertas automáticas
- Categorización por tipo de medicamento
- Información detallada: principio activo, concentración, laboratorio

#### Ventas
- Punto de venta con búsqueda de productos
- Generación automática de tickets
- Cálculo automático de IGV (18%)
- Soporte para múltiples tipos de pago
- Actualización automática de stock

#### Clientes
- Registro completo de clientes
- Historial de compras
- Tipos de documento: DNI, CE, RUC

#### Gestión Administrativa (Solo Administrador)
- Marcas
- Proveedores
- Categorías
- Usuarios del sistema

## Permisos por Rol

### Administrador
- Acceso completo a todos los módulos
- Puede crear, editar y eliminar en todas las secciones
- Acceso a reportes y estadísticas
- Gestión de usuarios

### Empleado
- Acceso al Dashboard (solo lectura)
- Productos: Solo visualización
- Ventas: Puede crear y generar tickets
- Sin acceso a módulos administrativos

## Instalación y Configuración

### Requisitos del Sistema
- PHP 8.2 o superior
- Composer
- MySQL 8.0 o superior / SQLite (para desarrollo)
- Node.js y NPM (opcional, para compilar assets)

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone [url-del-repositorio]
cd farmacia-magistral
```

2. **Instalar dependencias**
```bash
composer install
```

3. **Configurar el archivo .env**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar la base de datos en .env**
```env
# Para MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=farmacia_magistral
DB_USERNAME=root
DB_PASSWORD=

# Para SQLite (desarrollo)
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite
```

5. **Ejecutar migraciones y seeders**
```bash
php artisan migrate --seed
```

6. **Iniciar el servidor**
```bash
php artisan serve
```

## Usuarios de Prueba

El sistema incluye los siguientes usuarios de prueba:

### Administrador
- **Email:** admin@farmacia.com
- **Contraseña:** admin123
- **Permisos:** Acceso completo al sistema

### Empleado 1
- **Email:** empleado@farmacia.com
- **Contraseña:** empleado123
- **Permisos:** Limitados según rol de empleado

### Empleado 2
- **Email:** maria@farmacia.com
- **Contraseña:** empleado123
- **Permisos:** Limitados según rol de empleado

## Datos de Prueba para la Base de Datos

### Proveedores (SQL)
```sql
INSERT INTO proveedores (nombre, ruc, telefono, email, direccion, contacto, activo, created_at, updated_at) VALUES
('Droguería Lima SAC', '20123456789', '01-4567890', 'ventas@drogueria.com', 'Av. Arequipa 1234, Lima', 'Carlos Mendoza', 1, NOW(), NOW()),
('Distribuidora Médica del Norte', '20987654321', '01-9876543', 'pedidos@medica.com', 'Jr. Junín 567, Trujillo', 'Ana García', 1, NOW(), NOW()),
('Laboratorios Unidos SA', '20456789123', '01-5551234', 'distribucion@unidos.com', 'Av. Brasil 890, Lima', 'Roberto Silva', 1, NOW(), NOW()),
('Farmacéutica Central', '20321654987', '01-7778888', 'central@farmaceutica.com', 'Calle Real 456, Arequipa', 'Lucia Torres', 1, NOW(), NOW()),
('Suministros Médicos del Sur', '20147258369', '01-3334444', 'ventas@suministros.com', 'Av. El Sol 789, Cusco', 'Miguel Ramos', 1, NOW(), NOW());
```

### Productos Farmacéuticos (SQL)
```sql
INSERT INTO productos (codigo, nombre, descripcion, precio_compra, precio_venta, stock_actual, stock_minimo, lote, fecha_vencimiento, meses_vencimiento, presentacion, principio_activo, concentracion, laboratorio, registro_sanitario, requiere_receta, activo, categoria_id, marca_id, proveedor_id, created_at, updated_at) VALUES
('PARA500-001', 'Paracetamol 500mg', 'Analgésico y antipirético', 8.50, 15.00, 150, 20, 'LOT2024A001', '2025-12-26', '12', 'Tabletas x 20', 'Paracetamol', '500mg', 'Laboratorio Nacional', 'E-12345', 0, 1, 1, 1, 1, NOW(), NOW()),
('IBUP400-002', 'Ibuprofeno 400mg', 'Antiinflamatorio no esteroideo', 12.00, 22.00, 80, 15, 'LOT2024B002', '2026-06-26', '18', 'Tabletas x 24', 'Ibuprofeno', '400mg', 'Bayer', 'E-23456', 0, 1, 3, 2, 1, NOW(), NOW()),
('AMOX875-003', 'Amoxicilina 875mg', 'Antibiótico penicilina', 25.00, 45.00, 60, 10, 'LOT2024C003', '2026-12-26', '24', 'Tabletas x 14', 'Amoxicilina', '875mg', 'Pfizer', 'E-34567', 1, 1, 2, 2, 2, NOW(), NOW()),
('VITC1000-004', 'Vitamina C 1000mg', 'Suplemento vitamínico', 18.00, 30.00, 120, 25, 'LOT2024D004', '2026-03-26', '18', 'Tabletas efervescentes x 20', 'Ácido Ascórbico', '1000mg', 'Abbott', 'S-45678', 0, 1, 5, 6, 3, NOW(), NOW()),
('ATOR20-005', 'Atorvastatina 20mg', 'Medicamento para colesterol', 35.00, 65.00, 45, 8, 'LOT2024E005', '2025-09-26', '12', 'Tabletas x 30', 'Atorvastatina', '20mg', 'Pfizer', 'E-56789', 1, 1, 6, 2, 1, NOW(), NOW()),
('SALF200-006', 'Salbutamol 200mcg', 'Broncodilatador inhalado', 28.00, 50.00, 35, 5, 'LOT2024F006', '2025-11-26', '12', 'Inhalador x 200 dosis', 'Salbutamol', '200mcg', 'GSK', 'E-67890', 1, 1, 7, 3, 4, NOW(), NOW()),
('OMEP20-007', 'Omeprazol 20mg', 'Inhibidor de bomba de protones', 15.00, 28.00, 90, 15, 'LOT2024G007', '2026-08-26', '24', 'Cápsulas x 14', 'Omeprazol', '20mg', 'Laboratorio Genérico', 'E-78901', 0, 1, 8, 10, 5, NOW(), NOW()),
('ALCO70-008', 'Alcohol en Gel 70%', 'Desinfectante para manos', 8.00, 15.00, 200, 30, 'LOT2024H008', '2025-12-26', '12', 'Frasco x 250ml', 'Alcohol etílico', '70%', 'Productos de Higiene SA', 'H-89012', 0, 1, 9, 8, 3, NOW(), NOW()),
('CREM50-009', 'Crema Hidratante 50g', 'Crema para piel seca', 12.00, 25.00, 75, 12, 'LOT2024I009', '2026-05-26', '18', 'Tubo x 50g', 'Urea + Glicerina', '10% + 5%', 'Dermatológicos Unidos', 'D-90123', 0, 1, 10, 7, 4, NOW(), NOW()),
('LORA10-010', 'Loratadina 10mg', 'Antihistamínico', 10.00, 18.00, 110, 20, 'LOT2024J010', '2025-10-26', '12', 'Tabletas x 10', 'Loratadina', '10mg', 'Novartis', 'E-01234', 0, 1, 4, 4, 2, NOW(), NOW());
```

### Clientes de Prueba (SQL)
```sql
INSERT INTO clientes (nombres, apellidos, documento, tipo_documento, telefono, email, direccion, fecha_nacimiento, genero, activo, created_at, updated_at) VALUES
('Juan Carlos', 'García López', '12345678', 'DNI', '987654321', 'juan.garcia@email.com', 'Av. Los Olivos 123, Lima', '1985-03-15', 'M', 1, NOW(), NOW()),
('María Elena', 'Rodríguez Silva', '87654321', 'DNI', '987123456', 'maria.rodriguez@email.com', 'Jr. Las Flores 456, Lima', '1990-07-22', 'F', 1, NOW(), NOW()),
('Carlos Alberto', 'Mendoza Torres', '11223344', 'DNI', '976543210', 'carlos.mendoza@email.com', 'Calle Los Pinos 789, Callao', '1978-11-30', 'M', 1, NOW(), NOW()),
('Ana Sofía', 'Vargas Delgado', '44332211', 'DNI', '965432109', 'ana.vargas@email.com', 'Av. San Martín 321, Lima', '1992-05-18', 'F', 1, NOW(), NOW()),
('Roberto Luis', 'Fernández Castro', '55667788', 'DNI', '954321098', 'roberto.fernandez@email.com', 'Jr. Independencia 654, Lima', '1975-09-12', 'M', 1, NOW(), NOW()),
('Lucía Patricia', 'Morales Quispe', '99887766', 'DNI', '943210987', 'lucia.morales@email.com', 'Av. Grau 987, Cercado de Lima', '1988-01-25', 'F', 1, NOW(), NOW()),
('Distribuidora Médica SAC', '', '20123456789', 'RUC', '01-7654321', 'ventas@distribuidora.com', 'Av. Industrial 1500, Lima', NULL, NULL, 1, NOW(), NOW()),
('José Manuel', 'Huamán Flores', '33445566', 'DNI', '932109876', 'jose.huaman@email.com', 'Calle Real 147, San Juan de Lurigancho', '1983-12-08', 'M', 1, NOW(), NOW()),
('Carmen Rosa', 'Pérez Gutiérrez', '66554433', 'DNI', '921098765', 'carmen.perez@email.com', 'Av. Túpac Amaru 258, Los Olivos', '1995-04-14', 'F', 1, NOW(), NOW()),
('Miguel Ángel', 'Ramos Chávez', '77889900', 'DNI', '910987654', 'miguel.ramos@email.com', 'Jr. Ayacucho 369, Breña', '1980-08-03', 'M', 1, NOW(), NOW());
```

## Estructura de la Base de Datos

### Tablas Principales
- **users**: Usuarios del sistema con roles
- **categorias**: Categorías de productos farmacéuticos
- **marcas**: Marcas de laboratorios
- **proveedores**: Proveedores de medicamentos
- **productos**: Inventario de productos farmacéuticos
- **clientes**: Base de datos de clientes
- **ventas**: Registro de transacciones de venta
- **detalle_ventas**: Detalles de productos vendidos

### Características Especiales
- Fechas de vencimiento obligatorias para productos
- Control de lotes por producto
- Sistema de alertas automáticas
- Auditoría de cambios en stock
- Generación automática de códigos de ticket

## Tecnologías Utilizadas

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Bootstrap 5 + jQuery
- **Base de Datos**: MySQL / SQLite
- **Iconos**: Bootstrap Icons
- **Autenticación**: Laravel Auth nativo

## Comandos Útiles

```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders
php artisan db:seed

# Limpiar caché
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Generar key de aplicación
php artisan key:generate

# Crear usuario administrador (artisan tinker)
php artisan tinker
User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => Hash::make('password'), 'role' => 'administrador', 'activo' => true]);
```

## Pruebas

Para ejecutar las pruebas del sistema:

```bash
# Ejecutar todas las pruebas
php artisan test

# Ejecutar pruebas específicas
php artisan test --filter=ProductoTest
```

## Contribución

Este sistema fue desarrollado específicamente para Farmacia Magistral. Para cualquier modificación o mejora, seguir las mejores prácticas de Laravel y mantener la compatibilidad con los datos existentes.

## Soporte

Para soporte técnico o consultas sobre el sistema, contactar al equipo de desarrollo.

---

**Desarrollado con ❤️ para Farmacia Magistral**
*Sistema de Gestión Farmacéutica - Laravel Framework*
 
