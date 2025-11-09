<?php

require_once __DIR__ . '/../service/recipesService.php';

function recipeRegister(): void{
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if(isset($data['info']) and isset($data['key'])) echo registerRecipe($data['info'], $data['key']);
    else echo json_encode(['success' => false, 'message' => 'recipe register information not provided']);
}

function recipeEdit(): void{
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if(isset($data['info']) and isset($data['key'])) echo editRecipe($data['info'], $data['key']);
    else echo json_encode(['success' => false, 'message' => 'recipe edit information not provided']);
}

function recipeDelete(): void{
    $recipeId = $_GET['recipeId'] ?? null;
    $key = $_GET['key'] ?? null;

    if($recipeId) echo deleteRecipe($recipeId, $key);
    else echo json_encode(['success' => false, 'message' => 'recipe delete information not provided']);
}

?>