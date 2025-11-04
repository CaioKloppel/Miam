<?php

require_once __DIR__ . '/../service/userService.php';

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
        echo registerUser($data['info']);
    } else echo json_encode(['success' => false, 'message' => 'user register information not provided']);
}

function getUser(){
    $email = $_GET['email'] ?? null;
    $password = $_GET['password'] ?? null;
    if($email and $password){
        echo returnUser($email, $password);
    } else echo json_encode(['success' => false, 'message' => 'information to return user not provided']);
}

?>
