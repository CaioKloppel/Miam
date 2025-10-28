<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

function getCon() {
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $port = $_ENV['DB_PORT'] ?? '3306';
    $dbname = $_ENV['DB_NAME'] ?? '';
    $user = $_ENV['DB_USER'] ?? '';
    $password = $_ENV['DB_PASSWORD'] ?? '';
    
    $con = mysqli_connect($host, $user, $password, $dbname, $port);
    
    if (!$con) {
        die(json_encode([
            'success' => false, 
            'message' => 'Erro de conexão: ' . mysqli_connect_error()
        ]));
    }
    
    mysqli_set_charset($con, "utf8mb4");
    
    return $con;
}
?>