// ==============================
// jobflow/public/assets/js/main.js
// ==============================
(() => {
  const toggle = document.querySelector('[data-dark-toggle]');
  if (toggle) toggle.addEventListener('click', () => {
    document.documentElement.classList.toggle('light');
    localStorage.setItem('theme', document.documentElement.classList.contains('light') ? 'light' : 'dark');
  });
  if (localStorage.getItem('theme') === 'light') document.documentElement.classList.add('light');
})();
