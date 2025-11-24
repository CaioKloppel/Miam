import { User } from "../../../models/User.js";
import { Recipe } from "../../../models/Recipe.js";
import { Ingredient } from "../../../models/Ingredient.js";
import { Step } from "../../../models/Step.js";
import SECRET from "../../../../../config.js";

const user = await (async function () {
  const emailNick = localStorage.getItem("userEmailOrNick");
  const password = localStorage.getItem("userPassword");

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

  const encryptedEmail = CryptoJS.AES.encrypt(emailNick, randomKey).toString();
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

  const decrypted = CryptoJS.AES.decrypt(result.data, randomKey);
  const decryptedString = decrypted.toString(CryptoJS.enc.Utf8);

  const data = JSON.parse(decryptedString);

  if (data.success) {
    var recipes = [];
    data.user.recipes.forEach((recipe) => {
      var ingredients = [];
      var steps = [];

      recipe.ingredients.forEach((ingredient) => {
        ingredients.push(
          new Ingredient(
            ingredient.name,
            ingredient.quantity,
            ingredient.typeQuantity,
            ingredient.avaible
          )
        );
      });

      recipe.steps.forEach((step) => {
        steps.push(new Step(step.numStep, step.description));
      });

      recipes.push(
        new Recipe(
          recipe.idRecipe,
          recipe.name,
          recipe.category,
          recipe.portions,
          recipe.rating,
          recipe.favorite,
          recipe.image,
          steps,
          ingredients
        )
      );
    });

    return new User(
      data.user.idUser,
      data.user.name,
      data.user.nickname,
      data.user.email,
      data.user.birthDate,
      password,
      recipes
    );
  } else return null;
})();

let recipes = [];

function showPreview(file) {
  const dropArea = document.querySelector(".recipe-image");
  if (!dropArea) return;

  const reader = new FileReader();
  reader.onload = (e) => {
    const image = document.createElement("img");
    image.src = e.target.result;
    image.style.width = "100%";
    image.style.height = "100%";
    image.style.objectFit = "cover";
    image.style.borderRadius = "10px";

    dropArea.innerHTML = "";
    dropArea.appendChild(image);
  };
  reader.readAsDataURL(file);
}

function updateStepRemoveButtons() {
  const directionsContainer = document.getElementById("directionsContainer");
  if (!directionsContainer) return;

  const steps = directionsContainer.querySelectorAll(".direction-step");
  steps.forEach((step, index) => {
    const removeBtn = step.querySelector(".remove-step");
    if (removeBtn) {
      removeBtn.style.display = index === 0 ? "none" : "block";
    }
  });
}

function updateIngredientRemoveButtons() {
  const ingredientList = document.getElementById("ingredientList");
  if (!ingredientList) return;

  const rows = ingredientList.querySelectorAll(".ingredient-row");
  rows.forEach((row, index) => {
    const removeBtn = row.querySelector(".remove-ingredient");
    if (removeBtn) {
      removeBtn.style.display = index === 0 ? "none" : "block";
    }
  });
}

