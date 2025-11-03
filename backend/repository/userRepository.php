<?php
require_once __DIR__ . '/../connection/getCon.php';
require_once __DIR__ . '/../model/User.php';

function findUserByEmailOrNickAndPassword(string $emailOrNick, string $password) : ?User {
    try{
        $con = getCon();
        $stmt = mysqli_stmt_init($con);
    
        $query = 'SELECT * FROM users WHERE (email = ? or nickname = ?) and password = ?';
    
        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, 'sss', $emailOrNick, $emailOrNick, $password);
        mysqli_stmt_execute($stmt);
    
        $result = mysqli_stmt_get_result($stmt);
    
        $row = mysqli_fetch_assoc($result);
    
        mysqli_free_result($result);
    
        mysqli_stmt_close($stmt);
        mysqli_close($con);
    
        if (!$row) {
            return null;
        }
    
        return new User(
            (int)$row['ID_user'],
            $row['name'],
            $row['nickname'],
            $row['email'],
            new DateTime($row['birth_date']),
            $row['password'],
            []
        );
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

function checkUser(string $userEmailOrNick, string $password) : bool{
    try{
        $con = getCon();
        $stmt = mysqli_stmt_init($con);
    
        $query = 'SELECT * FROM users WHERE (email = ? or nickname = ?) and password = ?';
    
        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, 'sss', $userEmailOrNick, $userEmailOrNick, $password);
        mysqli_stmt_execute($stmt);
    
        $result = mysqli_stmt_get_result($stmt);
    
        $row = mysqli_fetch_assoc($result);
    
        mysqli_free_result($result);
    
        mysqli_stmt_close($stmt);
        mysqli_close($con);
    
        if (!$row) {
            return false;
        } else return true;

    } catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        if (isset($con)) {
            mysqli_close($con);
        }
        
        error_log("Erro em checkUser: " . $e->getMessage());
        return false;
    }
}

function setNewUser(User $user) : bool {
    try {
        $con = getCon();
        $stmt = mysqli_stmt_init($con);

        $query = 'INSERT INTO users(name, nickname, email, birth_date, password) values(?,?,?,?,?)';

        mysqli_stmt_prepare($stmt, $query);

        $name = $user->getName();
        $nickname = $user->getNickname();
        $email = $user->getEmail();
        $birthDate = $user->getBirthDate()->format('Y-m-d');
        $password = $user->getPassword();

        mysqli_stmt_bind_param($stmt, 'sssss', $name, $nickname, $email, $birthDate, $password);

        mysqli_stmt_execute($stmt);
        
        mysqli_stmt_close($stmt);
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