import { emailRegex, passwordRegex } from "../../../utils/regex.js";
import { User } from "../../../models/User.js";
import SECRET from "../../../../../config.js";

const registerButton = document.getElementById("btn-register");
const registerForm = document.getElementById("form-register");

registerForm.addEventListener("input", (e) => {
  if (e.target.tagName === "INPUT") {
    e.target.setCustomValidity("");
  }
});

registerButton.addEventListener("click", async (e) => {
  e.preventDefault();

  const name = registerForm.querySelector('[name="name"]');
  const email = registerForm.querySelector('[name="email"]');
  const password = registerForm.querySelector('[name="password"]');
  const nick = registerForm.querySelector('[name="nick"]');
  const birthDate = registerForm.querySelector('[name="birthDate"]');

  name.setCustomValidity("");
  email.setCustomValidity("");
  password.setCustomValidity("");
  nick.setCustomValidity("");
  birthDate.setCustomValidity("");

  if (!name.value.trim()) {
    name.setCustomValidity("Este campo é obrigatório");
    name.reportValidity();
    return;
  }

  if (!email.value.trim()) {
    email.setCustomValidity("Este campo é obrigatório");
    email.reportValidity();
    return;
  }

  if (!emailRegex.test(email.value.trim())) {
    email.setCustomValidity("Por favor, insira um email válido");
    email.reportValidity();
    return;
  }

  if (!password.value.trim()) {
    password.setCustomValidity("Este campo é obrigatório");
    password.reportValidity();
    return;
  }

  if (!passwordRegex.test(password.value)) {
    password.setCustomValidity(
      "A senha deve conter no mínimo 6 caracteres, 1 letra maiúscula, 1 minúscula e 1 número e um carectere especial (@, $, !, %, *, ?, &, #)"
    );
    password.reportValidity();
    return;
  }

  if (!nick.value.trim()) {
    nick.setCustomValidity("Este campo é obrigatório");
    nick.reportValidity();
    return;
  }

  if (!nick.value.trim()) {
    nick.setCustomValidity("Este campo é obrigatório");
    nick.reportValidity();
    return;
  }

  const formData = new FormData(registerForm);

  const newUser = new User(
    null,
    formData.get("name"),
    formData.get("nick"),
    formData.get("email"),
    formData.get("birthDate"),
    CryptoJS.MD5(formData.get("password")).toString(CryptoJS.enc.Hex),
    []
  );

  const randomKey = CryptoJS.lib.WordArray.random(32).toString();

  const encryptedMessage = CryptoJS.AES.encrypt(
    JSON.stringify(newUser),
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
    "http://localhost/PUC/TDE/backend/index.php/user/register",
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
    localStorage.setItem("userPassword", newUser.password);
    localStorage.setItem("userEmailOrNick", newUser.email);

    setTimeout(() => {
      window.location.href = "../recipepage/index.html";
    }, 1000);
  } else {
    alert(`${data.message}: email ou apelido já utilizado`);
    location.reload();
  }
});
