// ==============================
// jobflow/public/assets/js/table.js
// ==============================
window.TableHelper = {
  filter(selector,inputId){
    const rows=[...document.querySelectorAll(selector)];
    const q=(document.getElementById(inputId)?.value||'').toLowerCase();
    rows.forEach(r=>r.style.display=r.textContent.toLowerCase().includes(q)?'':'none');
  }
};
