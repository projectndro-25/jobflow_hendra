<?php ?>
<div class="p-6">
  <div class="text-lg font-semibold mb-4">Menu</div>
  <ul class="space-y-2">
    <!-- ✅ tanpa /public -->
    <li><a href="<?= url('/dashboard') ?>">Overview</a></li>
    <li><a href="<?= url('/dashboard/jobs') ?>">Jobs</a></li>
    <li><a href="<?= url('/dashboard/candidates') ?>">Candidates</a></li>
    <li><a href="<?= url('/dashboard/applications') ?>">Applications</a></li>
    <li><a href="<?= url('/dashboard/reports') ?>">Reports</a></li>
  </ul>
</div>
