
document.addEventListener("DOMContentLoaded", function () {
  const body = document.body;
  const themeToggle = document.getElementById("themeToggle");
  const themeIcon = document.getElementById("themeIcon");

  // 🔆 تطبيق الوضع الحالي
  function applyTheme(theme) {
    body.classList.remove("light-mode", "dark-mode");
    body.classList.add(theme === "dark" ? "dark-mode" : "light-mode");

    if (themeIcon) {
      themeIcon.className =
        theme === "dark" ? "fa-solid fa-sun" : "fa-regular fa-moon";
    }
  }

  // 🧠 تحميل الوضع من localStorage أو تحديد الافتراضي
  let theme = localStorage.getItem("theme") || "light";
  applyTheme(theme);

  // 🌓 التبديل عند الضغط على الزر
  if (themeToggle) {
    themeToggle.addEventListener("click", () => {
      theme = theme === "light" ? "dark" : "light";
      localStorage.setItem("theme", theme);
      applyTheme(theme);

      // ✨ إضافة تأثير ناعم عند التبديل
      body.style.transition = "background-color 0.5s ease, color 0.5s ease";
    });
  }

  // 🎯 الفلترة حسب التصنيف
  const filterBtns = document.querySelectorAll(".filter-btn");
  const eventCards = document.querySelectorAll(".event-card");

  filterBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const cat = btn.getAttribute("data-cat");

      filterBtns.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");

      eventCards.forEach((card) => {
        const match = cat === "all" || card.dataset.category === cat;
        card.style.display = match ? "" : "none";
      });
    });
  });

  // 🔍 البحث عن الفعاليات
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("input", () => {
      const q = searchInput.value.trim().toLowerCase();
      eventCards.forEach((c) => {
        const title = c.querySelector(".card-title")?.innerText.toLowerCase() || "";
        const desc = c.querySelector(".card-text")?.innerText.toLowerCase() || "";
        c.style.display = title.includes(q) || desc.includes(q) ? "" : "none";
      });
    });
  }

  // 🌐 أيقونة اللغة
  const langToggle = document.getElementById("langToggle");
  const langIcon = document.getElementById("langIcon");
  if (langToggle && langIcon) {
    if (window.location.search.includes("lang=en")) {
      langIcon.className = "fa-solid fa-language";
    } else {
      langIcon.className = "fa-solid fa-globe";
    }
  }

  // 🚀 AOS Animation
  if (typeof AOS !== "undefined") {
    AOS.init({
      duration: 700,
      once: true,
    });
  }

  // ⬆️ زر الصعود للأعلى
  const scrollBtn = document.getElementById("scrollTopBtn");
  if (scrollBtn) {
    window.addEventListener("scroll", () => {
      scrollBtn.style.display = window.scrollY > 300 ? "block" : "none";
    });
    scrollBtn.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }

  // 📩 نموذج التواصل
  const contactForm = document.getElementById("contactForm");
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const btn = this.querySelector("button");
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

      setTimeout(() => {
        alert("✅ تم إرسال الرسالة بنجاح!");
        this.reset();
        btn.disabled = false;
        btn.innerHTML = "إرسال";
      }, 1500);
    });
  }
});
