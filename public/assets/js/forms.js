// ==============================
// jobflow/public/assets/js/forms.js
// ==============================
window.FormHelper = {
  validEmail(v){return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);},
  showError(id,msg){const el=document.getElementById(id); if(el){el.textContent=msg; el.style.display=msg?'block':'none';}}
};
