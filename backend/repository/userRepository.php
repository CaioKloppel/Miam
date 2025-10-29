<?php
require_once __DIR__ . '/../connection/getCon.php';
require_once __DIR__ . '/../model/User.php';

function findUserByEmailOrNick(string $userInfo) : ?User {
    $con = getCon();
    $stmt = mysqli_stmt_init($con);

    $query = `select * from users where email = ? or nickname = ?`;

    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $userInfo, $userInfo);
    $result = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);
    mysqli_close($con);

    if (!$row) {
        return null;
    }

    return new User(
        (int)$row['id_user'],
        $row['name'],
        $row['nickname'],
        $row['email'],
        new DateTime($row['birth_date']),
        $row['password'],
        [],
        false
    );
}

function setNewUser(User $user) : bool {
    $con = getCon();
    $stmt = mysqli_stmt_init($con);

    $query = `insert into users(name, nickname, email, birth_date, password) values(?,?,?,?,?)`;

    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, 'sssss', $user->getName(), $user->getNickname(), $user->getEmail(), $user->getBirthDate()->format('Y-m-d'), $user->getPassword());

    $success = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($con);

    return $success;

}

?>