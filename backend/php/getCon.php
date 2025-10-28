<?php
require_once __DIR__ . '/../../vendor/autoload.php';

// Carrega o .env a partir do diretório raiz do projeto
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$dbHost = $_ENV['DB_HOST'];
$senha = $_ENV['SENHA'];

echo $dbHost;
echo "\n";
echo $senha;
?>