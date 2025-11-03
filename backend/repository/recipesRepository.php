<?php
require_once __DIR__ . '/../connection/getCon.php';
require_once __DIR__ . '/../model/Recipe.php';

function findRecipesByUserId(int $userId) : ?array{
    try{
        
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
    } catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        if (isset($con)) {
            mysqli_close($con);
        }
        
        error_log("Erro em setNewUser: " . $e->getMessage());
        return null;
    }
}

function setNewRecipe(int $userId, Recipe $recipe) : bool{
    try{
        $con = getCon();
        $stmt = mysqli_stmt_init($con);

        $query = 'INSERT INTO food_recipes(ID_user, Recipe_name, Category, Portions, Rating, Favourite, Food_Image) VALUES(?,?,?,?,?,?,?)';

        $ID_user = $userId;
        $Recipe_name = $recipe->getName();
        $Category = $recipe->getCategory();
        $Portions = $recipe->getPortions();
        $Rating = $recipe->getRating();
        $Favourite = $recipe->getFavorite() ? 1 : 0;
        $Food_Image = $recipe->getImage();

        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, 'issidib', $ID_user, $Recipe_name, $Category, $Portions, $Rating, $Favourite, $Food_Image);
        if ($Food_Image !== null) {
            mysqli_stmt_send_long_data($stmt, 6, $Food_Image);
        }
        mysqli_stmt_execute($stmt);

        $recipeId = mysqli_insert_id($con);

        mysqli_stmt_close($stmt);

        $stmt2 = mysqli_stmt_init($con);
        $query2 = 'INSERT INTO ingredients(ID_Food_recipe, Ingredient_name, Quantity, Type_quantity, Avaible) VALUES(?, ?, ?, ?, ?)';

        mysqli_stmt_prepare($stmt2, $query2);

        foreach($recipe->getIngredients() as $ingredient){
            $ingredientName = $ingredient->getName();
            $quantity = $ingredient->getQuantity();
            $typeQuantity = $ingredient->getType();
            $available = $ingredient->getAvailable() ? 1 : 0;

            mysqli_stmt_bind_param($stmt2, 'isdsi', $recipeId, $ingredientName, $quantity, $typeQuantity, $available);
            mysqli_stmt_execute($stmt2);
        } mysqli_stmt_close($stmt2);

        $stmt3 = mysqli_stmt_init($con);
        $query3 = 'INSERT INTO steps(ID_Food_recipe, Num_step, Description) VALUES(?,?,?)';

        mysqli_stmt_prepare($stmt3, $query3);

        foreach ($recipe->getSteps() as $step) {
            $numStep = $step->getNumStep();
            $description = $step->getDescription();

            mysqli_stmt_bind_param($stmt3, 'iis', $recipeId, $numStep, $description);
            mysqli_stmt_execute($stmt3);
        } mysqli_stmt_close($stmt3);

        mysqli_close($con);

        return true;
    } catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        if (isset($con)) {
            mysqli_close($con);
        }
        
        error_log("Erro em setNewUser: " . $e->getMessage());
        return false;
    }
}

?>