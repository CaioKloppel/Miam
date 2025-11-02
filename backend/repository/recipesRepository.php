<?php
require_once __DIR__ . '/../connection/getCon.php';
require_once __DIR__ . '/../model/Recipe.php';

function findRecipesByUserId(int $userId) : array{
    $con = getCon();
    $stmt = mysqli_stmt_init($con);

    $query = 'SELECT  r.ID_Food_recipe, 
    r.Recipe_name as recipe_name, 
    r.category, 
    r.portions, 
    r.rating, 
    r.favorite, 
    r.Food_image,
    i.Ingredient_name as ingredient_name, 
    i.quantity as ingredient_quantity, 
    i.type_quantity as ingredient_type,
    i.Avaible as ingredient_available,
    s.num_step, 
    s.description as step_description
    FROM food_recipes r 
    LEFT JOIN ingredients i ON r.ID_Food_recipe = i.ID_Food_recipe 
    LEFT JOIN steps s ON r.ID_Food_recipe = s.ID_Food_recipe 
    WHERE r.ID_user = ?
    ORDER BY r.ID_Food_recipe, s.num_step';

    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $recipesData = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $recipeId = $row['ID_Food_recipe'];

        if (!isset($recipesData[$recipeId])) {
            $recipesData[$recipeId] = new Recipe(
                (int)$row['ID_Food_recipe'],
                $row['recipe_name'],
                $row['category'],
                (int)$row['portions'],
                (float)$row['rating'],
                (bool)$row['favorite'],
                $row['Food_image'],
                [],
                []
            );
            $addedIngredients[$recipeId] = [];
            $addedSteps[$recipeId] = [];
        }

        if ($row['ingredient_name'] !== null) {
            $ingredientKey = $row['ingredient_name'];
            if (!in_array($ingredientKey, $addedIngredients[$recipeId])) {
                $recipesData[$recipeId]->setIngredients(
                    new Ingredient(
                        $row['ingredient_name'],
                        (float)$row['ingredient_quantity'],
                        $row['ingredient_type'],
                        (bool)$row['ingredient_available']
                    )
                );
                $addedIngredients[$recipeId][] = $ingredientKey;
            }
        }

        if ($row['num_step'] !== null) {
            $stepKey = $row['num_step'];
            if (!in_array($stepKey, $addedSteps[$recipeId])) {
                $recipesData[$recipeId]->setSteps(
                    new Step(
                        (int)$row['num_step'],
                        $row['step_description']
                    )
                );
                $addedSteps[$recipeId][] = $stepKey;
            }
        }
    }

    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    mysqli_close($con);
    
    return array_values($recipesData);
}

?>