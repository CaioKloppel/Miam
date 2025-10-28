<?php
require_once __DIR__ . '/controller/Router.php';


$router = new Router('/PUC/TDE/backend/index.php');

$router->get('/', function() {
    echo 'teste';
});

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$router->dispatch($requestUri, $requestMethod);

?>