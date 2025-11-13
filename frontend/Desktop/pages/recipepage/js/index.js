document.addEventListener("DOMContentLoaded", () => {
    const addStepBtn = document.getElementById("addStepBtn");
    const directionsContainer = document.getElementById("directionsContainer");
    const browseBtn = document.getElementById("browseBtn");
    const fileInput = document.getElementById("fileInput");
    const dropArea = document.querySelector(".recipe-image");

    if (browseBtn && fileInput && dropArea) {
        browseBtn.addEventListener("click", () => fileInput.click());
        fileInput.addEventListener("change", (event) => {
            const file = event.target.files[0];
            if (file && file.type.startsWith("image/")) {
                showPreview(file);
            }
        });
        dropArea.addEventListener("dragover", (e) => {
            e.preventDefault();
            dropArea.classList.add("highlight");
        });
        dropArea.addEventListener("dragleave", () => {
            dropArea.classList.remove("highlight");
        });
        dropArea.addEventListener("drop", (e) => {
            e.preventDefault();
            dropArea.classList.remove("highlight");
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith("image/")) {
                showPreview(file);
            }
        });

        function showPreview(file) {
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
    }


    if (addStepBtn && directionsContainer) {
        addStepBtn.addEventListener("click", () => {
            const stepCount = directionsContainer.querySelectorAll(".direction-step").length + 1;
            
            const newStep = document.createElement("div");
            newStep.classList.add("direction-step");
            
            const input = document.createElement("input");
            input.type = "text";
            input.name = "step[]";
            input.placeholder = `${stepCount}. Descreva o passo...`;
            
            newStep.appendChild(input);
            directionsContainer.appendChild(newStep);
        });
    }

    const form = document.getElementById("ingredientsForm");
    const addIngredientBtn = document.getElementById("addIngredientBtn");

        if (form && addIngredientBtn) {

            addIngredientBtn.addEventListener("click", () => {
                const newRow = document.createElement("div");
                newRow.classList.add("ingredient-row");

                newRow.innerHTML = `
                <input type="checkbox" class="ingredient-check">
                <input type="text" name="ingredient[]" placeholder="Ingrediente" class="ingredient-input">
                <input type="number" name="quantity[]" placeholder="Quantidade" class="quantity-input" min="0" step="any">
                <input type="text" name="unit[]" placeholder="Unidade (ex: gramas, ml)" class="unit-input">
                
                `;
                // <button type="button" class="remove-ingredient">✕</button>
                document.getElementById("ingredientList").appendChild(newRow);
            });

            // form.addEventListener("click", (e) => {
            //    if (e.target.classList.contains("remove-ingredient")) {
            //        e.target.parentElement.remove();
            //   }
            //});
        }
    
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('ratingValue');

    stars.forEach((star, index) => {
    star.addEventListener('mousemove', (e) => {
        const rect = star.getBoundingClientRect();
        const isHalf = e.clientX - rect.left < rect.width / 2;
        updateStars(index, isHalf);
    });

    star.addEventListener('click', (e) => {
        const rect = star.getBoundingClientRect();
        const isHalf = e.clientX - rect.left < rect.width / 2;
        const rating = index + (isHalf ? 0.5 : 1);
        ratingInput.value = rating.toFixed(1);
        lockStars(rating);
        console.log("Nota:", ratingInput.value);
    });
    });

    function updateStars(index, half) {
    stars.forEach((s, i) => {
        s.classList.remove('filled', 'half');
        if (i < index) s.classList.add('filled');
    });
    if (half) stars[index].classList.add('half');
    else stars[index].classList.add('filled');
    }

    function lockStars(value) {
    stars.forEach((s, i) => {
        s.classList.remove('filled', 'half');
        if (i + 1 <= value) s.classList.add('filled');
        else if (i + 0.5 === value) s.classList.add('half');
    });
    }

    const favBtn = document.getElementById('favoriteBtn');
    const favInput = document.getElementById('favoriteValue');

    favBtn.addEventListener('click', () => {
    favBtn.classList.toggle('active');
    const isFav = favBtn.classList.contains('active');
    favInput.value = isFav;
    console.log("Favoritado:", isFav);
    });

});

let recipes = [
    {name: "Primeira receita"}
];

let editingIndex = -1;

function renderCarousel() {
    const track = document.getElementById("carouselTrack");
    track.innerHTML = "";

    recipes.forEach((r, index) => {
        const card = document.createElement("div");
        card.classList.add("recipe-card");

        card.onclick = () => openPopup(index);

        card.innerHTML = `
            <div class="recipe-img">
                ${r.img ? `<img src="${r.img}" style="width:100%;height:100%;border-radius:12px;">` : "Sem imagem"}
            </div>

            <div class="recipe-name">${r.name}</div>
        `;

        track.appendChild(card);
    });
}


function addEmptyRecipe() {
    recipes.push({
        img: "",
        name: "Nova receita",
    });

    renderCarousel();

}

let currentPosition = 0;

let currentTranslate = 0;

function getElementsForCarousel() {
    const viewport = document.querySelector('.carousel-viewport');
    const track = document.getElementById('carouselTrack');
    return { viewport, track };
}

function moveRight() {
    const { viewport, track } = getElementsForCarousel();
    if (!viewport || !track) return;

    const cards = track.querySelectorAll('.recipe-card');
    if (!cards.length) return;

    const cardStyle = window.getComputedStyle(cards[0]);
    const gap = parseFloat(window.getComputedStyle(track).gap) || 16;
    const cardWidth = cards[0].getBoundingClientRect().width;
    const step = Math.max(Math.round((viewport.clientWidth) / (cardWidth + gap)) , 1) * (cardWidth + gap);

    const maxOffset = Math.max(0, track.scrollWidth - viewport.clientWidth);
    currentTranslate = currentTranslate - step;
    if (-currentTranslate > maxOffset) currentTranslate = -maxOffset;

    track.style.transform = `translateX(${currentTranslate}px)`;
}

function moveLeft() {
    const { viewport, track } = getElementsForCarousel();
    if (!viewport || !track) return;

    const cards = track.querySelectorAll('.recipe-card');
    if (!cards.length) return;

    const cardStyle = window.getComputedStyle(cards[0]);
    const gap = parseFloat(window.getComputedStyle(track).gap) || 16;
    const cardWidth = cards[0].getBoundingClientRect().width;
    const step = Math.max(Math.round((viewport.clientWidth) / (cardWidth + gap)) , 1) * (cardWidth + gap);

    currentTranslate = currentTranslate + step;
    if (currentTranslate > 0) currentTranslate = 0;

    track.style.transform = `translateX(${currentTranslate}px)`;
}

function openPopup(index) {
    editingIndex = index;
    document.getElementById("popupBg").style.display = "flex";
}

function closePopup() {
    document.getElementById("popupBg").style.display = "none";
}


renderCarousel();

