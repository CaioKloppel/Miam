<?php

require_once __DIR__ . '/../service/userService.php';
require_once __DIR__ . '/../encryption/encryption.php';

function userLogin(){
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if(isset($data['info'])){
        echo login($data['info']);
    } else echo json_encode(['success' => false, 'message' => 'user login information not provided']);
}

function userRegister(){
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if(isset($data['info'])){
        echo register($data['info']);
    } else echo json_encode(['success' => false, 'message' => 'user register information not provided']);
}

function getUser(){
    $input = $_GET['email'] ?? null;
    if($input){
        echo returnUser($input);
    } else echo json_encode(['success' => false, 'message' => 'user email not provided']);
}

?>