async function saveRecipe(recipe) {
  const registerRecipe = {
    userId: user.idUser,
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

  const decrypted = CryptoJS.AES.decrypt(result.data, randomKey);
  const decryptedString = decrypted.toString(CryptoJS.enc.Utf8);

  const data = JSON.parse(decryptedString);

  return data.success;
}

async function editRecipe(recipe) {
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

  const decrypted = CryptoJS.AES.decrypt(result.data, randomKey);
  const decryptedString = decrypted.toString(CryptoJS.enc.Utf8);

  const data = JSON.parse(decryptedString);

  return data.success;
}

function setupPopupEventListeners() {
  const browseBtn = document.getElementById("browseBtn");
  const fileInput = document.getElementById("fileInput");
  const dropArea = document.querySelector(".recipe-image");

  if (browseBtn && fileInput && dropArea) {
    const newBrowseBtn = browseBtn.cloneNode(true);
    browseBtn.parentNode.replaceChild(newBrowseBtn, browseBtn);

    const newFileInput = fileInput.cloneNode(true);
    fileInput.parentNode.replaceChild(newFileInput, fileInput);

    const freshBrowseBtn = document.getElementById("browseBtn");
    const freshFileInput = document.getElementById("fileInput");

    freshBrowseBtn.addEventListener("click", () => freshFileInput.click());

    freshFileInput.addEventListener("change", (event) => {
      const file = event.target.files[0];
      if (file && file.type.startsWith("image/")) {
        showPreview(file);
      }
    });
  }

  if (dropArea) {
    const newDropArea = dropArea.cloneNode(true);
    dropArea.parentNode.replaceChild(newDropArea, dropArea);

    newDropArea.addEventListener("dragover", (e) => {
      e.preventDefault();
      newDropArea.classList.add("highlight");
    });
    newDropArea.addEventListener("dragleave", () => {
      newDropArea.classList.remove("highlight");
    });
    newDropArea.addEventListener("drop", (e) => {
      e.preventDefault();
      newDropArea.classList.remove("highlight");
      const file = e.dataTransfer.files[0];
      if (file && file.type.startsWith("image/")) {
        showPreview(file);
      }
    });
  }

  const addStepBtn = document.getElementById("addStepBtn");
  const directionsContainer = document.getElementById("directionsContainer");

  if (addStepBtn && directionsContainer) {
    const newAddStepBtn = addStepBtn.cloneNode(true);
    addStepBtn.parentNode.replaceChild(newAddStepBtn, addStepBtn);

    newAddStepBtn.addEventListener("click", () => {
      const currentContainer = document.getElementById("directionsContainer");
      const stepCount =
        currentContainer.querySelectorAll(".direction-step").length + 1;

      const newStep = document.createElement("div");
      newStep.classList.add("direction-step");

      const input = document.createElement("input");
      input.type = "text";
      input.name = "step[]";
      input.placeholder = `${stepCount}. Descreva o passo...`;

      const removeBtn = document.createElement("button");
      removeBtn.type = "button";
      removeBtn.classList.add("remove-step");
      removeBtn.textContent = "✕";

      newStep.appendChild(input);
      newStep.appendChild(removeBtn);
      currentContainer.appendChild(newStep);

      updateStepRemoveButtons();
    });

    const newDirectionsContainer = directionsContainer.cloneNode(true);
    directionsContainer.parentNode.replaceChild(
      newDirectionsContainer,
      directionsContainer
    );

    newDirectionsContainer.addEventListener("click", (e) => {
      if (e.target.classList.contains("remove-step")) {
        e.target.parentElement.remove();
        updateStepRemoveButtons();
      }
    });

    updateStepRemoveButtons();
  }

  const form = document.getElementById("ingredientsForm");
  const addIngredientBtn = document.getElementById("addIngredientBtn");

  if (form && addIngredientBtn) {
    const newAddIngredientBtn = addIngredientBtn.cloneNode(true);
    addIngredientBtn.parentNode.replaceChild(
      newAddIngredientBtn,
      addIngredientBtn
    );

    newAddIngredientBtn.addEventListener("click", () => {
      const newRow = document.createElement("div");
      newRow.classList.add("ingredient-row");

      newRow.innerHTML = `
                <input type="checkbox" class="ingredient-check">
                <input type="text" name="ingredient[]" placeholder="Ingrediente" class="ingredient-input">
                <input type="number" name="quantity[]" placeholder="Quantidade" class="quantity-input" min="0" step="any">
                <select name="unit[]" class="unit-input" required>
                    <option value="">Tipo de quantidade</option>
                    <option value="tablespoon">Colher de sopa</option>
                    <option value="teaspoon">Colher de chá</option>
                    <option value="cups">Xícaras</option>
                    <option value="ml">Mililitros</option>
                    <option value="l">Litros</option>
                    <option value="g">Gramas</option>
                    <option value="kg">Quilogramas</option>
                    <option value="unit">Unidade</option>
                </select>
                <button type="button" class="remove-ingredient">✕</button>
                `;
      document.getElementById("ingredientList").appendChild(newRow);

      updateIngredientRemoveButtons();
    });

    const newForm = form.cloneNode(true);
    form.parentNode.replaceChild(newForm, form);

    newForm.addEventListener("click", (e) => {
      if (e.target.classList.contains("remove-ingredient")) {
        e.target.parentElement.remove();
        updateIngredientRemoveButtons();
      }
    });

    updateIngredientRemoveButtons();
  }

  const saveRecipeBtn = document.getElementById("saveRecipeBtn");
  if (saveRecipeBtn) {
    const newSaveRecipeBtn = saveRecipeBtn.cloneNode(true);
    saveRecipeBtn.parentNode.replaceChild(newSaveRecipeBtn, saveRecipeBtn);

    newSaveRecipeBtn.addEventListener("click", async () => {
      const titleInput = document.querySelector('input[name="recipetitle"]');
      const categorySelect = document.querySelector("select.category");
      const ratingValue = document.getElementById("ratingValue");
      const favoriteValue = document.getElementById("favoriteValue");

      const ingredientRows = document.querySelectorAll(".ingredient-row");
      const stepInputs = document.querySelectorAll(".direction-step input");

      if (!titleInput?.value.trim()) {
        alert("Por favor, preencha o nome da receita.");
        return;
      }

      if (!categorySelect?.value) {
        alert("Por favor, selecione uma categoria.");
        return;
      }

      if (!ratingValue?.value || ratingValue.value === "0") {
        alert("Por favor, adicione uma avaliação.");
        return;
      }

      const ingredients = [];
      let hasInvalidIngredient = false;
      ingredientRows.forEach((row) => {
        const name = row.querySelector(".ingredient-input")?.value.trim();
        const quantity = row.querySelector(".quantity-input")?.value;
        const unit = row.querySelector(".unit-input")?.value;
        const available =
          row.querySelector(".ingredient-check")?.checked || false;

        if (!name || !quantity || !unit) {
          hasInvalidIngredient = true;
          return;
        }

        ingredients.push(
          new Ingredient(name, parseFloat(quantity), unit, available)
        );
      });

      if (hasInvalidIngredient || ingredients.length === 0) {
        alert("Por favor, preencha todos os campos dos ingredientes.");
        return;
      }

      const steps = [];
      let hasInvalidStep = false;
      stepInputs.forEach((input, index) => {
        const description = input.value.trim();
        if (!description) {
          hasInvalidStep = true;
          return;
        }
        steps.push(new Step(index + 1, description));
      });

      if (hasInvalidStep || steps.length === 0) {
        alert("Por favor, preencha todos os passos.");
        return;
      }

      const imageElement = document.querySelector(".recipe-image img");
      let imageUrl = imageElement?.src || "";

      if (editingIndex >= 0 && !imageUrl) {
        imageUrl = recipes[editingIndex].image || "";
      }

      const newRecipe = new Recipe(
        editingIndex >= 0 ? recipes[editingIndex].idRecipe : null,
        titleInput.value.trim(),
        categorySelect.value,
        0,
        parseFloat(ratingValue.value),
        favoriteValue.value === "true",
        imageUrl,
        steps,
        ingredients
      );

      if (editingIndex >= 0) {
        var habemusRecipeEdit = await editRecipe(newRecipe);
        if (habemusRecipeEdit) {
          recipes[editingIndex] = newRecipe;
          user.recipes[editingIndex] = newRecipe;
          renderCarousel();
          closePopup();
        } else {
          alert("FALHA AO EDITAR RECEITA.");
        }
      } else {
        var habemusRecipeRegister = await saveRecipe(newRecipe);
        if (habemusRecipeRegister) {
          location.reload();
        } else {
          alert("FALHA AO SALVAR RECEITA.");
        }
      }
    });
  }

  setupStarRating();
  setupFavoriteButton();
}

function initializeApp() {
  if (!user) {
    alert("FALHA AO TENTAR ACESSAR INFORMAÇÕES DO USUÁRIO");
    window.location.href = "../login/index.html";
    return;
  }

  recipes = user.recipes || [];

  setupPopupEventListeners();
  renderCarousel();
}

let editingIndex = -1;
let clickedRating = 0;

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", () => {
    initializeApp();
  });
} else {
  initializeApp();
}

