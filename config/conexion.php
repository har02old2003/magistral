<?php

session_start();

/* TODO: Inicio de Session */
class Conectar {
    protected $dbh;
    
    protected function Conexion() {
        try {
            // TODO: Cadena de Conexión para Azure MySQL
            $host = $_ENV['DB_HOST'] ?? 'databasefarma.mysql.database.azure.com';
            $port = $_ENV['DB_PORT'] ?? '3306';
            $database = $_ENV['DB_DATABASE'] ?? 'mysampledb';
            $username = $_ENV['DB_USERNAME'] ?? 'administrador';
            $password = $_ENV['DB_PASSWORD'] ?? '';
            
            // Configuración específica para Azure MySQL
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_SSL_CA => false, // Azure requiere SSL
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
            ];
            
            $conectar = $this->dbh = new PDO("mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4", $username, $password, $options);
            return $conectar;
        } catch (Exception $e) {
            /* TODO: En caso de error mostrar mensaje */
            print "Error Conexion BD: " . $e->getMessage() . "<br/>";
            die();
        }
    }
    
    public static function ruta() {
        $app_url = $_ENV['APP_URL'] ?? 'http://localhost';
        return $app_url . "/";
    }
    
    public static function ruta_base_menu() {
        $app_url = $_ENV['APP_URL'] ?? 'http://localhost';
        return $app_url . "/";
    }
} 