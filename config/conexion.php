<?php

session_start();

/* TODO: Inicio de Session */
class Conectar {
    protected $dbh;
    
    protected function Conexion() {
        try {
            // TODO: Cadena de Conexión para MySQL
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $port = $_ENV['DB_PORT'] ?? '3306';
            $database = $_ENV['DB_DATABASE'] ?? 'farmacia_magistral';
            $username = $_ENV['DB_USERNAME'] ?? 'root';
            $password = $_ENV['DB_PASSWORD'] ?? '';
            
            $conectar = $this->dbh = new PDO("mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4", $username, $password);
            $conectar->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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