<?php
require_once __DIR__ . '/controller/Router.php';
require_once __DIR__ . '/controller/userController.php';

header('Content-Type: application/json');

$router = new Router('/PUC/TDE/backend/index.php');

$router->get('/', function() {
    echo 'teste';
});

$router->get('/teste2', function() {
    echo 'teste2';
});

$router->post('/login', function() {
    userLogin();
});

$router->post('/register', function() {
    userRegister();
});

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];
$apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';

$router->dispatch($requestUri, $requestMethod, $apiKey);

?>