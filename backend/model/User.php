<?php

class User implements JsonSerializable{

    private int $idUser;
    private string $name;
    private string $nickname;
    private string $email;
    private DateTime $birthDate;
    private string $password;
    private array $recipes;

    public function __construct(int $idUser, string $name, string $nickname, string $email, DateTime $birthDate, string $password, array $recipes) {
        $this->idUser = $idUser;
        $this->name = $name;
        $this->nickname = $nickname;
        $this->email = $email;
        $this->birthDate = $birthDate;
        $this->password = $password;
        $this->recipes = $recipes;
    }

    public static function constructFromArray(array $userInfo, bool $mysql=false) : User{
        $user = new User(
            $mysql ? $userInfo['ID_user'] : 0,
            $userInfo['name'],
            $userInfo['nickname'],
            $userInfo['email'],
            $mysql ? new DateTime($userInfo['birth_date']) : new DateTime($userInfo['birthDate']),
            $userInfo['password'],
            []
        );

        return $user;
    }

    public function jsonSerialize(): mixed {
        return [
            'idUser' => $this->idUser,
            'name' => $this->name,
            'nickname' => $this->nickname,
            'email' => $this->email,
            'birthDate' => $this->birthDate->format('Y-m-d'),
            'recipes' => $this->recipes
        ];
    }

    public function getIdUser(): int {
        return $this->idUser;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getNickname(): string {
        return $this->nickname;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getBirthDate(): DateTime {
        return $this->birthDate;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getRecipes(): array {
        return $this->recipes;
    }

    public function setIdUser(int $idUser): void {
        $this->idUser = $idUser;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setNickname(string $nickname): void {
        $this->nickname = $nickname;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setBirthDate(DateTime $birthDate): void {
        $this->birthDate = $birthDate;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function setRecipes(Recipe $recipe): void {
        $this->recipes[] = $recipe;
    }

    public function setAllRecipes(array $recipes){
        $this->recipes = $recipes;
    }

}

?>