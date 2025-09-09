<?php ?>
<header class="navbar">
  <div class="navbar-inner container">
    <!-- Brand logo + text -->
    <!-- ✅ tanpa /public -->
    <a href="<?= url('/') ?>" class="brand">
      <img src="<?= url('/assets/img/logo.svg') ?>" alt="JobFlow">
      <span>JobFlow</span>
    </a>

    <!-- Right menu -->
    <nav class="nav-links">
      <!-- ✅ tanpa /public -->
      <a href="<?= url('/jobs') ?>">Jobs</a>
      <a href="<?= url('/dashboard') ?>">Dashboard</a>
      <label title="Dark mode">
        <input id="darkToggle" type="checkbox" class="toggle">
      </label>
    </nav>
  </div>
</header>

<script>
(function(){
  const key='jf_dark';
  const root=document.documentElement;
  const setMode = v => { root.classList.toggle('dark', v); localStorage.setItem(key, v?'1':'0'); };
  const init = localStorage.getItem(key)==='1';
  setMode(init);
  document.addEventListener('DOMContentLoaded',()=>{
    const t=document.getElementById('darkToggle');
    if(t){ t.checked = init; t.addEventListener('change', ()=> setMode(t.checked)); }
  });
})();
</script>
