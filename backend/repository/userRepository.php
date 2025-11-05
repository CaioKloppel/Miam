<?php
require_once __DIR__ . '/../connection/getCon.php';
require_once __DIR__ . '/../model/User.php';

function findUserByEmailOrNickAndPassword(string $emailOrNick, string $password) : ?User {
    try{
        $con = GetCon::getInstance()->returnCon();
        $stmt = mysqli_stmt_init($con);
    
        $query = 'SELECT * FROM users WHERE (email = ? or nickname = ?) and password = ?';
    
        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, 'sss', $emailOrNick, $emailOrNick, $password);
        mysqli_stmt_execute($stmt);
    
        $result = mysqli_stmt_get_result($stmt);
    
        $row = mysqli_fetch_assoc($result);
    
        mysqli_free_result($result);
    
        mysqli_stmt_close($stmt);
    
        if (!$row) {
            return null;
        }
    
        return User::constructFromArray($row, true);
    } catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        
        echo("Erro em findUserByEmailOrNickAndPassword: " . $e->getMessage());
        return null;
    }
}

function checkUser(string $userEmailOrNick, string $password) : bool{
    try{
        $con = GetCon::getInstance()->returnCon();
        $stmt = mysqli_stmt_init($con);
    
        $query = 'SELECT * FROM users WHERE (email = ? or nickname = ?) and password = ?';
    
        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, 'sss', $userEmailOrNick, $userEmailOrNick, $password);
        mysqli_stmt_execute($stmt);
    
        $result = mysqli_stmt_get_result($stmt);
    
        $row = mysqli_fetch_assoc($result);
    
        mysqli_free_result($result);
    
        mysqli_stmt_close($stmt);
    
        if (!$row) {
            return false;
        } else return true;

    } catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        
        echo("Erro em checkUser: " . $e->getMessage());
        return false;
    }
}

function setNewUser(User $user) : bool {
    try {
        $con = GetCon::getInstance()->returnCon();
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

        return true;

    } catch (Exception $e) {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        
        echo("Erro em setNewUser: " . $e->getMessage());
        return false;
    }
}

function updateUser(User $user){
    try{
        $con = GetCon::getInstance()->returnCon();
        $stmt = mysqli_stmt_init($con);
        
        $query = 'UPDATE users SET
        name = ?, nickname = ?, password = ?
        where ID_user = ?';

        mysqli_stmt_prepare($stmt, $query);

        $ID_user = $user->getPassword();
        $name = $user->getName();
        $nickname = $user->getNickname();
        $password = $user->getPassword();

        mysqli_stmt_bind_param($stmt, 'sssi', $name, $nickname, $password, $ID_user);

        mysqli_stmt_execute($stmt);
        
        mysqli_stmt_close($stmt);

        return true;

    } catch (Exception $e){
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        
        echo("Erro em updateUser: " . $e->getMessage());
        return false;
    }

    function deleteUserByIdAndPassword(int $ID_user, string $password){
        try{
            $con = GetCon::getInstance()->returnCon();
            $stmt = mysqli_stmt_init($con);
            
            $query = 'DELETE from users WHERE ID_user = ? and password = ?';

            mysqli_stmt_prepare($stmt, $query);

            mysqli_stmt_bind_param($stmt, 'is', $ID_user, $password);

            mysqli_stmt_execute($stmt);
            
            $affectedRows = mysqli_stmt_affected_rows($stmt);

            mysqli_stmt_close($stmt);

            return $affectedRows > 0;

        } catch (Exception $e){
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        
        echo("Erro em deleteUser: " . $e->getMessage());
        return null;
    }
    }
}

?>