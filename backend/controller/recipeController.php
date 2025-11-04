<?php

require_once __DIR__ . '/../service/recipesService.php';

function recipeRegister(){
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if(isset($data['info'])){
        echo registerRecipe($data['info']);
    } else echo json_encode(['success' => false, 'message' => 'recipe register information not provided']);
}

function recipeEdit(){
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if(isset($data['info'])){
        echo editRecipe($data['info']);
    } else echo json_encode(['success' => false, 'message' => 'recipe edit information not provided']);
}

?>