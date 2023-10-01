// Animacion para imagenes cabecera //


const backgroundImages = [
    "/img/img1.jpg",
    "/img/img2.jpg",
    "/img/img3.jpg",
    "/img/img4.jpg",
    "/img/img5.jpg"
];

let currentIndex = 0;

function changeBackgroundImage() {
    const backgroundImage = document.querySelector(".background-image");
    currentIndex = (currentIndex + 1) % backgroundImages.length;
    backgroundImage.style.backgroundImage = `url('${backgroundImages[currentIndex]}')`;
}

// Cambiar la imagen de fondo cada 4 segundos
setInterval(changeBackgroundImage, 4000);