<!-- ==============================
     jobflow/README.md
============================== -->
# JobFlow (PHP + MySQL)
- Copy folder `jobflow/` ke `htdocs`.
- Import DB `jobflow_db` (sudah kamu buat).
- Salin `.env.example` -> `.env` dan sesuaikan kredensial.
- Akses: `http://localhost/jobflow` (publik), `http://localhost/jobflow/login` (dashboard).
- Login pakai user yang ada di tabel `users`. Jika `password_hash` kosong untuk admin/hr, login tetap diizinkan (mode awal).
