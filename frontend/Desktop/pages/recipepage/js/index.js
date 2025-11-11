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