<?php
/**
 * Script de prueba para conexión a Azure Database for MySQL
 * Ejecutar: php test-azure-connection.php
 */

echo "🔍 Probando conexión a Azure Database for MySQL...\n\n";

// Configuración de la base de datos
$host = 'databasefarma.mysql.database.azure.com';
$port = '3306';
$database = 'farmacia_magistral'; // Cambia esto por el nombre real de tu BD
$username = 'administrador';
$password = ''; // INGRESA TU CONTRASEÑA AQUÍ

echo "📋 Configuración:\n";
echo "Host: {$host}\n";
echo "Puerto: {$port}\n";
echo "Base de datos: {$database}\n";
echo "Usuario: {$username}\n";
echo "Contraseña: " . (empty($password) ? "⚠️  NO CONFIGURADA" : "✅ Configurada") . "\n\n";

if (empty($password)) {
    echo "❌ ERROR: Debes configurar la contraseña en la línea 12 de este archivo.\n";
    exit(1);
}

try {
    echo "🔗 Intentando conectar...\n";
    
    // Configuración de opciones PDO para Azure
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_SSL_CA => null,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::ATTR_TIMEOUT => 30
    ];
    
    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, $options);
    
    echo "✅ ¡CONEXIÓN EXITOSA!\n\n";
    
    // Probar una consulta simple
    echo "🧪 Probando consulta...\n";
    $stmt = $pdo->query("SELECT VERSION() as version, DATABASE() as database_name");
    $result = $stmt->fetch();
    
    echo "📊 Información del servidor:\n";
    echo "Versión MySQL: " . $result['version'] . "\n";
    echo "Base de datos actual: " . $result['database_name'] . "\n\n";
    
    // Verificar tablas existentes
    echo "📋 Verificando tablas...\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "✅ Tablas encontradas (" . count($tables) . "):\n";
        foreach ($tables as $table) {
            echo "  - {$table}\n";
        }
    } else {
        echo "⚠️  No se encontraron tablas. Quizás necesites ejecutar las migraciones.\n";
    }
    
    echo "\n🎉 ¡Conexión a Azure Database verificada exitosamente!\n";
    
} catch (PDOException $e) {
    echo "❌ ERROR DE CONEXIÓN:\n";
    echo "Código: " . $e->getCode() . "\n";
    echo "Mensaje: " . $e->getMessage() . "\n\n";
    
    echo "🔧 Posibles soluciones:\n";
    echo "1. Verificar que la contraseña sea correcta\n";
    echo "2. Verificar que el nombre del servidor sea exacto\n";
    echo "3. Verificar que el nombre de la base de datos exista\n";
    echo "4. Verificar las reglas de firewall en Azure\n";
    echo "5. Verificar que tu IP esté permitida en Azure\n";
    
} catch (Exception $e) {
    echo "❌ ERROR GENERAL:\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
}

echo "\n📝 Notas:\n";
echo "- Este script usa la misma configuración que tu clase Conectar\n";
echo "- Si funciona aquí, debería funcionar en tu aplicación\n";
echo "- Recuerda configurar las mismas variables en Azure App Service\n";
?> 