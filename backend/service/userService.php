<?php

require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../repository/userRepository.php';
require_once __DIR__ . '/../encryption/encryption.php';
require_once __DIR__ . '/../repository/recipesRepository.php';

function login(string $data): string{
    $decrypted = decryptData($data);
    
    if ($decrypted === false || $decrypted === null) {
        return json_encode(['success' => false, 'message' => 'failed to decrypt data']);
    }
    
    $infoUserLogin = json_decode($decrypted, true);

    if (checkUser($infoUserLogin['email/nick'],$infoUserLogin['password'])) return json_encode(['success' => true, 'message' => 'valid login']);
    else return json_encode(['success' => false, 'message' => 'incorret login information']);
}

function registerUser(string $data): string{
    $decrypted = decryptData($data);
    
    if ($decrypted === false || $decrypted === null) {
        return json_encode(['success' => false, 'message' => 'failed to decrypt data']);
    }
    
    $infoUserRegister = json_decode($decrypted, true);
    
    $user = User::constructFromArray($infoUserRegister);

    if(setNewUser($user)) return json_encode(['success' => true, 'message' => 'user successfully registered']);
    else return json_encode(['success' => false, 'message' => 'failed to register user']);
     
}

function editUser($data): string{
    $decrypted = decryptData($data);
    
    if ($decrypted === false || $decrypted === null) {
        return json_encode(['success' => false, 'message' => 'failed to decrypt data']);
    }

    $infoUserEdit = json_decode($decrypted, true);
    
    $user = User::constructFromArray($infoUserEdit);

    if(updateUser($user)) return json_encode(['success' => true, 'message' => 'user successfully edited']);
    else return json_encode(['success' => false, 'message' => 'failed to edit user']);
}

function returnUser(string $email, string $password): string{ 
    $user = findUserByEmailOrNickAndPassword($email, $password);

    if($user){
        $recipes = findRecipesByUserId($user->getIdUser());
        $user->setAllRecipes($recipes ?? []);
        return json_encode(['sucess' => true, 'user' => $user]);
    } else return json_encode(['success' => false, 'message' => 'failed to get user']);
    
}

function deleteUser(int $idUser, string $password): string{
    if (deleteUserByIdAndPassword($idUser, $password)) return json_encode(['sucess' => true, 'message' => 'user successfully deleted']);
    else return json_encode(['sucess' => false, 'message' => 'failed to deleted user']);
}

?>