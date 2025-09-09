<?php
// app/views/auth/login.php — Login clean, centered, modern
$action = url('/login');
$email  = $old['email'] ?? '';
?>
<section style="min-height:calc(100vh - 120px);display:flex;align-items:center;justify-content:center;padding:28px">
  <div class="card" style="width:100%;max-width:720px;border-radius:18px;padding:0">
    <div style="display:grid;grid-template-columns:1fr;gap:0">
      <!-- Left / Hero (hidden on small? keep minimal so tetap ringan) -->
      <div style="padding:24px 24px 6px 24px">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
          <img src="<?= url('/assets/img/logo.svg') ?>" alt="" style="width:28px;height:28px">
          <h1 class="card-title" style="margin:0;font-size:1.35rem;line-height:1.2">Login</h1>
        </div>
        <p class="text-muted" style="margin:0 0 14px;font-size:.98rem">
          Masuk ke <strong>Dashboard HR JobFlow</strong> untuk mengelola lowongan & kandidat.
        </p>

        <?php if (!empty($error)): ?>
          <div style="margin:0 0 14px;padding:10px 12px;border-radius:10px;background:#151a2c;border-left:4px solid #ef4444;color:#e5e7eb">
            <?= h($error) ?>
          </div>
        <?php endif; ?>

        <form action="<?= $action ?>" method="post" style="display:grid;gap:12px">
          <?= csrf_field() ?>

          <div>
            <label for="email" class="text-muted" style="display:block;margin-bottom:6px">Email</label>
            <input id="email"
                   class="input"
                   type="email"
                   name="email"
                   placeholder="email@company.com"
                   value="<?= h($email) ?>"
                   autocomplete="username"
                   required
                   style="width:100%;height:42px">
          </div>

          <div>
            <label for="password" class="text-muted" style="display:block;margin-bottom:6px">Password</label>
            <div style="position:relative">
              <input id="password"
                     class="input"
                     type="password"
                     name="password"
                     placeholder="••••••••"
                     autocomplete="current-password"
                     required
                     style="width:100%;height:42px;padding-right:88px">
              <button type="button"
                      id="togglePass"
                      class="btn secondary"
                      aria-label="Tampilkan/Sembunyikan password"
                      style="position:absolute;right:6px;top:50%;transform:translateY(-50%);height:32px;padding:0 10px;border-radius:10px">
                Lihat
              </button>
            </div>
          </div>

          <button class="btn" type="submit" style="margin-top:4px;height:44px">Masuk</button>
        </form>

        <p class="text-muted" style="margin:12px 0 6px;font-size:.9rem">
          Gunakan akun admin/HR yang sudah terdaftar.
        </p>
      </div>
    </div>
  </div>
</section>

<script>
  // Toggle show/hide password — rapi & tidak menutupi tombol submit
  (function(){
    const btn = document.getElementById('togglePass');
    const input = document.getElementById('password');
    if (btn && input) {
      btn.addEventListener('click', function(){
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.textContent = show ? 'Sembunyikan' : 'Lihat';
      });
    }
    // Focus ke email saat load
    window.addEventListener('DOMContentLoaded', () => {
      const em = document.getElementById('email');
      if (em && !em.value) em.focus();
    });
  })();
</script>
