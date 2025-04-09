// Horizontal Carousel
const carouselTrack = document.getElementById('carouselTrack');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const links = carouselTrack.getElementsByTagName('a');
let imageWidth = window.innerWidth <= 767 ? 100 : 50; // 100% for small screens, 50% for larger screens
let visibleImages = window.innerWidth <= 767 ? 1 : 2; // 1 image for small screens, 2 for larger screens
let currentIndex = 0;

function updateCarousel() {
    const maxIndex = links.length - visibleImages;
    if (currentIndex < 0) currentIndex = 0;
    if (currentIndex > maxIndex) currentIndex = maxIndex;
    carouselTrack.style.transform = `translateX(-${currentIndex * imageWidth}%)`;
}

window.addEventListener('resize', () => {
    imageWidth = window.innerWidth <= 767 ? 100 : 50;
    visibleImages = window.innerWidth <= 767 ? 1 : 2;
    updateCarousel();
});

prevBtn.addEventListener('click', () => {
    currentIndex--;
    updateCarousel();
});

nextBtn.addEventListener('click', () => {
    currentIndex++;
    updateCarousel();
});

// Star Rating Generator
document.querySelectorAll('.star-rating').forEach(ratingElement => {
    const rating = parseFloat(ratingElement.getAttribute('data-rating'));
    let starsHTML = '';

    for (let i = 1; i <= 5; i++) {
        if (rating >= i) {
            starsHTML += '<i class="bi bi-star-fill"></i>';
        } else if (rating >= i - 0.5) {
            starsHTML += '<i class="bi bi-star-half"></i>';
        } else {
            starsHTML += '<i class="bi bi-star"></i>';
        }
    }

    starsHTML += `<span class="ms-2">${rating}</span>`;
    ratingElement.innerHTML = starsHTML;
});
