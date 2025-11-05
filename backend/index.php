<?php
require_once __DIR__ . '/connection/GetCon.php';
require_once __DIR__ . '/controller/Router.php';
require_once __DIR__ . '/controller/userController.php';
require_once __DIR__ . '/controller/recipeController.php';

header('Content-Type: application/json');

$router = new Router('/PUC/TDE/backend/index.php');


//users routes
$router->post('/user/login', function() {
    userLogin();
});

$router->post('/user/register', function() {
    userRegister();
});

$router->put('/user/edit', function() {
    userEdit();
});

$router->get('/user/return', function(){
    getUser();
});

$router->delete('/user/delete', function() {
    userDelete();
});

//recipes routes
$router->post('/recipe/register', function() {
    recipeRegister();
});

$router->put('/recipe/edit', function() {
    recipeEdit();
});

$router->delete('/recipe/delete', function() {
    recipeDelete();
});


$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];
$apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';

$router->dispatch($requestUri, $requestMethod, $apiKey);

GetCon::getInstance()->closeCon();

?>