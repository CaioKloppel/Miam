<?php

require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../repository/userRepository.php';
require_once __DIR__ . '/../encryption/encryption.php';

/** 
function login(string $data){
    $infoUserLogin = json_decode(decryptData($data, $_ENV['SECRET_KEY_ENCRYPTION_DATA']), true);
    
    $user = findUserByEmailOrNick($infoUserLogin['email/nick']);

    if (isset($user)){
        if (isPasswordCorrect($user->getPassword(), $infoUserLogin['password'])) return json_encode(['success' => true, 'message' => 'valid login']);
        else return json_encode(['success' => false, 'message' => 'incorret password']);
    } else return json_encode(['success' => false, 'message' => 'user not found']);
}
*/

function login(string $data){
    $infoUserLogin = json_decode(decryptData($data), true);

    if (checkUser($infoUserLogin['email/nick'],$infoUserLogin['password'])) return json_encode(['success' => true, 'message' => 'valid login']);
    else return json_encode(['success' => false, 'message' => 'incorret login information']);
}

function register(string $data){
    $infoUserRegister = json_decode(decryptData($data), true);
    
    $user = new User(
        0,
        $infoUserRegister['name'],
        $infoUserRegister['nickname'],
        $infoUserRegister['email'],
        new DateTime($infoUserRegister['birthDate']),
        $infoUserRegister['password'],
        [],
        true
    );

    if(setNewUser($user)){
        return json_encode(['success' => true, 'message' => 'user successfully registered']);
    } else return json_encode(['success' => false, 'message' => 'failed to register user']);
     
}

function returnUser(string $data){
    //TODO 
}

?>