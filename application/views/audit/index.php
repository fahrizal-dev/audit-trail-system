<?php
$page = isset($page) && intval($page) > 0 ? intval($page) : 1;
$per_page = isset($per_page) && intval($per_page) > 0 ? intval($per_page) : 10;
$total_activity = isset($total_activity) ? intval($total_activity) : 0;
$total_log = isset($total_log) ? intval($total_log) : 0;
$activity = isset($activity) ? $activity : [];
$log_api = isset($log_api) ? $log_api : [];
$apps = isset($apps) ? $apps : [];
$filter_user = isset($filter_user) ? $filter_user : '';
$filter_aksi = isset($filter_aksi) ? $filter_aksi : '';
$filter_start = isset($filter_start) ? $filter_start : '';
$filter_end = isset($filter_end) ? $filter_end : '';
$filter_q = isset($filter_q) ? $filter_q : '';
$filter_app = isset($filter_app) ? $filter_app : '';
$filter_ip = isset($filter_ip) ? $filter_ip : '';
$filter_hasil = isset($filter_hasil) ? $filter_hasil : '';
$base_url = strtok($_SERVER['REQUEST_URI'], '?');
$query = $_GET;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Audit Trail • Admin</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg1: #e8f0ff;
      --bg2: #f7fbff;
      --glass: rgba(255,255,255,0.6);
      --accent: #2a6df6;
      --muted: #6b7280;
      --card-radius: 14px;
      --glass-border: rgba(42,109,246,0.12);
      --success: #16a34a;
      --danger: #ef4444;
    }
    [data-theme="dark"]{
      --bg1:#071020;
      --bg2:#071826;
      --glass: rgba(12,18,28,0.6);
      --accent: #4ea8ff;
      --muted:#9aa4b2;
      --glass-border: rgba(78,168,255,0.08);
    }

html,
body {
  height: 100%;
  margin: 0;
  font-family: "Inter", system-ui, -apple-system, "Segoe UI",
               Roboto, "Helvetica Neue", Arial;
  background: linear-gradient(180deg, var(--bg1), var(--bg2));
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

.wrap {
  max-width: 1200px;
  margin: 28px auto;
  padding: 18px;
  box-sizing: border-box;
}

/* =========================
   Header
========================= */
.header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 12px;
  margin-bottom: 18px;
}

.brand {
  display: flex;
  gap: 12px;
  align-items: center;
}

