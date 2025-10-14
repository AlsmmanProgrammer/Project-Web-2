document.addEventListener("DOMContentLoaded", function () {
  const body = document.body;
  const themeToggle = document.getElementById("themeToggle");
  const themeIcon = document.getElementById("themeIcon");

  function applyTheme(theme) {
    if (theme === "dark") {
      body.classList.add("dark-mode");
      body.classList.remove("light-mode");
      if (themeIcon) themeIcon.className = "fa-solid fa-sun";
    } else {
      body.classList.add("light-mode");
      body.classList.remove("dark-mode");
      if (themeIcon) themeIcon.className = "fa-regular fa-moon";
    }
  }

  let theme = localStorage.getItem("theme") || "light";
  applyTheme(theme);

  if (themeToggle) {
    themeToggle.addEventListener("click", () => {
      theme = theme === "light" ? "dark" : "light";
      localStorage.setItem("theme", theme);
      applyTheme(theme);
    });
  }

  const filterBtns = document.querySelectorAll(".filter-btn");
  const eventCards = document.querySelectorAll(".event-card");
  filterBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const cat = btn.getAttribute("data-cat");
      if (cat === "all") {
        eventCards.forEach((c) => (c.style.display = ""));
      } else {
        eventCards.forEach((c) => {
          c.style.display =
            c.getAttribute("data-category") === cat ? "" : "none";
        });
      }
    });
  });

  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("input", () => {
      const q = searchInput.value.trim().toLowerCase();
      eventCards.forEach((c) => {
        const title = c.querySelector(".card-title")
          ? c.querySelector(".card-title").innerText.toLowerCase()
          : "";
        const desc = c.querySelector(".card-text")
          ? c.querySelector(".card-text").innerText.toLowerCase()
          : "";
        c.style.display = title.includes(q) || desc.includes(q) ? "" : "none";
      });
    });
  }
});

const langToggle = document.getElementById("langToggle");
const langIcon = document.getElementById("langIcon");
if (langToggle && langIcon) {
  if (window.location.search.includes("lang=en")) {
    langIcon.className = "fa-solid fa-language";
  } else {
    langIcon.className = "fa-solid fa-globe";
  }
}

AOS.init({
  duration: 700,
  once: true,
});

const scrollBtn = document.getElementById("scrollTopBtn");
if (scrollBtn) {
  window.addEventListener("scroll", () => {
    scrollBtn.style.display = window.scrollY > 300 ? "block" : "none";
  });
  scrollBtn.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
}

AOS.init({ duration: 700, once: true });

document.getElementById("contactForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const btn = this.querySelector("button");
  btn.disabled = true;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

  setTimeout(() => {
    alert("✅ Message sent successfully!");
    this.reset();
    btn.disabled = false;
    btn.innerHTML = "Send";
  }, 1500);
});

AOS.init({duration: 700, once: true,});

$(".filter-btn").on("click", function () {
  const category = $(this).data("cat");
  $(".filter-btn").removeClass("active");
  $(this).addClass("active");
  if (category === "all") {
    $(".event-card").show();
  } else {
    $(".event-card").hide();
    $(`.event-card[data-category="${category}"]`).show();
  }
});


