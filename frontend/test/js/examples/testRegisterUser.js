import SECRET from "/PUC/TDE/config.js";
import { User } from "../../../Desktop/models/User.js";
import { Recipe } from "../../../Desktop/models/Recipe.js";
import { Ingredient } from "../../../Desktop/models/Ingredient.js";
import { Step } from "../../../Desktop/models/Step.js";

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

  const randomKey = CryptoJS.lib.WordArray.random(32).toString();

  const encryptedMessage = CryptoJS.AES.encrypt(
    JSON.stringify(user),
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

  try {
    console.log("Resultado bruto:", result);

    if (result.success !== undefined) {
      console.log("Resposta do servidor (sem criptografia):", result);
      return;
    }

    if (!result.data) {
      console.error("Erro: result.data não existe!");
      console.log("Result completo:", result);
      return;
    }

    const decrypted = CryptoJS.AES.decrypt(result.data, randomKey);
    const decryptedString = decrypted.toString(CryptoJS.enc.Utf8);

    if (!decryptedString) {
      console.error("Erro: decryptedString está vazio!");
      return;
    }

    const data = JSON.parse(decryptedString);
    console.log("Resposta do servidor:", data);
  } catch (e) {
    console.error("Erro ao descriptografar:", e);
    console.log("Result bruto:", result);
  }
}

