import SECRET from "../../../config.js";
import { User } from "../models/User.js";

async function testRegister() {
  const user = new User(
    null,
    "caio",
    "caiok9",
    "caio@gmail.com",
    "2007-04-12",
    CryptoJS.MD5("senha123").toString(CryptoJS.enc.Hex),
    []
  );

  console.log("Dados do usuário:", user);

  var crypto = new JSEncrypt();
  crypto.setPublicKey(SECRET.SECRET_KEY);

  const encrypted = crypto.encrypt(JSON.stringify(user));

  const response = await fetch(
    "http://localhost/PUC/TDE/backend/index.php/register",
    {
      method: "POST",
      headers: {
        "X-API-KEY": SECRET.API_KEY,
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ info: encrypted }),
    }
  );

  const result = await response.json();
  console.log("Resposta do servidor:", result);
}

async function testLogin() {
  var emailNick = "carol";
  var password = CryptoJS.MD5("senha123").toString(CryptoJS.enc.Hex);
  console.log("Email/Nick:", emailNick);
  console.log("Password:", password);

  const loginData = {
    "email/nick": emailNick,
    password: password,
  };

  var crypto = new JSEncrypt();
  crypto.setPublicKey(SECRET.SECRET_KEY);

  const encrypted = crypto.encrypt(JSON.stringify(loginData));

  console.log("Dados criptografados:", encrypted);

  const response = await fetch(
    "http://localhost/PUC/TDE/backend/index.php/login",
    {
      method: "POST",
      headers: {
        "X-API-KEY": SECRET.API_KEY,
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ info: encrypted }),
    }
  );

  const result = await response.json();
  console.log("Resposta do servidor:", result);
}

window.testRegister = testRegister;
window.testLogin = testLogin;