.logo {
  width: 52px;
  height: 52px;
  border-radius: 12px;
  background: linear-gradient(135deg, #4ea8ff, #8b5cf6);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-weight: 700;
  box-shadow: 0 8px 30px rgba(42, 109, 246, 0.16);
}

h1 {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 600;
}

.sub {
  margin-top: 4px;
  font-size: 0.92rem;
  color: var(--muted);
}

/* =========================
   Glass Card
========================= */
.card-glass {
  background: linear-gradient(
    180deg,
    var(--glass),
    rgba(255, 255, 255, 0.35)
  );
  border-radius: var(--card-radius);
  padding: 14px;
  border: 1px solid var(--glass-border);
  box-shadow: 0 8px 30px rgba(12, 24, 48, 0.06);
}

/* =========================
   Filter Area
========================= */
.filter-grid {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 12px;
  align-items: end;
}

.filter-grid .form-label {
  margin-bottom: 6px;
  font-size: 0.85rem;
  color: var(--muted);
}

/* =========================
   Tables
========================= */
.table-wrap {
  margin-top: 12px;
}

.table thead th {
  background: transparent;
  border-bottom: none;
  font-weight: 600;
  color: #111827;
}

.table tbody tr:hover {
  background: rgba(42, 109, 246, 0.03);
}

.status-pill {
  display: inline-block;
  padding: 6px 10px;
  border-radius: 999px;
  font-size: 0.85rem;
  color: #fff;
}

/* Prevent long text issues */
td {
  vertical-align: middle;
}

td .short {
  display: block;
  max-width: 300px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

td pre {
  margin: 0;
  padding: 6px;
  max-height: 160px;
  overflow: auto;
  white-space: pre-wrap;
  word-break: break-word;
  background: transparent;
  border-radius: 8px;
}

/* =========================
   Pagination
========================= */
.pagination {
  margin: 0;
}

/* =========================
   Utilities
========================= */
.muted {
  font-size: 0.92rem;
  color: var(--muted);
}

.top-actions {
  display: flex;
  gap: 8px;
  align-items: center;
}

/* =========================
   Responsive
========================= */
@media (max-width: 992px) {
  .filter-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .header {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }
}

@media (max-width: 768px) {
  .logo {
    width: 44px;
    height: 44px;
  }

  .filter-grid {
    gap: 8px;
  }

  .table thead th:nth-child(6),
  .table thead th:nth-child(7) {
    display: none;
  }
}

@media (max-width: 576px) {
  .filter-grid {
    grid-template-columns: 1fr;
  }
}
  </style>
</head>
<body data-theme="">
  <div class="container mt-2">
      <?php if($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
              <?= $this->session->flashdata('success'); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
      <?php endif; ?>

      <?php if($this->session->flashdata('error')): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <?= $this->session->flashdata('error'); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
      <?php endif; ?>
  </div>

  <script>
      setTimeout(function() {
          var alertNode = document.querySelector('.alert');
          if (alertNode) {
              // animasi fade out
              alertNode.style.transition = "opacity 0.5s";
              alertNode.style.opacity = "0";

              // hapus dari DOM setelah animasi
              setTimeout(function() {
                  alertNode.remove();
              }, 500);
          }
      }, 5000);
  </script>

<div class="wrap">

  <!-- HEADER -->
  <div class="header">
    <div class="brand">
      <div class="logo">AT</div>
      <div>
        <h1>Audit Trail Admin</h1>
        <div class="sub muted">Activity & API logging</div>
      </div>
    </div>

    <div class="top-actions">
      <button id="refreshBtn" class="btn btn-outline-primary"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
      <a class="btn btn-outline-success" href="<?= site_url('audit/export_activity_csv') . '?' . $_SERVER['QUERY_STRING'] ?>"><i class="bi bi-file-earmark-csv"></i> Export Activity</a>
      <a class="btn btn-outline-secondary" href="<?= site_url('audit/export_log_api_csv') . '?' . $_SERVER['QUERY_STRING'] ?>"><i class="bi bi-file-earmark-csv"></i> Export API</a>
      <!-- <button id="themeToggle" class="btn btn-light">Theme</button> -->
      <a href="<?= base_url('auth/logout'); ?>" class="btn btn-outline-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
  </div>

  <div class="card-glass">
    <form id="filterForm" method="get" action="<?= site_url('audit') ?>">
      <div class="filter-grid">

        <div>
          <label class="form-label">App</label>
          <select name="app" class="form-select">
            <option value="">All Applications</option>
            <?php if (!empty($apps)): foreach ($apps as $ap): ?>
              <option value="<?= htmlspecialchars($ap->id_aplikasi) ?>" <?= ($filter_app == $ap->id_aplikasi ? 'selected' : '') ?>>
                <?= htmlspecialchars($ap->NM_APLIKASI . ' (' . $ap->user_name . ')') ?>
              </option>
            <?php endforeach; endif; ?>
          </select>
        </div>

        <div>
          <label class="form-label">User</label>
          <input name="user" class="form-control" placeholder="user" value="<?= htmlspecialchars($filter_user) ?>">
        </div>

        <div>
          <label class="form-label">Aksi</label>
          <select name="aksi" class="form-select">
            <option value="">All Actions</option>
            <option value="REGISTER" <?= ($filter_aksi=='REGISTER' ? 'selected':'') ?>>REGISTER</option>
            <option value="LOGIN"  <?= ($filter_aksi=='LOGIN' ? 'selected':'') ?>>LOGIN</option>
            <option value="LOGOUT" <?= ($filter_aksi=='LOGOUT' ? 'selected':'') ?>>LOGOUT</option>
            <option value="CREATE" <?= ($filter_aksi=='CREATE' ? 'selected':'') ?>>CREATE</option>
            <option value="UPDATE" <?= ($filter_aksi=='UPDATE' ? 'selected':'') ?>>UPDATE</option>
            <option value="DELETE" <?= ($filter_aksi=='DELETE' ? 'selected':'') ?>>DELETE</option>
            <option value="API CALL" <?= ($filter_aksi=='API CALL' ? 'selected':'') ?>>API CALL</option>
          </select>
        </div>

        <div>
          <label class="form-label">Hasil</label>
          <select name="hasil" class="form-select">
            <option value="">All</option>
            <option value="success" <?= ($filter_hasil=='success' ? 'selected':'') ?>>Success</option>
            <option value="failed"  <?= ($filter_hasil=='failed'  ? 'selected':'') ?>>Failed</option>
            <option value="Wrong_password" <?= ($filter_hasil=='Wrong_password'  ? 'selected':'') ?>>Wrong Password</option>
            <option value="Not_found" <?= ($filter_hasil=='Not_found'  ? 'selected':'') ?>>Not found</option>
          </select>
        </div>

        <div>
            <label class="form-label">Tanggal Awal</label>
            <input type="date" name="start" class="form-control"
                   value="<?= isset($filter_start) ? htmlspecialchars($filter_start) : '' ?>">
        </div>

        <div>
            <label class="form-label">Tanggal Akhir</label>
            <input type="date" name="end" class="form-control"
                   value="<?= isset($filter_end) ? htmlspecialchars($filter_end) : '' ?>">
        </div>

        <div>
          <label class="form-label">Keyword</label>
          <input name="q" class="form-control" placeholder="search ket / trx_id" value="<?= htmlspecialchars($filter_q) ?>">
        </div>

        <div style="grid-column: 1 / -1; display:flex; justify-content:flex-end; gap:8px; margin-top:8px;">
          <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Apply</button>
          <a class="btn btn-outline-secondary" href="<?= site_url('audit') ?>">Reset</a>
        </div>

      </div>
    </form>
  </div>

  <div class="card-glass mt-4">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:12px;">
      <div>
        <strong style="font-size:1.05rem">Activity Log</strong>
        <div class="muted">Total: <?= number_format($total_activity) ?> • Page <?= $page ?></div>
      </div>
      <div class="muted">Per page: <?= $per_page ?></div>
    </div>

    <div class="table-wrap">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th style="width:64px">No</th>
              <th>Waktu</th>
              <th>User</th>
              <th>Menu</th>
              <th>Aksi</th>
              <th style="width:120px">Status</th>
              <th>Keterangan</th>
              <th>IP</th>
            </tr>
          </thead>
          <tbody id="activity-table-body">
            <?php
            $start_no = ($page - 1) * $per_page + 1;
            if (empty($activity)): ?>
              <tr><td colspan="8" class="text-center muted">No records</td></tr>
            <?php else:
              $no = $start_no;
              foreach ($activity as $a):
                $user = isset($a->user) ? $a->user : ($a->user_name ?? 'unknown');
                $status = isset($a->hasil) ? $a->hasil : 'unknown';
                $badge_color = $status === 'success' ? 'var(--success)' : 'var(--danger)';
            ?>
            <tr class="pointer" onclick="showActivityDetail(<?= (int)$a->id_activity ?>)">
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($a->modidate) ?></td>
              <td><span class="short"><?= htmlspecialchars($user) ?></span></td>
              <td><span class="short"><?= htmlspecialchars($a->menu_fitur) ?></span></td>
              <td><?= htmlspecialchars($a->aksi) ?></td>
              <td><span class="status-pill" style="background:<?= $badge_color ?>; color:white;"><?= ucfirst(htmlspecialchars($status)) ?></span></td>
              <td style="max-width:300px;"><span class="short"><?= htmlspecialchars(mb_substr($a->ket ?? '', 0, 120)) ?><?= (mb_strlen($a->ket ?? '') > 120 ? '...' : '') ?></span></td>
              <td><?= htmlspecialchars($a->ip_address) ?></td>
            </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>

      <?php
      $total_pages = max(1, (int)ceil($total_activity / $per_page));
      if ($total_pages > 1): ?>
      <nav class="mt-3" aria-label="activity-pagination">
        <ul class="pagination">
          <?php for ($p = 1; $p <= $total_pages; $p++):
            $query['page'] = $p; $qs = http_build_query($query);
          ?>
            <li class="page-item <?= ($p == $page ? 'active' : '') ?>"><a class="page-link" href="<?= $base_url . '?' . $qs ?>"><?= $p ?></a></li>
          <?php endfor; ?>
        </ul>
      </nav>
      <?php endif; ?>
    </div>
  </div>

  <div class="card-glass mt-4">
    <div style="display:flex; justify-content:space-between; align-items:center;">
      <strong>API Log</strong>
      <div class="muted">Total: <?= number_format($total_log) ?></div>
    </div>

    <div class="table-responsive mt-2">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th style="width:64px">No</th>
            <th>Waktu</th>
            <th>IP</th>
            <th>Metode</th>
            <th>Request</th>
            <th>Response</th>
          </tr>
        </thead>
        <tbody id="api-table-body">
          <?php
          $noLog = ($page - 1) * $per_page + 1;
          if (empty($log_api)): ?>
            <tr><td colspan="6" class="text-center muted">No records</td></tr>
          <?php else:
            foreach ($log_api as $l): ?>
              <tr>
                <td><?= $noLog++ ?></td>
                <td><?= htmlspecialchars($l->waktu_akses) ?></td>
                <td><?= htmlspecialchars($l->ip_address) ?></td>
                <td><?= htmlspecialchars($l->metode) ?></td>
                <td style="max-width:240px;"><pre class="mb-0"><?= htmlspecialchars($l->request) ?></pre></td>
                <td style="max-width:240px;"><pre class="mb-0"><?= htmlspecialchars($l->response) ?></pre></td>
              </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>

    <?php
    $total_log_pages = max(1, (int)ceil($total_log / $per_page));
    if ($total_log_pages > 1): ?>
      <nav class="mt-3" aria-label="log-pagination">
        <ul class="pagination justify-content-end">
          <?php for ($p=1; $p <= $total_log_pages; $p++): $query['page'] = $p; $qs = http_build_query($query); ?>
            <li class="page-item <?= ($p == $page ? 'active' : '') ?>"><a class="page-link" href="<?= $base_url . '?' . $qs ?>"><?= $p ?></a></li>
          <?php endfor; ?>
        </ul>
      </nav>
    <?php endif; ?>

  </div>

</div>

<!-- ACTIVITY DETAIL MODAL -->
<div class="modal fade" id="activityModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Activity Detail <small id="activityId" class="text-muted"></small></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="activityDetail">Loading…</div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- LOG DETAIL MODAL -->
<div class="modal fade" id="logModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">API Log Detail <small id="logId" class="text-muted"></small></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="logDetail">Loading…</div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
  // helpers
  function escapeHtml(s) {
    if (s === null || s === undefined) return '';
    return String(s).replace(/[&<>"'`=\/]/g, function (c) {
      return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'}[c];
    });
  }

  // UI bindings
  const refreshBtn = document.getElementById('refreshBtn');
  const filterForm = document.getElementById('filterForm');
  const themeBtn = document.getElementById('themeToggle');
  const body = document.body;

  if (refreshBtn && filterForm) refreshBtn.addEventListener('click', ()=> filterForm.submit());
  if (themeBtn) {
    const saved = localStorage.getItem('audit_theme') || '';
    body.setAttribute('data-theme', saved);
    themeBtn.textContent = saved === 'dark' ? 'Light' : 'Dark';
    themeBtn.addEventListener('click', ()=>{
      const now = body.getAttribute('data-theme') || '';
      const next = now === 'dark' ? '' : 'dark';
      body.setAttribute('data-theme', next);
      localStorage.setItem('audit_theme', next);
      themeBtn.textContent = next === 'dark' ? 'Light' : 'Dark';
    });
  }

  // smooth scroll to top on pagination click
  document.addEventListener('click', function(e){
    if (e.target.closest('.pagination a')) window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  // AJAX: show activity detail
  window.showActivityDetail = function(id) {
    const modalEl = document.getElementById('activityModal');
    const modal = new bootstrap.Modal(modalEl);
    document.getElementById('activityId').innerText = '#' + id;
    document.getElementById('activityDetail').innerHTML = 'Loading...';
    modal.show();

    fetch('<?= site_url('audit/ajax_activity_detail') ?>?id=' + encodeURIComponent(id))
      .then(r => r.json())
      .then(d => {
        if (d && d.success && d.data) {
          const a = d.data;
          const user = a.user ?? a.user_name ?? '';
          let html = '<table class="table table-sm">';
          html += '<tr><th style="width:180px">ID</th><td>' + (a.id_activity ?? '') + '</td></tr>';
          html += '<tr><th>Waktu</th><td>' + escapeHtml(a.modidate ?? '') + '</td></tr>';
          html += '<tr><th>User</th><td>' + escapeHtml(user) + '</td></tr>';
          html += '<tr><th>Menu</th><td>' + escapeHtml(a.menu_fitur ?? '') + '</td></tr>';
          html += '<tr><th>Aksi</th><td>' + escapeHtml(a.aksi ?? '') + '</td></tr>';
          html += '<tr><th>Keterangan</th><td><pre>' + escapeHtml(a.ket ?? '') + '</pre></td></tr>';
          html += '<tr><th>IP</th><td>' + escapeHtml(a.ip_address ?? '') + '</td></tr>';
          html += '</table>';
          document.getElementById('activityDetail').innerHTML = html;
        } else {
          document.getElementById('activityDetail').innerHTML = '<div class="text-danger">Tidak dapat memuat detail</div>';
        }
      }).catch(() => {
        document.getElementById('activityDetail').innerHTML = '<div class="text-danger">Error loading</div>';
      });
  };

  // AJAX: show log detail
  window.showLogDetail = function(id) {
    const modalEl = document.getElementById('logModal');
    const modal = new bootstrap.Modal(modalEl);
    document.getElementById('logId').innerText = '#' + id;
    document.getElementById('logDetail').innerHTML = 'Loading...';
    modal.show();

    fetch('<?= site_url('audit/ajax_log_detail') ?>?id=' + encodeURIComponent(id))
      .then(r => r.json())
      .then(d => {
        if (d && d.success && d.data) {
          const a = d.data;
          let req = a.request || '{}';
          let res = a.response || '{}';
          try { req = JSON.stringify(JSON.parse(req), null, 2); } catch(e){ /* not JSON */ }
          try { res = JSON.stringify(JSON.parse(res), null, 2); } catch(e){}
          let html = '<h6>Request</h6><pre>' + escapeHtml(req) + '</pre>';
          html += '<h6 class="mt-3">Response</h6><pre>' + escapeHtml(res) + '</pre>';
          document.getElementById('logDetail').innerHTML = html;
        } else {
          document.getElementById('logDetail').innerHTML = '<div class="text-danger">Tidak dapat memuat detail</div>';
        }
      }).catch(() => {
        document.getElementById('logDetail').innerHTML = '<div class="text-danger">Error loading</div>';
      });
  };

  // Date range helpers
  document.addEventListener("DOMContentLoaded", function () {
    const startInput = document.querySelector('input[name="start"]');
    const endInput   = document.querySelector('input[name="end"]');
    if (startInput && endInput) {
      startInput.addEventListener("change", function () {
        endInput.min = startInput.value;
        if (endInput.value && endInput.value < startInput.value) {
          endInput.value = startInput.value;
        }
      });
    }

    window.scrollTo({ top: 0, behavior: "smooth" });
  });

  const refreshIntervalMs = 3000; 
  async function refreshActivity() {
    try {
      const response = await fetch('<?= base_url("audit/get_activity_ajax") ?>');
      if (!response.ok) return;
      const data = await response.text();
      const tbody = document.getElementById('activity-table-body');
      if (tbody) tbody.innerHTML = data;
    } catch (e) {
      console.error('Gagal refresh tabel:', e);
    }
  }
  refreshActivity();
  setInterval(refreshActivity, refreshIntervalMs);

})();

setInterval(function() {

    let url = "<?= site_url('audit/ajax_refresh_tables') ?>" + window.location.search;

    fetch(url)
      .then(r => r.json())
      .then(data => {
          // Update tabel activity
          if (data.activity_html) {
              document.getElementById('activity-table-body').innerHTML = data.activity_html;
          }
          // Update tabel log API
          if (data.api_html) {
              document.getElementById('api-table-body').innerHTML = data.api_html;
          }
      })
      .catch(err => console.error('Auto refresh error:', err));

}, 3000); 
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>