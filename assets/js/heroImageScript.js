const slides = document.querySelectorAll(".hero-carousel img");

if (slides.length > 0) {
  let current = 0;

  // console.log(slides);
  // console.log(slides[current]);

  setInterval(() => {
    slides[current].classList.remove("active");

    current = (current + 1) % slides.length;
    slides[current].classList.add("active");
  }, 2000);
}

// console.log("jalan!");
