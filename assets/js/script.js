// Fungsi untuk toggle class 'show' pada dropdown
function toggleNavbarDropdown() {
  document.getElementById("navbarDropdown").classList.toggle("show");
}

// untuk sekarang, scroll ke isi
function handleBukaUndangan() {
  document.getElementById("isiUndangan").scrollIntoView({
    behavior: "smooth",
  });
}

// Logic Countdown di halaman invitation
const countdownElem = document.getElementById("countdown");

if (countdownElem) {
  const targetDate = new Date(
    countdownElem.getAttribute("data-date")
  ).getTime();

  const timer = setInterval(function () {
    const now = new Date().getTime();
    const distance = targetDate - now;

    // Perhitungan waktu
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor(
      (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
    );
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById("days").innerText = days;
    document.getElementById("hours").innerText = hours;
    document.getElementById("minutes").innerText = minutes;
    document.getElementById("seconds").innerText = seconds;

    // handle waktu habis
    if (distance < 0) {
      clearInterval(timer);
      document.getElementById("countdown").innerHTML =
        "<div class='cd-box' style='width: 80%'><h3 style='color:#ffd700'>Acara Telah Dimulai!</h3></div>";
    }
  }, 1000);
}

// Tutup dropdown kalau user klik di luar menu
window.onclick = function (event) {
  if (
    !event.target.matches(".nav-dropdown-toggle") &&
    !event.target.matches(".nav-dropdown-toggle *")
  ) {
    var dropdowns = document.getElementsByClassName("nav-dropdown-content");
    for (var i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains("show")) {
        openDropdown.classList.remove("show");
      }
    }
  }
};