function setupStarRating() {
  const ratingContainer = document.getElementById("rating");
  const stars = ratingContainer.querySelectorAll(".star");
  const ratingInput = document.getElementById("ratingValue");

  if (!ratingContainer || !stars.length || !ratingInput) return;

  clickedRating = parseFloat(ratingInput.value) || 0;

  const newRatingContainer = ratingContainer.cloneNode(true);
  ratingContainer.parentNode.replaceChild(newRatingContainer, ratingContainer);

  const freshStars = newRatingContainer.querySelectorAll(".star");
  const freshRatingInput = document.getElementById("ratingValue");

  lockStars(clickedRating, freshStars);

  freshStars.forEach((star, index) => {
    star.addEventListener("mousemove", (e) => {
      const rect = star.getBoundingClientRect();
      const isHalf = e.clientX - rect.left < rect.width / 2;
      updateStars(index, isHalf, freshStars);
    });

    star.addEventListener("click", (e) => {
      const rect = star.getBoundingClientRect();
      const isHalf = e.clientX - rect.left < rect.width / 2;
      const rating = index + (isHalf ? 0.5 : 1);
      clickedRating = rating;
      freshRatingInput.value = rating.toFixed(1);
      lockStars(rating, freshStars);
    });
  });

  newRatingContainer.addEventListener("mouseleave", () => {
    lockStars(clickedRating, freshStars);
  });
}

