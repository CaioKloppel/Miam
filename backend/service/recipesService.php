<?php

require_once __DIR__ . '/../model/Recipe.php';
require_once __DIR__ . '/../repository/recipesRepository.php';
require_once __DIR__ . '/../encryption/encryption.php';

function registerRecipe(string $dataMessage, string $dataKey): string{
    $decryptedKey = decryptDataAssymetric($dataKey);
    $decrypted = decryptDataSymmetric($dataMessage, $decryptedKey);
    
    if ($decrypted === false || $decrypted === null) {
        return encryptResponse(['success' => false, 'message' => 'failed to decrypt data'], $decryptedKey);
    }

    $infoRecipeRegister = json_decode($decrypted, true);

    $userId = (int)$infoRecipeRegister['userId'];
    
    $recipe = Recipe::constructFromArray($infoRecipeRegister['recipe']);

    if(setNewRecipe($userId, $recipe)){
        return encryptResponse(['success' => true, 'message' => 'recipe successfully registered'], $decryptedKey);
    } else return encryptResponse(['success' => false, 'message' => 'failed to register recipe'], $decryptedKey);

}

function editRecipe(string $dataMessage, string $dataKey): string{
    $decryptedKey = decryptDataAssymetric($dataKey);
    $decrypted = decryptDataSymmetric($dataMessage, $decryptedKey);
    
    if ($decrypted === false || $decrypted === null) {
        return encryptResponse(['success' => false, 'message' => 'failed to decrypt data'], $decryptedKey);
    }

    $infoRecipeEdit = json_decode($decrypted, true);

    $recipe = Recipe::constructFromArray($infoRecipeEdit);

    if(updateRecipe($recipe)){
        return encryptResponse(['success' => true, 'message' => 'recipe successfully edited'], $decryptedKey);
    } else return encryptResponse(['success' => false, 'message' => 'failed to edit recipe'], $decryptedKey);
}

function deleteRecipe(string $encryptIdRecipe, string $dataKey): string{
    $decryptedKey = decryptDataAssymetric($dataKey);
    $idRecipe = (int)decryptDataSymmetric((string)$encryptIdRecipe, $decryptedKey);

    if (deleteRecipeById($idRecipe)) return encryptResponse(['success' => true, 'message' => 'recipe successfully deleted'], $decryptedKey);
    else return encryptResponse(['success' => false, 'message' => 'failed to delete recipe'], $decryptedKey);
}

?>