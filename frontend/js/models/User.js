export class User {
  constructor(idUser, name, nickname, email, password, recipes) {
    this.idUser = idUser;
    this.name = name;
    this.nickname = nickname;
    this.email = email;
    this.password = password;
    this.recipes = recipes;
  }
}
