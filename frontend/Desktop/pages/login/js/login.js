import { passwordRegex } from "../../../utils/regex.js";
import SECRET from "../../../../../config.js";

const loginButton = document.getElementById("btn-login");
const loginForm = document.getElementById("form-login");

loginForm.addEventListener("input", (e) => {
  if (e.target.tagName === "INPUT") {
    e.target.setCustomValidity("");
  }
});

loginButton.addEventListener("click", async (e) => {
  e.preventDefault();

  const emailNick = loginForm.querySelector('[name="email/nick"]');
  const password = loginForm.querySelector('[name="password"]');

  // Limpar mensagens anteriores
  emailNick.setCustomValidity("");
  password.setCustomValidity("");

  // Validar campos
  if (!emailNick.value.trim()) {
    emailNick.setCustomValidity("Este campo é obrigatório");
    emailNick.reportValidity();
    return;
  }

  if (!password.value.trim()) {
    password.setCustomValidity("Este campo é obrigatório");
    password.reportValidity();
    return;
  }

  const formData = new FormData(loginForm);

  const encryptPassword = CryptoJS.MD5(formData.get("password")).toString(
    CryptoJS.enc.Hex
  );

  const loginData = {
    "email/nick": formData.get("email/nick"),
    password: encryptPassword,
  };

  const randomKey = CryptoJS.lib.WordArray.random(32).toString();

  const encryptedMessage = CryptoJS.AES.encrypt(
    JSON.stringify(loginData),
    randomKey
  ).toString();

  const publicKeyResponse = await fetch(
    "http://localhost/PUC/TDE/backend/index.php/publicKey",
    {
      method: "GET",
      headers: {
        "X-API-KEY": SECRET.API_KEY,
      },
    }
  );

  const publicKeyText = await publicKeyResponse.text();

  var crypto = new JSEncrypt();
  crypto.setPublicKey(publicKeyText);

  const encryptedKey = crypto.encrypt(randomKey);

  const response = await fetch(
    "http://localhost/PUC/TDE/backend/index.php/user/login",
    {
      method: "POST",
      headers: {
        "X-API-KEY": SECRET.API_KEY,
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        info: encryptedMessage,
        key: encryptedKey,
      }),
    }
  );

  const result = await response.json();

  const decrypted = CryptoJS.AES.decrypt(result.data, randomKey).toString(
    CryptoJS.enc.Utf8
  );

  const data = JSON.parse(decrypted);

  if (data.success) {
    localStorage.setItem("userPassword", loginData.password);
    localStorage.setItem("userEmailOrNick", loginData["email/nick"]);

    setTimeout(() => {
      window.location.href = "../recipepage/index.html";
    }, 1000);
  } else {
    alert(data.message);
    location.reload();
  }
});
