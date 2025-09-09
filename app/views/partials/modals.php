<?php /* modal sederhana; show/hide via JS (main.js) */
?>
<div id="modal" style="position:fixed;inset:0;background:rgba(0,0,0,.4);display:none;align-items:center;justify-content:center">
  <div class="card" style="max-width:480px">
    <div id="modal-title" class="card-title">Konfirmasi</div>
    <div id="modal-body" class="text-muted">Yakin ingin melakukan aksi ini?</div>
    <div style="display:flex;gap:.5rem;margin-top:1rem">
      <button class="btn" id="modal-ok">Ya</button>
      <button class="btn secondary" id="modal-cancel">Batal</button>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded',()=>{
  const m=document.getElementById('modal');
  window.showModal=(t,b,ok)=>{document.getElementById('modal-title').textContent=t;
    document.getElementById('modal-body').textContent=b;m.style.display='flex';
    document.getElementById('modal-ok').onclick=()=>{m.style.display='none'; ok&&ok();};
    document.getElementById('modal-cancel').onclick=()=>m.style.display='none';
  };
});
</script>