function updateStars(index, half, stars) {
  stars.forEach((s, i) => {
    s.classList.remove("filled", "half");
    if (i < index) s.classList.add("filled");
  });
  if (half) stars[index].classList.add("half");
  else stars[index].classList.add("filled");
}

function lockStars(value, stars) {
  stars.forEach((s, i) => {
    s.classList.remove("filled", "half");
    if (i + 1 <= value) s.classList.add("filled");
    else if (i + 0.5 === value) s.classList.add("half");
  });
}

function setupFavoriteButton() {
  const favBtn = document.getElementById("favoriteBtn");
  const favInput = document.getElementById("favoriteValue");

  if (!favBtn) return;

  const newFavBtn = favBtn.cloneNode(true);
  favBtn.parentNode.replaceChild(newFavBtn, favBtn);

  newFavBtn.addEventListener("click", () => {
    newFavBtn.classList.toggle("active");
    const isFav = newFavBtn.classList.contains("active");
    favInput.value = isFav;
  });
}

function updateUI() {
  const greetingElement = document.querySelector(".greeting h1");
  const addBtn = document.querySelector(".add-btn");

  if (recipes.length === 0) {
    greetingElement.textContent = `Bem-vindo ${user.name}! Comece criando sua primeira receita!`;
    addBtn.textContent = "CRIE SUA PRIMEIRA RECEITA";
  } else {
    greetingElement.textContent = `Bem-vindo de volta ${user.name}!`;
    addBtn.textContent = "ADICIONAR RECEITA";
  }
}

function renderCarousel() {
  const track = document.getElementById("carouselTrack");
  track.innerHTML = "";

  recipes.forEach((recipe, index) => {
    const card = document.createElement("div");
    card.classList.add("recipe-card");

    card.onclick = () => openPopup(index);

    card.innerHTML = `
            <div class="recipe-img">
                ${
                  recipe.image
                    ? `<img src="${recipe.image}" style="width:100%;height:100%;border-radius:12px;">`
                    : "Sem imagem"
                }
            </div>

            <div class="recipe-name">${recipe.name}</div>
        `;

    track.appendChild(card);
  });

  updateUI();
}

function addEmptyRecipe() {
  editingIndex = -1;
  openPopup(-1);
}

let currentTranslate = 0;

function getElementsForCarousel() {
  const viewport = document.querySelector(".carousel-viewport");
  const track = document.getElementById("carouselTrack");
  return { viewport, track };
}

function moveRight() {
  const { viewport, track } = getElementsForCarousel();
  if (!viewport || !track) return;

  const cards = track.querySelectorAll(".recipe-card");
  if (!cards.length) return;

  const cardStyle = window.getComputedStyle(cards[0]);
  const gap = parseFloat(window.getComputedStyle(track).gap) || 16;
  const cardWidth = cards[0].getBoundingClientRect().width;
  const step =
    Math.max(Math.round(viewport.clientWidth / (cardWidth + gap)), 1) *
    (cardWidth + gap);

  const maxOffset = Math.max(0, track.scrollWidth - viewport.clientWidth);
  currentTranslate = currentTranslate - step;
  if (-currentTranslate > maxOffset) currentTranslate = -maxOffset;

  track.style.transform = `translateX(${currentTranslate}px)`;
}

