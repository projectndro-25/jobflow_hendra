<section class="dashboard flex min-h-screen">
  <aside class="sidebar w-64 bg-[#12172a] text-gray-300 flex flex-col p-6 border-r border-gray-700" aria-label="Sidebar">
    <nav class="menu flex flex-col gap-3">
      <a href="#" class="menu-item active flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-semibold shadow-md">📊 Overview</a>
      <a href="<?= url('/dashboard/jobs') ?>" class="menu-item flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-[#1e2438] transition">💼 Jobs</a>
      <a href="<?= url('/dashboard/candidates') ?>" class="menu-item flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-[#1e2438] transition">👤 Candidates</a>
      <a href="<?= url('/dashboard/applications') ?>" class="menu-item flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-[#1e2438] transition">📄 Applications</a>
      <a href="<?= url('/dashboard/reports') ?>" class="menu-item flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-[#1e2438] transition">📈 Reports</a>
    </nav>
  </aside>

  <div class="content flex-1 p-8">
    <div class="grid-cards grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="stat-card bg-[#1e2438] rounded-2xl p-6 shadow-lg hover:shadow-xl transition">
        <h3 class="text-gray-400 mb-2">Open Jobs</h3>
        <p class="stat-number text-4xl font-extrabold bg-gradient-to-r from-purple-500 to-indigo-400 bg-clip-text text-transparent">0</p>
      </div>
      <div class="stat-card bg-[#1e2438] rounded-2xl p-6 shadow-lg hover:shadow-xl transition">
        <h3 class="text-gray-400 mb-2">Candidates</h3>
        <p class="stat-number text-4xl font-extrabold bg-gradient-to-r from-pink-500 to-purple-400 bg-clip-text text-transparent">0</p>
      </div>
      <div class="stat-card bg-[#1e2438] rounded-2xl p-6 shadow-lg hover:shadow-xl transition">
        <h3 class="text-gray-400 mb-2">Applications</h3>
        <p class="stat-number text-4xl font-extrabold bg-gradient-to-r from-green-400 to-emerald-500 bg-clip-text text-transparent">0</p>
      </div>
    </div>
  </div>
</section>
