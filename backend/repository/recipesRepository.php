<?php
require_once __DIR__ . '/../connection/GetCon.php';
require_once __DIR__ . '/../model/Recipe.php';
require_once __DIR__ . '/ingredientRepository.php';
require_once __DIR__ . '/stepRepository.php';

function findRecipeById(int $recipeId) : ?Recipe{
    try{
        $con = GetCon::getInstance()->returnCon();
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
        WHERE r.ID_Food_recipe = ?
        ORDER BY s.num_step';
    
        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, 'i', $recipeId);
        mysqli_stmt_execute($stmt);
    
        $result = mysqli_stmt_get_result($stmt);
    
        $recipe = null;
        $addedIngredients = [];
        $addedSteps = [];
    
        while ($row = mysqli_fetch_assoc($result)) {
            if ($recipe === null) {
                $recipe = Recipe::constructFromArray($row, true);
            }
    
            if ($row['ingredient_name'] !== null) {
                $ingredientKey = $row['ingredient_name'];
                if (!in_array($ingredientKey, $addedIngredients)) {
                    $recipe->setIngredients(
                        Ingredient::constructFromArray($row)
                    );
                    $addedIngredients[] = $ingredientKey;
                }
            }
    
            if ($row['num_step'] !== null) {
                $stepKey = $row['num_step'];
                if (!in_array($stepKey, $addedSteps)) {
                    $recipe->setSteps(
                        Step::constructFromArray($row)
                    );
                    $addedSteps[] = $stepKey;
                }
            }
        }
    
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
        
        return $recipe;
    } catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        
        error_log("Erro em findRecipeById: " . $e->getMessage());
        return null;
    }
}

function findRecipesByUserId(int $userId) : ?array{
    try{
        
        $con = GetCon::getInstance()->returnCon();
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
                $recipesData[$recipeId] = Recipe::constructFromArray($row, true);
                $addedIngredients[$recipeId] = [];
                $addedSteps[$recipeId] = [];
            }
    
            if ($row['ingredient_name'] !== null) {
                $ingredientKey = $row['ingredient_name'];
                if (!in_array($ingredientKey, $addedIngredients[$recipeId])) {
                    $recipesData[$recipeId]->setIngredients(
                        Ingredient::constructFromArray($row)
                    );
                    $addedIngredients[$recipeId][] = $ingredientKey;
                }
            }
    
            if ($row['num_step'] !== null) {
                $stepKey = $row['num_step'];
                if (!in_array($stepKey, $addedSteps[$recipeId])) {
                    $recipesData[$recipeId]->setSteps(
                        Step::constructFromArray($row)
                    );
                    $addedSteps[$recipeId][] = $stepKey;
                }
            }
        }
    
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
        
        return array_values($recipesData);
    } catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        
        error_log("Erro em findRecipesByUserId: " . $e->getMessage());
        return null;
    }
}

function setNewRecipe(int $userId, Recipe $recipe): ?bool{
    try{
        $con = GetCon::getInstance()->returnCon();
        $stmt = mysqli_stmt_init($con);

        $query = 'INSERT INTO food_recipes(ID_user, Recipe_name, Category, Portions, Rating, Favorite, Food_Image) VALUES(?,?,?,?,?,?,?)';

        $ID_user = $userId;
        $Recipe_name = $recipe->getName();
        $Category = $recipe->getCategory();
        $Portions = $recipe->getPortions();
        $Rating = $recipe->getRating();
        $Favourite = $recipe->getFavorite() ? 1 : 0;
        $Food_Image = $recipe->getImage();

        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, 'issidis', $ID_user, $Recipe_name, $Category, $Portions, $Rating, $Favourite, $Food_Image);
        if ($Food_Image !== null) {
            mysqli_stmt_send_long_data($stmt, 6, $Food_Image);
        }
        mysqli_stmt_execute($stmt);

        $recipeId = mysqli_insert_id($con);

        mysqli_stmt_close($stmt);

        foreach($recipe->getIngredients() as $ingredient){
            setNewIngredient($recipeId, $ingredient);
        } 

        foreach ($recipe->getSteps() as $step) {
            setNewStep($recipeId, $step);
        } 

        return true;
    } catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }

        error_log("Erro em setNewRecipe: " . $e->getMessage());
        return false;
    }
}

function updateRecipe(Recipe $recipe): ?bool{
    try{
        $con = GetCon::getInstance()->returnCon();
        $stmt = mysqli_stmt_init($con);

        $query = 'UPDATE food_recipes
        SET Recipe_name = ?, Category = ?, Portions = ?, Rating = ?, Favorite = ?, Food_image = ?
        WHERE ID_Food_recipe = ?';

        $ID_Food_recipe = $recipe->getIdRecipe();
        $Recipe_name = $recipe->getName();
        $Category = $recipe->getCategory();
        $Portions = $recipe->getPortions();
        $Rating = $recipe->getRating();
        $Favourite = $recipe->getFavorite() ? 1 : 0;
        $Food_Image = $recipe->getImage();
         
        $oldRecipe = findRecipeById($ID_Food_recipe);

        if (!$oldRecipe) return false;

        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, 'ssidisi', $Recipe_name, $Category, $Portions, $Rating, $Favourite, $Food_Image, $ID_Food_recipe);
        if ($Food_Image !== null) {
            mysqli_stmt_send_long_data($stmt, 5, $Food_Image);
        }
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        foreach($oldRecipe->getIngredients() as $oldIngredient){
            $find = false;
            foreach($recipe->getIngredients() as $newIngredient){
                if ($oldIngredient->getName() === $newIngredient->getName()) $find = true;
            }

            if(!$find) deleteIngredient($ID_Food_recipe, $oldIngredient->getName());
        }

        foreach($oldRecipe->getSteps() as $oldStep){
            $find = false;
            foreach($recipe->getSteps() as $newStep){
                if ($oldStep->getNumStep() === $newStep->getNumStep()) $find = true;
            }

            if(!$find) deleteStep($ID_Food_recipe, $oldStep->getNumStep());
        }

        $newIngredients = [];
        foreach($recipe->getIngredients() as $ingredient){
            $affectedRows = updateIngredient($ID_Food_recipe, $ingredient);

            if ($affectedRows == 0) $newIngredients[] = $ingredient;
        } 

        $newSteps = [];
        foreach($recipe->getSteps() as $step){
            $affectedRows = updateStep($ID_Food_recipe, $step); 

            if ($affectedRows == 0) $newSteps[] = $step;
        }

        foreach($newIngredients as $ingredient){
            setNewIngredient($ID_Food_recipe, $ingredient);
        }

        foreach($newSteps as $step){
            setNewStep($ID_Food_recipe, $step);
        }

        return true;
    } catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        
        error_log("Erro em updateRecipe: " . $e->getMessage());
        return false;
    }
}

function deleteRecipeById(int $idRecipe): ?bool{
    try{
        $con = GetCon::getInstance()->returnCon();
        $stmt = mysqli_stmt_init($con);

        $query = 'DELETE from food_recipes
        WHERE ID_Food_recipe = ?';

        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, 'i', $idRecipe);

        mysqli_execute($stmt);
        mysqli_stmt_close($stmt);

        return true;
    } catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        
        error_log("Erro em updateRecipe: " . $e->getMessage());
        return null;
    }
}

?>