async function testLogin() {
  var emailNick = "caio@gmail.com";
  var password = CryptoJS.MD5("senha123").toString(CryptoJS.enc.Hex);
  console.log("email/nick:", emailNick);
  console.log("password:", password);

  const loginData = {
    "email/nick": emailNick,
    password: password,
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

  try {
    console.log("Resultado bruto:", result);

    if (result.success !== undefined) {
      console.log("Resposta do servidor (sem criptografia):", result);
      return;
    }

    if (!result.data) {
      console.error("Erro: result.data não existe!");
      console.log("Result completo:", result);
      return;
    }

    const decrypted = CryptoJS.AES.decrypt(result.data, randomKey);
    const decryptedString = decrypted.toString(CryptoJS.enc.Utf8);

    if (!decryptedString) {
      console.error("Erro: decryptedString está vazio!");
      return;
    }

    const data = JSON.parse(decryptedString);
    console.log("Resposta do servidor:", data);
  } catch (e) {
    console.error("Erro ao descriptografar:", e);
    console.log("Result bruto:", result);
  }
}

async function testUser() {
  var email = "caio@gmail.com";
  var password = CryptoJS.MD5("senha123").toString(CryptoJS.enc.Hex);

  const randomKey = CryptoJS.lib.WordArray.random(32).toString();

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

  const encryptedEmail = CryptoJS.AES.encrypt(email, randomKey).toString();
  const encryptedPassword = CryptoJS.AES.encrypt(
    password,
    randomKey
  ).toString();
  const encryptedKey = crypto.encrypt(randomKey);

  const response = await fetch(
    `http://localhost/PUC/TDE/backend/index.php/user/return?email=${encodeURIComponent(
      encryptedEmail
    )}&password=${encodeURIComponent(
      encryptedPassword
    )}&key=${encodeURIComponent(encryptedKey)}`,
    {
      method: "GET",
      headers: {
        "X-API-KEY": SECRET.API_KEY,
      },
    }
  );

  const result = await response.json();

  try {
    console.log("Resultado bruto:", result);

    if (result.success !== undefined) {
      console.log("Resposta do servidor (sem criptografia):", result);
      return;
    }

    if (!result.data) {
      console.error("Erro: result.data não existe!");
      console.log("Result completo:", result);
      return;
    }

    const decrypted = CryptoJS.AES.decrypt(result.data, randomKey);
    const decryptedString = decrypted.toString(CryptoJS.enc.Utf8);

    if (!decryptedString) {
      console.error("Erro: decryptedString está vazio!");
      return;
    }

    const data = JSON.parse(decryptedString);
    console.log("Resposta do servidor:", data);
  } catch (e) {
    console.error("Erro ao descriptografar:", e);
    console.log("Result bruto:", result);
  }
}

async function testRegisterRecipe() {
  // Criar ingredientes de exemplo
  const ingredients = [
    new Ingredient("Farinha de trigo", 500, "g", true),
    new Ingredient("Açúcar", 200, "g", true),
    new Ingredient("Ovos", 3, "unit", true),
    new Ingredient("Leite", 250, "ml", true),
    new Ingredient("Manteiga", 100, "g", true),
  ];

  // Criar passos de exemplo
  const steps = [
    new Step(1, "Em uma tigela, misture a farinha e o açúcar"),
    new Step(2, "Adicione os ovos e bata bem"),
    new Step(3, "Acrescente o leite aos poucos, mexendo sempre"),
    new Step(4, "Por último, adicione a manteiga derretida"),
    new Step(5, "Despeje em uma forma untada e asse por 40 minutos a 180°C"),
  ];

  // Criar receita de exemplo
  const recipe = new Recipe(
    null, // idRecipe (será gerado pelo backend)
    "Bolo Simples de Farinha",
    "dessert",
    8, // porções
    0, // rating inicial
    false, // não é favorito ainda
    null, // URL da imagem
    steps,
    ingredients
  );

  console.log("Dados da receita:", recipe);

  const registerRecipe = {
    userId: 16,
    recipe: recipe,
  };

  const randomKey = CryptoJS.lib.WordArray.random(32).toString();

  const encryptedMessage = CryptoJS.AES.encrypt(
    JSON.stringify(registerRecipe),
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
    "http://localhost/PUC/TDE/backend/index.php/recipe/register",
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

  try {
    console.log("Resultado bruto:", result);

    if (result.success !== undefined) {
      console.log("Resposta do servidor (Register Recipe):", result);
      return result;
    }

    if (!result.data) {
      console.error("Erro: result.data não existe!");
      console.log("Result completo:", result);
      return null;
    }

    const decrypted = CryptoJS.AES.decrypt(result.data, randomKey);
    const decryptedString = decrypted.toString(CryptoJS.enc.Utf8);

    if (!decryptedString) {
      console.error("Erro: decryptedString está vazio!");
      return null;
    }

    const data = JSON.parse(decryptedString);
    console.log("Resposta do servidor (Register Recipe):", data);
    return data;
  } catch (e) {
    console.error("Erro ao descriptografar:", e);
    console.log("Result bruto:", result);
    return null;
  }
}

async function testEditRecipe() {
  // Criar ingredientes atualizados
  const ingredients = [
    new Ingredient("Farinha de trigo", 600, "g", true), // quantidade alterada
    new Ingredient("Açúcar", 250, "g", true), // quantidade alterada
    new Ingredient("Ovos", 4, "unit", true), // quantidade alterada
    new Ingredient("Leite", 300, "ml", true), // quantidade alterada
    new Ingredient("Manteiga", 150, "g", true), // quantidade alterada
    new Ingredient("Fermento em pó", 10, "g", true), // novo ingrediente
  ];

  // Criar passos atualizados
  const steps = [
    new Step(1, "Preaqueça o forno a 180°C"), // novo passo
    new Step(
      2,
      "Em uma tigela grande, misture a farinha, o açúcar e o fermento"
    ), // modificado
    new Step(3, "Adicione os ovos e bata bem até obter uma mistura homogênea"),
    new Step(4, "Acrescente o leite aos poucos, mexendo sempre"),
    new Step(
      5,
      "Por último, adicione a manteiga derretida e misture delicadamente"
    ),
    new Step(6, "Despeje em uma forma untada e enfarinhada"),
    new Step(7, "Asse por 45 minutos ou até dourar"), // modificado
  ];

  // Criar receita atualizada (use um ID existente)
  const recipe = new Recipe(
    6, // idRecipe existente (altere conforme necessário)
    "Bolo Completo de Farinha", // nome alterado
    "dessert",
    10, // porções alteradas
    4.5, // rating atualizado
    true, // agora é favorito
    null, // imagem atualizada
    steps,
    ingredients
  );

  console.log("Dados da receita atualizada:", recipe);

  const randomKey = CryptoJS.lib.WordArray.random(32).toString();

  const encryptedMessage = CryptoJS.AES.encrypt(
    JSON.stringify(recipe),
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
    "http://localhost/PUC/TDE/backend/index.php/recipe/edit",
    {
      method: "PUT",
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

  try {
    console.log("Resultado bruto:", result);

    if (result.success !== undefined) {
      console.log("Resposta do servidor (Edit Recipe):", result);
      return result;
    }

    if (!result.data) {
      console.error("Erro: result.data não existe!");
      console.log("Result completo:", result);
      return null;
    }

    const decrypted = CryptoJS.AES.decrypt(result.data, randomKey);
    const decryptedString = decrypted.toString(CryptoJS.enc.Utf8);

    if (!decryptedString) {
      console.error("Erro: decryptedString está vazio!");
      return null;
    }

    const data = JSON.parse(decryptedString);
    console.log("Resposta do servidor (Edit Recipe):", data);
    return data;
  } catch (e) {
    console.error("Erro ao descriptografar:", e);
    console.log("Result bruto:", result);
    return null;
  }
}

async function testEditUser() {
  const user = new User(
    null,
    "Caio Atualizado",
    "caiok9",
    "caio@gmail.com",
    "2007-04-12",
    CryptoJS.MD5("novaSenha123").toString(CryptoJS.enc.Hex),
    []
  );

  console.log("Dados do usuário para editar:", user);

  const randomKey = CryptoJS.lib.WordArray.random(32).toString();

  const encryptedMessage = CryptoJS.AES.encrypt(
    JSON.stringify(user),
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
    "http://localhost/PUC/TDE/backend/index.php/user/edit",
    {
      method: "PUT",
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

  try {
    console.log("Resultado bruto:", result);

    if (result.success !== undefined) {
      console.log("Resposta do servidor (Edit User):", result);
      return result;
    }

    if (!result.data) {
      console.error("Erro: result.data não existe!");
      console.log("Result completo:", result);
      return null;
    }

    const decrypted = CryptoJS.AES.decrypt(result.data, randomKey);
    const decryptedString = decrypted.toString(CryptoJS.enc.Utf8);

    if (!decryptedString) {
      console.error("Erro: decryptedString está vazio!");
      return null;
    }

    const data = JSON.parse(decryptedString);
    console.log("Resposta do servidor (Edit User):", data);
    return data;
  } catch (e) {
    console.error("Erro ao descriptografar:", e);
    console.log("Result bruto:", result);
    return null;
  }
}

async function testDeleteUser() {
  var email = "caio@gmail.com";
  var password = CryptoJS.MD5("senha123").toString(CryptoJS.enc.Hex);

  console.log("Deletando usuário:", email);

  const randomKey = CryptoJS.lib.WordArray.random(32).toString();

  const encryptedEmail = CryptoJS.AES.encrypt(email, randomKey).toString();
  const encryptedPassword = CryptoJS.AES.encrypt(
    password,
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

  const params = new URLSearchParams({
    email: encryptedEmail,
    password: encryptedPassword,
    key: encryptedKey,
  });

  const response = await fetch(
    `http://localhost/PUC/TDE/backend/index.php/user/delete?${params.toString()}`,
    {
      method: "DELETE",
      headers: {
        "X-API-KEY": SECRET.API_KEY,
      },
    }
  );

  const result = await response.json();

  try {
    console.log("Resultado bruto:", result);

    if (result.success !== undefined) {
      console.log("Resposta do servidor (Delete User):", result);
      return result;
    }

    if (!result.data) {
      console.error("Erro: result.data não existe!");
      console.log("Result completo:", result);
      return null;
    }

    const decrypted = CryptoJS.AES.decrypt(result.data, randomKey);
    const decryptedString = decrypted.toString(CryptoJS.enc.Utf8);

    if (!decryptedString) {
      console.error("Erro: decryptedString está vazio!");
      return null;
    }

    const data = JSON.parse(decryptedString);
    console.log("Resposta do servidor (Delete User):", data);
    return data;
  } catch (e) {
    console.error("Erro ao descriptografar:", e);
    console.log("Result bruto:", result);
    return null;
  }
}

async function testDeleteRecipe() {
  const recipeId = 5; // Altere conforme necessário

  console.log("Deletando receita ID:", recipeId);

  const randomKey = CryptoJS.lib.WordArray.random(32).toString();

  const encryptedRecipeId = CryptoJS.AES.encrypt(
    recipeId.toString(),
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

  const params = new URLSearchParams({
    recipeId: encryptedRecipeId,
    key: encryptedKey,
  });

  const response = await fetch(
    `http://localhost/PUC/TDE/backend/index.php/recipe/delete?${params.toString()}`,
    {
      method: "DELETE",
      headers: {
        "X-API-KEY": SECRET.API_KEY,
      },
    }
  );

  const result = await response.json();

  try {
    console.log("Resultado bruto:", result);

    if (result.success !== undefined) {
      console.log("Resposta do servidor (Delete Recipe):", result);
      return result;
    }

    if (!result.data) {
      console.error("Erro: result.data não existe!");
      console.log("Result completo:", result);
      return null;
    }

    const decrypted = CryptoJS.AES.decrypt(result.data, randomKey);
    const decryptedString = decrypted.toString(CryptoJS.enc.Utf8);

    if (!decryptedString) {
      console.error("Erro: decryptedString está vazio!");
      return null;
    }

    const data = JSON.parse(decryptedString);
    console.log("Resposta do servidor (Delete Recipe):", data);
    return data;
  } catch (e) {
    console.error("Erro ao descriptografar:", e);
    console.log("Result bruto:", result);
    return null;
  }
}

window.testRegister = testRegister;
window.testLogin = testLogin;
window.testUser = testUser;
window.testEditUser = testEditUser;
window.testDeleteUser = testDeleteUser;
window.testRegisterRecipe = testRegisterRecipe;
window.testEditRecipe = testEditRecipe;
window.testDeleteRecipe = testDeleteRecipe;
