<?php

require_once __DIR__ . '/../connection/GetCon.php';
require_once __DIR__ . '/../model/Ingredient.php';

function setNewIngredient(int $recipeId, Ingredient $ingredient): ?bool{
    try{
        $con = GetCon::getInstance()->returnCon();

        $stmt = mysqli_stmt_init($con);
        $query = 'INSERT INTO ingredients(ID_Food_recipe, Ingredient_name, Quantity, Type_quantity, Avaible) VALUES(?, ?, ?, ?, ?)';

        mysqli_stmt_prepare($stmt, $query);

        $ingredientName = $ingredient->getName();
        $quantity = $ingredient->getQuantity();
        $typeQuantity = $ingredient->getTypeQuantity();
        $available = $ingredient->getAvaible() ? 1 : 0;

        mysqli_stmt_bind_param($stmt, 'isdsi', $recipeId, $ingredientName, $quantity, $typeQuantity, $available);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        return true;
    } catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        
        echo("Erro em setNewIngredient: " . $e->getMessage());
        return null;
    }
}

function updateIngredient(int $recipeId, Ingredient $ingredient) : ?int{
    try{
        $con = GetCon::getInstance()->returnCon();

        $stmt = mysqli_stmt_init($con);

        $query = 'UPDATE ingredients
        SET Quantity = ?, Type_quantity = ?, Avaible = ?
        WHERE ID_Food_recipe = ? and Ingredient_name = ?';

        mysqli_stmt_prepare($stmt, $query);
        
        $ID_Food_recipe = $recipeId;
        $Ingredient_name = $ingredient->getName();
        $Quantity = $ingredient->getQuantity();
        $Type_quantity = $ingredient->getTypeQuantity();
        $Avaible = $ingredient->getAvaible() ? 1 : 0;

        mysqli_stmt_bind_param($stmt, 'dsiis', $Quantity, $Type_quantity, $Avaible, $ID_Food_recipe, $Ingredient_name);
        mysqli_stmt_execute($stmt);

        $affectedRows = mysqli_stmt_affected_rows($stmt);

        mysqli_stmt_close($stmt);

        return $affectedRows;
    } catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        
        echo("Erro em updateIngredient: " . $e->getMessage());
        return null;
    }
}

function deleteIngredient(int $recipeId, string $ingredientName): ?bool{
    try{
        $con = GetCon::getInstance()->returnCon();

        $stmt = mysqli_stmt_init($con);

        $query = 'DELETE from ingredients
        WHERE ID_Food_recipe = ? and Ingredient_name = ?';

        mysqli_stmt_prepare($stmt, $query);

        mysqli_stmt_bind_param($stmt, 'is', $recipeId, $ingredientName);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        return true;
    } catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        
        echo("Erro em deleteIngredient: " . $e->getMessage());
        return null;
    }
}

?>