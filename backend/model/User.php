<?php

class User{

    private $idUser;
    private $name;
    private $nickname;
    private $email;
    private $birthDate;
    private $password;
    private $recipes;

    public function __construct($idUser, $name, $nickname, $email, $birthDate, $password, $recipes)
    {
        $this->idUser = $idUser;
        $this->name = $name;
        $this->nickname = $nickname;
        $this->email = $email;
        $this->birthDate = $birthDate;
        $this->password = $password;
        $this->recipes = $recipes;
    }

    public function getIdUser() {
        return $this->idUser;
    }

    public function getName() {
        return $this->name;
    }

    public function getNickname() {
        return $this->nickname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getBirthDate() {
        return $this->birthDate;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRecipes() {
        return $this->recipes;
    }

    public function setIdUser($idUser) {
        $this->idUser = $idUser;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setNickname($nickname) {
        $this->nickname = $nickname;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setBirthDate($birthDate) {
        $this->birthDate = $birthDate;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setRecipes($recipes) {
        $this->recipes = $recipes;
    }

}

?>