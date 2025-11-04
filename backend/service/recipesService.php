<?php

require_once __DIR__ . '/../model/Recipe.php';
require_once __DIR__ . '/../repository/recipesRepository.php';
require_once __DIR__ . '/../encryption/encryption.php';

function registerRecipe(string $data){
    $infoRecipeRegister = json_decode(decryptData($data), true);

    $userId = (int)$infoRecipeRegister['userId'];
    
    $recipe = Recipe::constructFromArray($infoRecipeRegister['recipe']);

    if(setNewRecipe($userId, $recipe)){
        return json_encode(['success' => true, 'message' => 'recipe successfully registered']);
    } else return json_encode(['success' => false, 'message' => 'failed to register recipe']);

}

function editRecipe(string $data){
    $infoRecipeEdit = json_decode(decryptData($data), true);

    $recipe = Recipe::constructFromArray($infoRecipeEdit);

    if(updateRecipe($recipe)){
        return json_encode(['success' => true, 'message' => 'recipe successfully edited']);
    } else return json_encode(['success' => false, 'message' => 'failed to edit recipe']);
}

?>