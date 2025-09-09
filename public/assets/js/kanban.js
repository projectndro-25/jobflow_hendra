// ==============================
// jobflow/public/assets/js/kanban.js
// ==============================
window.Pipeline = (() => {
  function basePublic() {
    // Ambil prefix ".../public" dari path sekarang
    // contoh: /jobflow/public/xxx => /jobflow/public
    const m = location.pathname.match(/^(.*\/public)\b/);
    return m ? m[1] : '';
  }

  async function move(appId, toStatus) {
    const endpoint = basePublic() + '/dashboard/pipeline/status';

    // Ambil token CSRF dari <meta> atau <input hidden>
    const meta = document.querySelector('meta[name="csrf"]');
    const hidden = document.querySelector('input[name="csrf"]');
    const csrf = (meta && meta.content) || (hidden && hidden.value) || '';

    const res = await fetch(endpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'fetch',
        'X-CSRF-Token': csrf
      },
      body: JSON.stringify({ application_id: appId, to_status: toStatus, csrf })
    });
    return res.json();
  }

  // URL helper (bisa dipakai di tempat lain jika perlu)
  function detailUrl(id){ return basePublic() + '/dashboard/applications/' + id; }
  function scheduleUrl(id){ return basePublic() + '/dashboard/schedules/create?application_id=' + id; }
  function mailUrl(id){ return detailUrl(id) + '#email'; }

  return { move, base: basePublic, detailUrl, scheduleUrl, mailUrl };
})();
