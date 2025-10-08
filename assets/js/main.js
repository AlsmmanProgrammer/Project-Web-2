// main.js

document.addEventListener('DOMContentLoaded', function() {
  // THEME: init from localStorage
  const body = document.body;
  const themeToggle = document.getElementById('themeToggle');
  const themeIcon = document.getElementById('themeIcon');

  function applyTheme(theme) {
    if (theme === 'dark') {
      body.classList.add('dark-mode');
      body.classList.remove('light-mode');
      if (themeIcon) themeIcon.className = 'fa-solid fa-sun';
    } else {
      body.classList.add('light-mode');
      body.classList.remove('dark-mode');
      if (themeIcon) themeIcon.className = 'fa-regular fa-moon';
    }
  }

  let theme = localStorage.getItem('theme') || 'light';
  applyTheme(theme);

  if (themeToggle) {
    themeToggle.addEventListener('click', () => {
      theme = (theme === 'light') ? 'dark' : 'light';
      localStorage.setItem('theme', theme);
      applyTheme(theme);
    });
  }

  // FILTER: by category buttons
  const filterBtns = document.querySelectorAll('.filter-btn');
  const eventCards = document.querySelectorAll('.event-card');
  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.getAttribute('data-cat');
      if (cat === 'all') {
        eventCards.forEach(c => c.style.display = '');
      } else {
        eventCards.forEach(c => {
          c.style.display = (c.getAttribute('data-category') === cat) ? '' : 'none';
        });
      }
    });
  });

  // SEARCH
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('input', () => {
      const q = searchInput.value.trim().toLowerCase();
      eventCards.forEach(c => {
        const title = c.querySelector('.card-title') ? c.querySelector('.card-title').innerText.toLowerCase() : '';
        const desc = c.querySelector('.card-text') ? c.querySelector('.card-text').innerText.toLowerCase() : '';
        c.style.display = (title.includes(q) || desc.includes(q)) ? '' : 'none';
      });
    });
  }
});

// LANGUAGE ICON CHANGE
const langToggle = document.getElementById('langToggle');
const langIcon = document.getElementById('langIcon');
if (langToggle && langIcon) {
  if (window.location.search.includes('lang=en')) {
    langIcon.className = 'fa-solid fa-language';
  } else {
    langIcon.className = 'fa-solid fa-globe';
  }
}




