export class User {
  constructor(idUser, name, nickname, email, birthDate, password, recipes) {
    this.idUser = idUser;
    this.name = name;
    this.nickname = nickname;
    this.email = email;
    this.birthDate = birthDate;
    this.password = password;
    this.recipes = recipes;
  }
}
