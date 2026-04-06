<?php defined('BASEPATH') OR exit('No direct script access allowed');
function h($s){ 
  return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); 
}

$page = $page ?? 1;
$per_page = $per_page ?? 10;
$total_log = $total_log ?? 0;
$log_api = $log_api ?? [];
$apps = $apps ?? [];
$filter_app = $filter_app ?? '';
$filter_show = $filter_show ?? '10';
$filter_sort = $_GET['sort'] ?? 'desc';
$base_url = strtok($_SERVER['REQUEST_URI'], '?');
$query = $_GET;
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>API Log | Audit Trail</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="<?= base_url('assets/css/audit.css') ?>" rel="stylesheet">
<style>
.top-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.filter-actions {
    display: flex;
    gap: 8px;
    margin-top: 16px;
    flex-wrap: wrap;
}

.stats-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
    border-radius: 12px;
    margin-bottom: 16px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.stat-label {
    font-size: 13px;
    color: #64748b;
}

.stat-value {
    font-size: 18px;
    font-weight: 700;
    color: var(--warning);
}

.precell {
    max-width: 300px;
    white-space: pre-wrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-family: 'Courier New', monospace;
    font-size: 12px;
    background: #f8fafc;
    padding: 8px;
    border-radius: 6px;
}

.method-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.method-get { background: #dbeafe; color: #1e40af; }
.method-post { background: #dcfce7; color: #166534; }
.method-put { background: #fef3c7; color: #92400e; }
.method-delete { background: #fee2e2; color: #991b1b; }

@media (max-width: 768px) {
    .top-actions {
        width: 100%;
    }
    
    .top-actions .btn {
        flex: 1;
        min-width: 120px;
    }
    
    .stats-bar {
        flex-direction: column;
        gap: 12px;
    }
    
    .precell {
        max-width: 150px;
    }
}
</style>
</head>
<body>
<div class="container-wrap">

  <!-- Header -->
  <div class="header">
    <div class="brand">
      <div class="logo">API</div>
      <div>
        <h1>API Log</h1>
        <div class="sub muted">Request & Response Monitoring</div>
      </div>
    </div>

    <div class="top-actions">
      <a href="<?= base_url('audit/home'); ?>" class="btn btn-outline-secondary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
          <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
        </svg>
        Back
      </a>
      <button id="refreshBtn" class="btn btn-outline-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
          <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
        </svg>
        Refresh
      </button>
      <a class="btn btn-secondary" href="<?= site_url('audit/export_log_api_csv') . '?' . h($_SERVER['QUERY_STRING']) ?>">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
          <path d="M19 12v7H5v-7H3v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2zm-6 .67l2.59-2.58L17 11.5l-5 5-5-5 1.41-1.41L11 12.67V3h2z"/>
        </svg>
        Export CSV
      </a>
      <button class="btn btn-danger" onclick="showModal('modalLogout')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
          <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
        </svg>
        Logout
      </button>
    </div>
  </div>

  <!-- Stats Bar -->
  <div class="card-glass">
    <div class="stats-bar">
      <div class="stat-item">
        <span class="stat-label">Total API Calls:</span>
        <span class="stat-value"><?= number_format($total_log) ?></span>
      </div>
      <div class="stat-item">
        <span class="stat-label">Current Page:</span>
        <span class="stat-value"><?= $page ?></span>
      </div>
      <div class="stat-item">
        <span class="stat-label">Per Page:</span>
        <span class="stat-value"><?= $per_page ?></span>
      </div>
    </div>
  </div>

  <!-- Filter -->
  <div class="card-glass">
    <h3 style="margin-bottom: 20px; color: var(--dark); font-size: 18px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="vertical-align: middle; margin-right: 8px;">
        <path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/>
      </svg>
      Filter & Search
    </h3>
    <form id="filterForm" method="get" action="<?= site_url('audit/api_log') ?>">
      <div class="filter-grid">
        <div>
          <label class="form-label">Urutkan</label>
          <select name="sort" class="form-select">
            <option value="">Default</option>
            <option value="desc" <?= (($filter_sort ?? 'desc') == 'desc' ? 'selected' : '') ?>>Terbaru</option>
            <option value="asc"  <?= (($filter_sort ?? 'desc') == 'asc'  ? 'selected' : '') ?>>Terlama</option>
          </select>
        </div>

        <div>
          <label class="form-label">Tanggal Awal</label>
          <input type="date" name="start" class="form-control" value="<?= h($filter_start) ?>" max="<?= date('Y-m-d') ?>">
        </div>

        <div>
          <label class="form-label">Tanggal Akhir</label>
          <input type="date" name="end" class="form-control" value="<?= h($filter_end) ?>" max="<?= date('Y-m-d') ?>">
        </div>

        <div>
          <label class="form-label">Keyword</label>
          <input type="text" name="q" class="form-control" value="<?= h($filter_q ?? '') ?>" placeholder="Cari di request/response...">
        </div>

        <div>
          <label class="form-label">Aplikasi</label>
          <select name="app" class="form-select">
            <option value="">Semua Aplikasi</option>
            <?php foreach ($apps as $ap): ?>
              <option value="<?= h($ap->id_aplikasi) ?>" <?= ((string)$filter_app === (string)$ap->id_aplikasi ? 'selected' : '') ?>>
                <?= h($ap->NM_APLIKASI) ?> (ID: <?= h($ap->id_aplikasi) ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="form-label">Tampilkan</label>
          <select name="show" class="form-select">
            <option value="10" <?= ($filter_show=='10'?'selected':'') ?>>10 Data</option>
            <option value="all" <?= ($filter_show=='all'?'selected':'') ?>>Semua Data</option>
          </select>
        </div>
      </div>

      <div class="filter-actions">
        <button type="submit" class="btn btn-primary">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
            <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
          </svg>
          Terapkan Filter
        </button>
        <a href="<?= site_url('audit/api_log') ?>" class="btn btn-outline-secondary">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
          </svg>
          Reset
        </a>
      </div>
    </form>
  </div>

  <!-- API Log Table -->
  <div class="card-glass">
    <h3 style="margin-bottom: 20px; color: var(--dark); font-size: 18px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="vertical-align: middle; margin-right: 8px;">
        <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
      </svg>
      Data API Log
    </h3>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th style="width:60px">No</th>
            <th>Waktu Akses</th>
            <th>IP Address</th>
            <th style="width:100px">Metode</th>
            <th>Request</th>
            <th>Response</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($log_api)): ?>
            <tr><td colspan="6" style="text-align:center; padding: 40px; color: #94a3b8;">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor" style="opacity: 0.3; margin-bottom: 12px;">
                <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4z"/>
              </svg>
              <div style="font-size: 16px; font-weight: 600;">Tidak ada data</div>
              <div style="font-size: 14px; margin-top: 4px;">Belum ada API log yang tercatat</div>
            </td></tr>
          <?php else:
            $no = ($page -1) * $per_page + 1;
            foreach ($log_api as $l): 
              $method = strtoupper($l->metode ?? 'GET');
              $method_class = 'method-' . strtolower($method);
          ?>
            <tr onclick="showLogDetail(<?= (int)$l->id_log ?>)" style="cursor: pointer;">
              <td style="font-weight: 600;"><?= $no++ ?></td>
              <td><?= h($l->waktu_akses) ?></td>
              <td><strong><?= h($l->ip_address) ?></strong></td>
              <td>
                <span class="method-badge <?= $method_class ?>">
                  <?= h($method) ?>
                </span>
              </td>
              <td>
                <div class="precell">
                  <?= h(preg_replace('/\s+/', ' ', trim($l->request))) ?>
                </div>
              </td>
              <td>
                <div class="precell">
                  <?= h(preg_replace('/\s+/', ' ', trim($l->response))) ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <?php
    $total_pages = max(1, (int)ceil($total_log / $per_page));
    if ($total_pages > 1):
      function build_url($base_url, $query, $page) {
        $query['page'] = $page;
        return $base_url . '?' . htmlspecialchars(http_build_query($query));
      }
      $start = max(1, $page - 2);
      $end   = min($total_pages, $page + 2);
    ?>
    <nav style="margin-top: 24px;">
      <ul class="pagination">
        <li class="page-item <?= ($page <= 1 ? 'disabled' : '') ?>">
          <a class="page-link" href="<?= ($page > 1 ? build_url($base_url, $query, $page - 1) : '#') ?>">Previous</a>
        </li>

        <?php if ($start > 1): ?>
          <li class="page-item"><a class="page-link" href="<?= build_url($base_url, $query, 1) ?>">1</a></li>
          <?php if ($start > 2): ?>
            <li class="page-item disabled"><span class="page-link">…</span></li>
          <?php endif; ?>
        <?php endif; ?>

        <?php for ($p = $start; $p <= $end; $p++): ?>
          <li class="page-item <?= ($p == $page ? 'active' : '') ?>">
            <a class="page-link" href="<?= build_url($base_url, $query, $p) ?>"><?= $p ?></a>
          </li>
        <?php endfor; ?>

        <?php if ($end < $total_pages): ?>
          <?php if ($end < $total_pages - 1): ?>
            <li class="page-item disabled"><span class="page-link">…</span></li>
          <?php endif; ?>
          <li class="page-item"><a class="page-link" href="<?= build_url($base_url, $query, $total_pages) ?>"><?= $total_pages ?></a></li>
        <?php endif; ?>

        <li class="page-item <?= ($page >= $total_pages ? 'disabled' : '') ?>">
          <a class="page-link" href="<?= ($page < $total_pages ? build_url($base_url, $query, $page + 1) : '#') ?>">Next</a>
        </li>
      </ul>
    </nav>
    <?php endif; ?>
  </div>

</div>

<!-- API Log Detail Modal -->
<div class="modal" id="logModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">API Log Detail <small id="logId" style="color: #94a3b8;"></small></h2>
        <button type="button" class="btn btn-outline-secondary" onclick="hideModal('logModal')" style="padding: 8px 16px;">Close</button>
      </div>
      <div class="modal-body">
        <div id="logDetail">Loading...</div>
      </div>
    </div>
  </div>
</div>

<!-- Logout Modal -->
<div class="modal" id="modalLogout">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Konfirmasi Logout</h2>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin keluar dari sistem?
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" onclick="hideModal('modalLogout')">Batal</button>
        <a href="<?= base_url('auth/logout') ?>" class="btn btn-danger">Logout</a>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/js/audit.js') ?>"></script>
<script>
const refreshBtn = document.getElementById('refreshBtn');
const filterForm = document.getElementById('filterForm');
if (refreshBtn && filterForm) {
  refreshBtn.addEventListener('click', () => filterForm.submit());
}

function showLogDetail(id) {
  showModal('logModal');
  document.getElementById('logId').innerText = '#' + id;
  document.getElementById('logDetail').innerHTML = '<div style="text-align:center; padding: 40px;"><div style="width: 40px; height: 40px; border: 4px solid rgba(245,158,11,0.3); border-top-color: var(--warning); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div></div>';

  fetch('<?= site_url('audit/ajax_log_detail') ?>?id=' + encodeURIComponent(id))
    .then(r => r.json())
    .then(d => {
      if (d && d.success && d.data) {
        const a = d.data || {};
        let req = a.request || '{}';
        let res = a.response || '{}';
        
        // Try to format as JSON
        try { req = JSON.stringify(JSON.parse(req), null, 2); } catch(e){}
        try { res = JSON.stringify(JSON.parse(res), null, 2); } catch(e){}

        let html = '<div style="margin-bottom: 20px; padding: 16px; background: #f8fafc; border-radius: 12px;">';
        html += '<div style="display: flex; gap: 24px; flex-wrap: wrap;">';
        html += '<div><strong style="color: #64748b;">Waktu:</strong> <span style="color: var(--dark);">' + (a.waktu_akses ?? '-') + '</span></div>';
        html += '<div><strong style="color: #64748b;">IP Address:</strong> <span style="color: var(--dark);">' + (a.ip_address ?? '-') + '</span></div>';
        html += '<div><strong style="color: #64748b;">Metode:</strong> <span style="color: var(--dark); font-weight: 600;">' + (a.metode ?? '-').toUpperCase() + '</span></div>';
        html += '</div></div>';
        
        html += '<div style="margin-bottom: 20px;">';
        html += '<h4 style="color: var(--dark); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">';
        html += '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg>';
        html += 'Request</h4>';
        html += '<pre style="background: #1e293b; color: #e2e8f0; padding: 16px; border-radius: 12px; overflow-x: auto; margin: 0; font-size: 13px; line-height: 1.6; white-space: pre-wrap; word-wrap: break-word;">' + req + '</pre>';
        html += '</div>';
        
        html += '<div>';
        html += '<h4 style="color: var(--dark); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">';
        html += '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>';
        html += 'Response</h4>';
        html += '<pre style="background: #1e293b; color: #e2e8f0; padding: 16px; border-radius: 12px; overflow-x: auto; margin: 0; font-size: 13px; line-height: 1.6; white-space: pre-wrap; word-wrap: break-word;">' + res + '</pre>';
        html += '</div>';

        document.getElementById('logDetail').innerHTML = html;
      } else {
        document.getElementById('logDetail').innerHTML = '<div style="color: var(--danger); text-align: center; padding: 20px;">Tidak dapat memuat detail</div>';
      }
    }).catch(() => {
      document.getElementById('logDetail').innerHTML = '<div style="color: var(--danger); text-align: center; padding: 20px;">Error loading data</div>';
    });
}

// Date range validation
const startDate = document.querySelector('input[name="start"]');
const endDate = document.querySelector('input[name="end"]');

function updateEndMin() {
  if (startDate.value) {
    endDate.min = startDate.value;
    if (endDate.value && endDate.value < startDate.value) {
      endDate.value = startDate.value;
    }
  } else {
    endDate.min = "";
  }
}

if (startDate && endDate) {
  startDate.addEventListener('change', updateEndMin);
  document.addEventListener('DOMContentLoaded', updateEndMin);
}
</script>
</body>
</html>
