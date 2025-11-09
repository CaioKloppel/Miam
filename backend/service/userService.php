<?php

require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../repository/userRepository.php';
require_once __DIR__ . '/../encryption/encryption.php';
require_once __DIR__ . '/../repository/recipesRepository.php';

function login(string $dataMessage, string $dataKey): string{
    $decryptedKey = decryptDataAssymetric($dataKey);
    $decrypted = decryptDataSymmetric($dataMessage, $decryptedKey);
    
    if ($decrypted === false || $decrypted === null) {
        return encryptResponse(['success' => false, 'message' => 'failed to decrypt data']);
    }
    
    $infoUserLogin = json_decode($decrypted, true);

    if (checkUser($infoUserLogin['email/nick'],$infoUserLogin['password'])) return encryptResponse(['success' => true, 'message' => 'valid login']);
    else return encryptResponse(['success' => false, 'message' => 'incorret login information']);
}

function registerUser(string $dataMessage, string $dataKey): string{
    $decryptedKey = decryptDataAssymetric($dataKey);
    $decrypted = decryptDataSymmetric($dataMessage, $decryptedKey);
    
    if ($decrypted === false || $decrypted === null) {
        return encryptResponse(['success' => false, 'message' => 'failed to decrypt data']);
    }
    
    $infoUserRegister = json_decode($decrypted, true);
    
    $user = User::constructFromArray($infoUserRegister);

    if(setNewUser($user)) return encryptResponse(['success' => true, 'message' => 'user successfully registered']);
    else return encryptResponse(['success' => false, 'message' => 'failed to register user']);
     
}

function editUser(string $dataMessage, string $dataKey): string{
    $decryptedKey = decryptDataAssymetric($dataKey);
    $decrypted = decryptDataSymmetric($dataMessage, $decryptedKey);
    
    if ($decrypted === false || $decrypted === null) {
        return encryptResponse(['success' => false, 'message' => 'failed to decrypt data']);
    }

    $infoUserEdit = json_decode($decrypted, true);
    
    $user = User::constructFromArray($infoUserEdit);

    if(updateUser($user)) return encryptResponse(['success' => true, 'message' => 'user successfully edited']);
    else return encryptResponse(['success' => false, 'message' => 'failed to edit user']);
}

function returnUser(string $email, string $password): string{ 
    $user = findUserByEmailOrNickAndPassword($email, $password);

    if($user){
        $recipes = findRecipesByUserId($user->getIdUser());
        $user->setAllRecipes($recipes ?? []);
        return encryptResponse(['sucess' => true, 'user' => $user]);
    } else return encryptResponse(['success' => false, 'message' => 'failed to get user']);
    
}

function deleteUser(string $email, string $password): string{
    if (deleteUserByEmailAndPassword($email, $password)) return encryptResponse(['sucess' => true, 'message' => 'user successfully deleted']);
    else return encryptResponse(['sucess' => false, 'message' => 'failed to deleted user']);
}

?>