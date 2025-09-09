<?php
$jobsUrl  = url('/jobs');
$applyUrl = url('/apply');   // ✅ sekarang ke halaman daftar apply
$dashUrl  = url('/dashboard');
?>
<section class="hero container">
  <h1>Temukan Karier Impianmu</h1>
  <p>Platform sederhana untuk mengelola lowongan, kandidat, dan proses rekrutmen.</p>

  <div class="cards">
    <article class="card-cta">
      <h3 class="title">Lihat Lowongan</h3>
      <p class="desc">Jelajahi posisi yang sedang dibuka.</p>
      <svg class="arrow" width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 5l7 7-7 7" stroke="#6d5dfc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      <a class="full-link" href="<?= $jobsUrl ?>" aria-label="Lihat Lowongan"></a>
    </article>

    <article class="card-cta">
      <h3 class="title">Lamar Sekarang</h3>
      <p class="desc">Ajukan lamaran untuk posisi pilihanmu.</p>
      <svg class="arrow" width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 5l7 7-7 7" stroke="#6d5dfc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      <a class="full-link" href="<?= $applyUrl ?>" aria-label="Lamar Sekarang"></a>
    </article>

    <article class="card-cta">
      <h3 class="title">Masuk HR</h3>
      <p class="desc">Kelola job, kandidat, dan pipeline.</p>
      <svg class="arrow" width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 5l7 7-7 7" stroke="#6d5dfc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      <a class="full-link" href="<?= $dashUrl ?>" aria-label="Masuk HR"></a>
    </article>
  </div>
</section>
