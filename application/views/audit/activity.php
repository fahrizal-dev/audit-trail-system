<?php defined('BASEPATH') OR exit('No direct script access allowed');
function h($s){ 
  return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); 
}

$page = $page ?? 1;
$per_page = $per_page ?? 10;
$total_activity = $total_activity ?? 0;
$activity = $activity ?? [];
$apps = $apps ?? [];
$filter_show = $filter_show ?? '10';
$base_url = strtok($_SERVER['REQUEST_URI'], '?');
$query = $_GET;
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Activity Log | Audit Trail</title>
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
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
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
    color: var(--primary);
}

.short {
    display: block;
    max-width: 250px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.scrollable-dropdown {
    max-height: 150px;
    overflow-y: auto;
}

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
}
</style>
</head>
<body>
<div class="container-wrap">

  <!-- Header -->
  <div class="header">
    <div class="brand">
      <div class="logo">AT</div>
      <div>
        <h1>Activity Log</h1>
        <div class="sub muted">Monitor semua aktivitas sistem</div>
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
      <a class="btn btn-success" href="<?= site_url('audit/export_activity_csv') . '?' . h($_SERVER['QUERY_STRING']) ?>">
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
        <span class="stat-label">Total Records:</span>
        <span class="stat-value"><?= number_format($total_activity) ?></span>
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
    <form id="filterForm" method="get" action="<?= site_url('audit/activity') ?>">
      <div class="filter-grid">
        <div>
          <label class="form-label">Urutkan</label>
          <select name="sort" class="form-select">
            <option value="">Default</option>
            <option value="desc" <?= ($filter_sort == 'desc' ? 'selected' : '') ?>>Waktu Terbaru</option>
            <option value="asc" <?= ($filter_sort == 'asc' ? 'selected' : '') ?>>Waktu Terlama</option>
          </select>
        </div>

        <div>
          <label class="form-label">Aplikasi</label>
          <select name="app" class="form-select">
            <option value="">Semua Aplikasi</option>
            <?php if (!empty($apps)): foreach ($apps as $ap): ?>
              <option value="<?= h($ap->id_aplikasi) ?>" <?= ($filter_app == $ap->id_aplikasi ? 'selected' : '') ?>>
                <?= h($ap->NM_APLIKASI . ' (' . ($ap->user_name ?? '') . ')') ?>
              </option>
            <?php endforeach; endif; ?>
          </select>
        </div>

        <div>
          <label class="form-label">User</label>
          <input name="user" class="form-control" placeholder="Cari user..." value="<?= h($filter_user ?? '') ?>">
        </div>

        <div>
          <label class="form-label">Aksi</label>
          <select name="aksi" class="form-select scrollable-dropdown">
            <option value="">Semua Aksi</option>
            <?php
            $aksi_opts = $this->db->distinct()->select('aksi')->get('tb_activity')->result_array();
            foreach($aksi_opts as $row):
                $opt = $row['aksi'];
            ?>
                <option value="<?= h($opt) ?>" <?= ($filter_aksi == $opt ? 'selected' : '') ?>><?= h($opt) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="form-label">Hasil</label>
          <select name="hasil" class="form-select scrollable-dropdown">
            <option value="">Semua</option>
            <?php
            $hasil_opts = $this->db->distinct()->select('hasil')->get('tb_activity')->result_array();
            foreach($hasil_opts as $row):
                $opt = $row['hasil'];
            ?>
                <option value="<?= h($opt) ?>" <?= ($filter_hasil == $opt ? 'selected' : '') ?>><?= h($opt) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="form-label">Keyword</label>
          <input name="q" class="form-control" placeholder="Cari keterangan..." value="<?= h($filter_q ?? '') ?>">
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
          <label class="form-label">Tampilkan</label>
          <select name="show" class="form-select">
            <option value="10" <?= $filter_show == '10' ? 'selected' : '' ?>>10 Data</option>
            <option value="all" <?= $filter_show == 'all' ? 'selected' : '' ?>>Semua Data</option>
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
        <a class="btn btn-outline-secondary" href="<?= site_url('audit/activity') ?>">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
          </svg>
          Reset
        </a>
      </div>
    </form>
  </div>

  <!-- Activity Table -->
  <div class="card-glass">
    <h3 style="margin-bottom: 20px; color: var(--dark); font-size: 18px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="vertical-align: middle; margin-right: 8px;">
        <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
      </svg>
      Data Activity Log
    </h3>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th style="width:60px">No</th>
            <th>Waktu</th>
            <th>User</th>
            <th>Menu</th>
            <th>Aksi</th>
            <th style="width:120px">Status</th>
            <th>Keterangan</th>
            <th>IP Address</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $start_no = ($page - 1) * $per_page + 1;
          if (empty($activity)): ?>
            <tr><td colspan="8" style="text-align:center; padding: 40px; color: #94a3b8;">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor" style="opacity: 0.3; margin-bottom: 12px;">
                <path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V8h16v10z"/>
              </svg>
              <div style="font-size: 16px; font-weight: 600;">Tidak ada data</div>
              <div style="font-size: 14px; margin-top: 4px;">Belum ada activity yang tercatat</div>
            </td></tr>
          <?php else:
            $no = $start_no;
            foreach ($activity as $a):
              $user = isset($a->user) ? $a->user : ($a->user_name ?? 'unknown');
              $status = isset($a->hasil) ? $a->hasil : 'unknown';
              $badge_color = $status === 'success' ? '#10b981' : '#ef4444';
          ?>
          <tr onclick="showActivityDetail(<?= (int)$a->id_activity ?>)" style="cursor: pointer;">
            <td style="font-weight: 600;"><?= $no++ ?></td>
            <td><?= h($a->modidate) ?></td>
            <td><span class="short"><?= h($user) ?></span></td>
            <td><span class="short"><?= h($a->menu_fitur) ?></span></td>
            <td><strong><?= h($a->aksi) ?></strong></td>
            <td>
              <span class="status-pill" style="background:<?= $badge_color ?>; color:white;">
                <?= ucfirst(h($status)) ?>
              </span>
            </td>
            <td><span class="short"><?= h(mb_substr($a->ket ?? '', 0, 100)) ?><?= (mb_strlen($a->ket ?? '') > 100 ? '...' : '') ?></span></td>
            <td><?= h($a->ip_address) ?></td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <?php
    $total_pages = max(1, (int)ceil($total_activity / $per_page));
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

