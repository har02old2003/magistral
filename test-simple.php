<?php
echo "Probando conexión a Azure MySQL...\n";

$host = 'databasefarma.mysql.database.azure.com';
$database = 'mysampledb';
$username = 'administrador';
$password = 'Har02old12/';

echo "Host: $host\n";
echo "Usuario: $username\n";
echo "Base de datos: $database\n";

try {
    $dsn = "mysql:host=$host;port=3306;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_CA => false,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    echo "✅ CONEXIÓN EXITOSA!\n";
    
    $stmt = $pdo->query("SELECT VERSION() as version");
    $result = $stmt->fetch();
    echo "Versión MySQL: " . $result['version'] . "\n";
    
    echo "\n📋 Bases de datos disponibles:\n";
    $stmt = $pdo->query("SHOW DATABASES");
    while ($row = $stmt->fetch()) {
        echo "  - " . $row[0] . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?> 