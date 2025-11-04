<?php
require_once __DIR__ . '/connection/getCon.php';
require_once __DIR__ . '/controller/Router.php';
require_once __DIR__ . '/controller/userController.php';
require_once __DIR__ . '/controller/recipeController.php';

header('Content-Type: application/json');

$router = new Router('/PUC/TDE/backend/index.php');

$router->get('/', function() {
    echo 'teste';
});

$router->get('/teste2', function() {
    echo 'teste2';
});

$router->get('/user/return', function(){
    getUser();
});

$router->post('/user/login', function() {
    userLogin();
});

$router->post('/user/register', function() {
    userRegister();
});

$router->post('/recipe/register', function() {
    recipeRegister();
});

$router->post('/recipe/edit', function() {
    recipeEdit();
});

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];
$apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';

$router->dispatch($requestUri, $requestMethod, $apiKey);

GetCon::getInstance()->closeCon();

?>