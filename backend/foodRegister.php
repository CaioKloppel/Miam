<?php

    header('Content-Type: application/json');

    $idUser = $_POST['id'];
    $recipeName = $_POST['name'];
    $imgData = file_get_contents($_FILES['img']['tmp_name']);

    $con = mysqli_connect('localhost:3306', 'TDE', '12345', 'tde_prog_web');

    $stmt = mysqli_stmt_init($con);
    $query = 'insert into food_recipes(id_user, recipe_name, food_image) values(?,?,?)';

    mysqli_stmt_prepare($stmt, $query);

    mysqli_stmt_bind_param($stmt, 'isb', $idUser, $recipeName, $null);
    mysqli_stmt_send_long_data($stmt, 2, $imgData);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => "success", 'message' => 'Cadastrado']);
    } else {
        echo json_encode(['success' => "success", 'message' => mysqli_stmt_error($stmt)]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);


?>