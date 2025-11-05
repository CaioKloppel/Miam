<?php

require_once __DIR__ . '/../connection/getCon.php';
require_once __DIR__ . '/../model/Step.php';

function setNewStep(int $recipeId, Step $step) {
    try{
        $con = GetCon::getInstance()->returnCon();

        $stmt = mysqli_stmt_init($con);
        $query = 'INSERT INTO steps(ID_Food_recipe, Num_step, Description) VALUES(?,?,?)';

        mysqli_stmt_prepare($stmt, $query);
        
        $numStep = $step->getNumStep();
        $description = $step->getDescription();

        mysqli_stmt_bind_param($stmt, 'iis', $recipeId, $numStep, $description);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
    }catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        
        echo("Erro em setNewStep: " . $e->getMessage());
        return null;
    }
    
}

function updateStep(int $recipeId, Step $step) : ?int{
    try{
        $con = GetCon::getInstance()->returnCon();

        $stmt = mysqli_stmt_init($con);

        $query = 'UPDATE steps
        SET Description = ?
        WHERE ID_Food_recipe = ? and Num_step = ?';

        mysqli_stmt_prepare($stmt, $query);

        $ID_Food_recipe = $recipeId;
        $Num_step = $step->getNumStep();
        $Description = $step->getDescription();

        mysqli_stmt_bind_param($stmt, 'sii', $Description, $ID_Food_recipe, $Num_step);

        mysqli_stmt_execute($stmt);
        $affectedRows = mysqli_stmt_affected_rows($stmt);

        mysqli_stmt_close($stmt);

        return $affectedRows;
    }catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        
        echo("Erro em updateStep: " . $e->getMessage());
        return null;
    }
}

function deleteStep(int $recipeId, int $numStep){
    try{
        $con = GetCon::getInstance()->returnCon();

        $stmt = mysqli_stmt_init($con);

        $query = 'DELETE from steps
        WHERE ID_Food_recipe = ? and Num_step = ?';

        mysqli_stmt_prepare($stmt, $query);

        mysqli_stmt_bind_param($stmt, 'ii', $ID_Food_recipe, $Num_step);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        return true;
    }catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        
        echo("Erro em deleteStep: " . $e->getMessage());
        return null;
    }
}

?>