function moveLeft() {
  const { viewport, track } = getElementsForCarousel();
  if (!viewport || !track) return;

  const cards = track.querySelectorAll(".recipe-card");
  if (!cards.length) return;

  const gap = parseFloat(window.getComputedStyle(track).gap) || 16;
  const cardWidth = cards[0].getBoundingClientRect().width;
  const step =
    Math.max(Math.round(viewport.clientWidth / (cardWidth + gap)), 1) *
    (cardWidth + gap);

  currentTranslate = currentTranslate + step;
  if (currentTranslate > 0) currentTranslate = 0;

  track.style.transform = `translateX(${currentTranslate}px)`;
}

function openPopup(index) {
  editingIndex = index;

  const titleInput = document.querySelector('input[name="recipetitle"]');
  const categorySelect = document.querySelector("select.category");
  const ratingValue = document.getElementById("ratingValue");
  const favoriteValue = document.getElementById("favoriteValue");
  const favoriteBtn = document.getElementById("favoriteBtn");

  if (index >= 0 && recipes[index]) {
    const recipe = recipes[index];

    if (titleInput) titleInput.value = recipe.name || "";
    if (categorySelect) categorySelect.value = recipe.category || "";
    if (ratingValue) ratingValue.value = recipe.rating || 0;
    if (favoriteValue) favoriteValue.value = recipe.favorite;
    if (favoriteBtn) {
      if (recipe.favorite) {
        favoriteBtn.classList.add("active");
      } else {
        favoriteBtn.classList.remove("active");
      }
    }

    setupStarRating();
    setupFavoriteButton();
    setupPopupEventListeners();

    const dropArea = document.querySelector(".recipe-image");
    if (dropArea && recipe.image) {
      dropArea.innerHTML = `
        <div style="position:relative;width:100%;height:100%;">
          <img src="${recipe.image}" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
          <div style="position:absolute;bottom:10px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,0.7);color:#fff;padding:8px 16px;border-radius:5px;font-size:12px;font-family:'Poppins',sans-serif;white-space:nowrap;">Para editar arraste uma nova imagem aqui</div>
        </div>
        <input type="file" id="fileInput" accept="image/*" hidden>
      `;

      const newFileInput = document.getElementById("fileInput");
      if (newFileInput) {
        newFileInput.addEventListener("change", (event) => {
          const file = event.target.files[0];
          if (file && file.type.startsWith("image/")) {
            showPreview(file);
          }
        });
      }
    }

    let ingredientList = document.getElementById("ingredientList");
    if (ingredientList && recipe.ingredients) {
      ingredientList.innerHTML = "";
      recipe.ingredients.forEach((ingredient, idx) => {
        const newRow = document.createElement("div");
        newRow.classList.add("ingredient-row");
        newRow.innerHTML = `
          <input type="checkbox" class="ingredient-check" ${
            ingredient.avaible ? "checked" : ""
          }>
          <input type="text" name="ingredient[]" placeholder="Ingrediente" class="ingredient-input" value="${
            ingredient.name || ""
          }">
          <input type="number" name="quantity[]" placeholder="Quantidade" class="quantity-input" min="0" step="any" value="${
            ingredient.quantity || ""
          }">
          <select name="unit[]" class="unit-input" required>
            <option value="">Tipo de quantidade</option>
            <option value="tablespoon" ${
              ingredient.typeQuantity === "tablespoon" ? "selected" : ""
            }>Colher de sopa</option>
            <option value="teaspoon" ${
              ingredient.typeQuantity === "teaspoon" ? "selected" : ""
            }>Colher de chá</option>
            <option value="cups" ${
              ingredient.typeQuantity === "cups" ? "selected" : ""
            }>Xícaras</option>
            <option value="ml" ${
              ingredient.typeQuantity === "ml" ? "selected" : ""
            }>Mililitros</option>
            <option value="l" ${
              ingredient.typeQuantity === "l" ? "selected" : ""
            }>Litros</option>
            <option value="g" ${
              ingredient.typeQuantity === "g" ? "selected" : ""
            }>Gramas</option>
            <option value="kg" ${
              ingredient.typeQuantity === "kg" ? "selected" : ""
            }>Quilogramas</option>
            <option value="unit" ${
              ingredient.typeQuantity === "unit" ? "selected" : ""
            }>Unidade</option>
          </select>
          <button type="button" class="remove-ingredient" style="display: ${
            idx === 0 ? "none" : "block"
          }">✕</button>
        `;
        ingredientList.appendChild(newRow);
      });
    }

    let directionsContainer = document.getElementById("directionsContainer");
    if (directionsContainer && recipe.steps) {
      directionsContainer.innerHTML = "";
      recipe.steps.forEach((step, idx) => {
        const newStep = document.createElement("div");
        newStep.classList.add("direction-step");
        newStep.innerHTML = `
          <input type="text" name="step[]" placeholder="${
            idx + 1
          }. Descreva o passo..." value="${step.description || ""}">
          <button type="button" class="remove-step" style="display: ${
            idx === 0 ? "none" : "block"
          }">✕</button>
        `;
        directionsContainer.appendChild(newStep);
      });
    }
  } else {
    if (titleInput) titleInput.value = "";
    if (categorySelect) categorySelect.value = "";
    if (ratingValue) ratingValue.value = 0;
    if (favoriteValue) favoriteValue.value = false;
    if (favoriteBtn) favoriteBtn.classList.remove("active");

    const stars = document.querySelectorAll(".star");
    stars.forEach((s) => s.classList.remove("filled", "half"));

    let ingredientList = document.getElementById("ingredientList");
    if (ingredientList) {
      ingredientList.innerHTML = `
        <div class="ingredient-row">
          <input type="checkbox" class="ingredient-check">
          <input type="text" name="ingredient[]" placeholder="Ingrediente" class="ingredient-input">
          <input type="number" name="quantity[]" placeholder="Qntd" class="quantity-input" min="0" step="any">
          <select name="unit[]" class="unit-input" required>
            <option value="">Tipo de quantidade</option>
            <option value="tablespoon">Colher de sopa</option>
            <option value="teaspoon">Colher de chá</option>
            <option value="cups">Xícaras</option>
            <option value="ml">Mililitros</option>
            <option value="l">Litros</option>
            <option value="g">Gramas</option>
            <option value="kg">Quilogramas</option>
            <option value="unit">Unidade</option>
          </select>
          <button type="button" class="remove-ingredient" style="display: none;">✕</button>
        </div>
      `;
    }

    let directionsContainer = document.getElementById("directionsContainer");
    if (directionsContainer) {
      directionsContainer.innerHTML = `
        <div class="direction-step">
          <input type="text" name="step[]" placeholder="1. Descreva o primeiro passo...">
          <button type="button" class="remove-step" style="display: none;">✕</button>
        </div>
      `;
    }

    setupStarRating();
    setupFavoriteButton();
    setupPopupEventListeners();
  }

  document.getElementById("popupBg").style.display = "flex";
}

