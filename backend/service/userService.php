<?php

require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../repository/userRepository.php';
require_once __DIR__ . '/../encryption/encryption.php';
require_once __DIR__ . '/../repository/recipesRepository.php';

function login(string $dataMessage, string $dataKey): string{
    $decryptedKey = decryptDataAssymetric($dataKey);
    $decrypted = decryptDataSymmetric($dataMessage, $decryptedKey);
    
    if ($decrypted === false || $decrypted === null) {
        return encryptResponse(['success' => false, 'message' => 'failed to decrypt data'], $decryptedKey);
    }
    
    $infoUserLogin = json_decode($decrypted, true);

    if (checkUser($infoUserLogin['email/nick'],$infoUserLogin['password'])) return encryptResponse(['success' => true, 'message' => 'valid login'], $decryptedKey);
    else return encryptResponse(['success' => false, 'message' => 'incorret login information'], $decryptedKey);
}

function registerUser(string $dataMessage, string $dataKey): string{
    $decryptedKey = decryptDataAssymetric($dataKey);
    $decrypted = decryptDataSymmetric($dataMessage, $decryptedKey);
    
    if ($decrypted === false || $decrypted === null) {
        return encryptResponse(['success' => false, 'message' => 'failed to decrypt data'], $decryptedKey);
    }
    
    $infoUserRegister = json_decode($decrypted, true);
    
    $user = User::constructFromArray($infoUserRegister);

    if(setNewUser($user)) return encryptResponse(['success' => true, 'message' => 'user successfully registered'], $decryptedKey);
    else return encryptResponse(['success' => false, 'message' => 'failed to register user'], $decryptedKey);
     
}

function editUser(string $dataMessage, string $dataKey): string{
    $decryptedKey = decryptDataAssymetric($dataKey);
    $decrypted = decryptDataSymmetric($dataMessage, $decryptedKey);
    
    if ($decrypted === false || $decrypted === null) {
        return encryptResponse(['success' => false, 'message' => 'failed to decrypt data'], $decryptedKey);
    }

    $infoUserEdit = json_decode($decrypted, true);
    
    $user = User::constructFromArray($infoUserEdit);

    if(updateUser($user)) return encryptResponse(['success' => true, 'message' => 'user successfully edited'], $decryptedKey);
    else return encryptResponse(['success' => false, 'message' => 'failed to edit user'], $decryptedKey);
}

function returnUser(string $encryptEmail, string $encryptPassword, string $dataKey): string{ 
    $decryptedKey = decryptDataAssymetric($dataKey);
    $email = decryptDataSymmetric($encryptEmail, $decryptedKey);
    $password = decryptDataSymmetric($encryptPassword, $decryptedKey);

    $user = findUserByEmailOrNickAndPassword($email, $password);

    if($user){
        $recipes = findRecipesByUserId($user->getIdUser());
        $user->setAllRecipes($recipes ?? []);
        return encryptResponse(['success' => true, 'user' => $user], $decryptedKey);
    } else return encryptResponse(['success' => false, 'message' => 'failed to get user'], $decryptedKey);
    
}

function deleteUser(string $encryptEmail, string $encryptPassword, string $dataKey): string{
    $decryptedKey = decryptDataAssymetric($dataKey);
    $email = decryptDataSymmetric($encryptEmail, $decryptedKey);
    $password = decryptDataSymmetric($encryptPassword, $decryptedKey);

    if (deleteUserByEmailAndPassword($email, $password)) return encryptResponse(['sucess' => true, 'message' => 'user successfully deleted'], $decryptedKey);
    else return encryptResponse(['sucess' => false, 'message' => 'failed to deleted user'], $decryptedKey);
}

?>