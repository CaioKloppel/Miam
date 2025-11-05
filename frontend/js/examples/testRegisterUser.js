import SECRET from "/PUC/TDE/config.js";
import { User } from "../models/User.js";
import { Recipe } from "../models/Recipe.js";
import { Ingredient } from "../models/Ingredient.js";
import { Step } from "../models/Step.js";

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

  const encrypted = CryptoJS.AES.encrypt(
    JSON.stringify(user),
    SECRET.SECRET_KEY
  ).toString();

  const response = await fetch(
    "http://localhost/PUC/TDE/backend/index.php/user/register",
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
  var emailNick = "caio@gmail.com";
  var password = CryptoJS.MD5("senha123").toString(CryptoJS.enc.Hex);
  console.log("email/nick:", emailNick);
  console.log("password:", password);

  const loginData = {
    "email/nick": emailNick,
    password: password,
  };

  const encrypted = CryptoJS.AES.encrypt(
    JSON.stringify(loginData),
    SECRET.SECRET_KEY
  ).toString();

  console.log("Dados criptografados:", encrypted);

  const response = await fetch(
    "http://localhost/PUC/TDE/backend/index.php/user/login",
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

async function testUser() {
  var email = "caio@gmail.com";
  var password = CryptoJS.MD5("senha123").toString(CryptoJS.enc.Hex);

  const response = await fetch(
    `http://localhost/PUC/TDE/backend/index.php/user/return?email=${email}&password=${password}`,
    {
      method: "GET",
      headers: {
        "X-API-KEY": SECRET.API_KEY,
      },
    }
  );

  const result = await response.json();
  console.log("Resposta do servidor:", result);
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
    userId: 1,
    recipe: recipe,
  };

  const encrypted = CryptoJS.AES.encrypt(
    JSON.stringify(registerRecipe),
    SECRET.SECRET_KEY
  ).toString();

  const response = await fetch(
    "http://localhost/PUC/TDE/backend/index.php/recipe/register",
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
  console.log("Resposta do servidor (Register Recipe):", result);
  return result;
}

async function testEditRecipe() {
  // Criar ingredientes atualizados
  const ingredients = [
    new Ingredient("Farinha de trigo", 600, "g", true), // quantidade alterada
    new Ingredient("Açúcar", 250, "g", true), // quantidade alterada
    new Ingredient("Ovos", 4, "unidades", true), // quantidade alterada
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
    1, // idRecipe existente (altere conforme necessário)
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

  const encrypted = CryptoJS.AES.encrypt(
    JSON.stringify(recipe),
    SECRET.SECRET_KEY
  ).toString();

  const response = await fetch(
    "http://localhost/PUC/TDE/backend/index.php/recipe/edit",
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
  console.log("Resposta do servidor (Edit Recipe):", result);
  return result;
}

window.testRegister = testRegister;
window.testLogin = testLogin;
window.testUser = testUser;
window.testRegisterRecipe = testRegisterRecipe;
window.testEditRecipe = testEditRecipe;