function closePopup() {
  const titleInput = document.querySelector('input[name="recipetitle"]');
  const categorySelect = document.querySelector("select.category");
  const ratingValue = document.getElementById("ratingValue");
  const favoriteValue = document.getElementById("favoriteValue");
  const favoriteBtn = document.getElementById("favoriteBtn");
  const dropArea = document.querySelector(".recipe-image");

  if (titleInput) titleInput.value = "";
  if (categorySelect) categorySelect.value = "";
  if (ratingValue) ratingValue.value = 0;
  if (favoriteValue) favoriteValue.value = false;
  if (favoriteBtn) favoriteBtn.classList.remove("active");

  const stars = document.querySelectorAll(".star");
  stars.forEach((s) => s.classList.remove("filled", "half"));

  if (dropArea) {
    dropArea.innerHTML = `
      <div class="img-icon"><img src="images/cloud-computing.png" height="50px" width="50px"></div>
      <header>Arraste e solte para enviar o arquivo</header>
      <span>OU</span>
      <button id="browseBtn" type="button">Procure em arquivos</button>
      <input type="file" id="fileInput" accept="image/*" hidden>
    `;
  }

  editingIndex = -1;

  document.getElementById("popupBg").style.display = "none";
}

function logout() {
  localStorage.removeItem("userEmailOrNick");
  localStorage.removeItem("userPassword");

  window.location.href = "../";
}

window.addEmptyRecipe = addEmptyRecipe;
window.closePopup = closePopup;
window.moveLeft = moveLeft;
window.moveRight = moveRight;
window.logout = logout;
