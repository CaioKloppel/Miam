<?php

require_once __DIR__ . '/../service/userService.php';

function userLogin(): void{
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if(isset($data['info'])) echo login($data['info']);
    else echo json_encode(['success' => false, 'message' => 'user login information not provided']);
}

function userRegister(): void{
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if(isset($data['info'])) echo registerUser($data['info']);
    else echo json_encode(['success' => false, 'message' => 'user register information not provided']);
}

function userEdit(): void{
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if(isset($data['info'])) echo editUser($data['info']);
    else echo json_encode(['success' => false, 'message' => 'user edit information not provided']);
}

function getUser(): void{
    $email = $_GET['email'] ?? null;
    $password = $_GET['password'] ?? null;
    if($email and $password) echo returnUser($email, $password);
    else echo json_encode(['success' => false, 'message' => 'information to return user not provided']);
}

function userDelete(): void{
    $email = $_GET['email'] ?? null;
    $password = $_GET['password'] ?? null;
    if($email and $password) echo deleteUser($email, $password);
    else echo json_encode(['success' => false, 'message' => 'information to delete user not provided']);
}


?>
