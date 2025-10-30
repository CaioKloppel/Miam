import SECRET_KEY from "../../../config.js";

async function testRegister() {
  console.log("=== TESTE DE REGISTRO DE USUÁRIO ===\n");

  // Dados do novo usuário
  const userData = {
    name: "João Silva",
    nickname: "joaosilva123",
    email: "joao.silva@email.com",
    birthDate: "1995-05-15",
    password: "senha123",
  };

  console.log("Dados do usuário:", userData);

  const encrypted = CryptoJS.AES.encrypt(
    JSON.stringify(userData),
    SECRET_KEY
  ).toString();

  const response = await fetch(
    "http://localhost/PUC/TDE/backend/index.php/register",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ info: encrypted }),
    }
  );

  const result = await response.json();
  console.log("Resposta do servidor:", result);

  if (result.success) {
    console.log("✅ Usuário registrado com sucesso!");
  } else {
    console.log("❌ Erro ao registrar:", result.message);
  }
}

async function testLogin() {
  var email = "joao.silva@email.com";
  var password = "senha123";
  console.log("=== TESTE DE LOGIN ===");
  console.log("Email:", email);
  console.log("Password:", password);

  try {
    // Criptografar dados de login
    const loginData = {
      "email/nick": email,
      password: password,
    };

    const encrypted = CryptoJS.AES.encrypt(
      JSON.stringify(loginData),
      SECRET_KEY
    ).toString();

    console.log("Dados criptografados:", encrypted);

    const response = await fetch(
      "http://localhost/PUC/TDE/backend/index.php/login",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ info: encrypted }),
      }
    );

    const result = await response.json();
    console.log("Resposta do servidor:", result);

    if (result.success) {
      console.log("✅ Login bem-sucedido!");
      console.log("Mensagem:", result.message);
    } else {
      console.log("❌ Falha no login:", result.message);
    }
  } catch (error) {
    console.error("❌ Erro na requisição de login:", error);
  }
}

window.testRegister = testRegister;
window.testLogin = testLogin;