<!-- Activity Detail Modal -->
<div class="modal" id="activityModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Activity Detail <small id="activityId" style="color: #94a3b8;"></small></h2>
        <button type="button" class="btn btn-outline-secondary" onclick="hideModal('activityModal')" style="padding: 8px 16px;">Close</button>
      </div>
      <div class="modal-body">
        <div id="activityDetail">Loading...</div>
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
function escapeHtml(s) {
  if (s === null || s === undefined) return '';
  return String(s).replace(/[&<>"'`=\/]/g, function (c) {
    return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'}[c];
  });
}

const refreshBtn = document.getElementById('refreshBtn');
const filterForm = document.getElementById('filterForm');
if (refreshBtn && filterForm) {
  refreshBtn.addEventListener('click', () => filterForm.submit());
}

function showActivityDetail(id) {
  showModal('activityModal');
  document.getElementById('activityId').innerText = '#' + id;
  document.getElementById('activityDetail').innerHTML = '<div style="text-align:center; padding: 40px;"><div style="width: 40px; height: 40px; border: 4px solid rgba(99,102,241,0.3); border-top-color: var(--primary); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div></div>';

  fetch('<?= site_url('audit/ajax_activity_detail') ?>?id=' + encodeURIComponent(id))
    .then(r => r.json())
    .then(d => {
      if (d && d.success && d.data) {
        const a = d.data;
        const user = a.user ?? a.user_name ?? '';
        let html = '<table class="table" style="margin: 0;">';
        html += '<tr><th style="width:180px; background: #f8fafc;">ID</th><td>' + (a.id_activity ?? '') + '</td></tr>';
        html += '<tr><th style="background: #f8fafc;">Waktu</th><td>' + escapeHtml(a.modidate ?? '') + '</td></tr>';
        html += '<tr><th style="background: #f8fafc;">User</th><td>' + escapeHtml(user) + '</td></tr>';
        html += '<tr><th style="background: #f8fafc;">Menu</th><td>' + escapeHtml(a.menu_fitur ?? '') + '</td></tr>';
        html += '<tr><th style="background: #f8fafc;">Aksi</th><td>' + escapeHtml(a.aksi ?? '') + '</td></tr>';
        html += '<tr><th style="background: #f8fafc;">Keterangan</th><td><pre style="background: #f8fafc; padding: 12px; border-radius: 8px; margin: 0; white-space: pre-wrap; word-wrap: break-word;">' + escapeHtml(a.ket ?? '') + '</pre></td></tr>';
        html += '<tr><th style="background: #f8fafc;">IP Address</th><td>' + escapeHtml(a.ip_address ?? '') + '</td></tr>';
        html += '</table>';
        document.getElementById('activityDetail').innerHTML = html;
      } else {
        document.getElementById('activityDetail').innerHTML = '<div style="color: var(--danger); text-align: center; padding: 20px;">Tidak dapat memuat detail</div>';
      }
    }).catch(() => {
      document.getElementById('activityDetail').innerHTML = '<div style="color: var(--danger); text-align: center; padding: 20px;">Error loading data</div>';
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
