<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Manage Aplikasi & Token | Audit Trail</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/audit.css') ?>">
  <style>
.api-docs {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.api-docs h2 {
    font-size: 24px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.api-docs h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--primary);
    margin: 24px 0 12px 0;
}

.api-docs p {
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 16px;
}

.code-block {
    background: #1e293b;
    color: #e2e8f0;
    padding: 16px;
    border-radius: 12px;
    overflow-x: auto;
    margin: 12px 0;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    line-height: 1.6;
}

.code-inline {
    background: #e2e8f0;
    color: #1e293b;
    padding: 2px 8px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 13px;
}

.app-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    transition: all 0.3s;
}

.app-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
}

.app-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 16px;
}

.app-info h3 {
    font-size: 20px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 8px;
}

.app-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin-top: 8px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #64748b;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
}

.status-active {
    background: #d1fae5;
    color: #065f46;
}

.status-inactive {
    background: #fee2e2;
    color: #991b1b;
}

.app-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.bulk-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px 16px;
    margin-bottom: 16px;
}

.bulk-toolbar-left {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    color: #475569;
    font-size: 14px;
}

.bulk-toolbar-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.bulk-toolbar input[type="checkbox"] {
    transform: translateY(1px);
}

.app-select {
    display: flex;
    align-items: flex-start;
    padding-top: 2px;
}

.app-select input[type="checkbox"] {
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 13px;
}

.credentials {
    background: #f8fafc;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
}

.cred-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e2e8f0;
}

.cred-row:last-child {
    border-bottom: none;
}

.cred-label {
    font-weight: 600;
    color: #475569;
    font-size: 13px;
}

.cred-value {
    font-family: 'Courier New', monospace;
    color: #1e293b;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-copy {
    background: none;
    border: none;
    color: var(--primary);
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: all 0.2s;
}

.btn-copy:hover {
    background: #e0e7ff;
}

.tokens-section {
    margin-top: 16px;
}

.tokens-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}

.tokens-section h4 {
    font-size: 15px;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0;
}

.token-list-controls {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #64748b;
}

.token-list-controls select {
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 6px 8px;
    background: #fff;
    color: #1e293b;
    font-size: 12px;
}

.token-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}

.token-info {
    flex: 1;
    min-width: 0;
}

.token-value {
    font-family: 'Courier New', monospace;
    font-size: 12px;
    color: #1e293b;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.token-meta {
    font-size: 11px;
    color: #64748b;
    margin-top: 4px;
}

.token-expired {
    opacity: 0.5;
}

.token-actions {
    display: flex;
    gap: 4px;
}

.no-tokens {
    text-align: center;
    padding: 24px;
    color: #94a3b8;
    font-size: 14px;
}

@media (max-width: 768px) {
    .app-header {
        flex-direction: column;
    }

    .bulk-toolbar {
        flex-direction: column;
        align-items: stretch;
    }

    .bulk-toolbar-actions .btn {
        width: 100%;
    }
    
    .app-actions {
        width: 100%;
    }
    
    .btn-sm {
        flex: 1;
    }
    
    .cred-row {
        flex-direction: column;
        align-items: start;
        gap: 4px;
    }
}
  </style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <div class="logo">
                <svg viewBox="0 0 24 24">
                    <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                </svg>
            </div>
            <div class="header-title">
                <h1>Manage Aplikasi & Token</h1>
                <p>Kelola akses API untuk sistem eksternal</p>
            </div>
        </div>
        <a href="<?= base_url('audit/home') ?>" class="btn btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
            Kembali
        </a>
    </div>

    <!-- API Documentation -->
    <div class="api-docs">
        <h2>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 21c0 .55.45 1 1 1h4c.55 0 1-.45 1-1v-1H9v1zm3-19C8.14 2 5 5.14 5 9c0 2.38 1.19 4.47 3 5.74V17c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-2.26c1.81-1.27 3-3.36 3-5.74 0-3.86-3.14-7-7-7zm2.85 11.1l-.85.6V16h-4v-2.3l-.85-.6C7.8 12.16 7 10.63 7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.63-.8 3.16-2.15 4.1z"/>
            </svg>
            Cara Menggunakan API
        </h2>
        
        <p>Ikuti langkah runtut berikut supaya sistem lain langsung bisa kirim log ke Audit Trail.</p>
        
        <h3>📚 Dokumentasi Lengkap</h3>
        <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 24px;">
            <a href="<?= base_url('client-libraries/') ?>" class="btn btn-sm btn-success" target="_blank">
                📦 Download Client Libraries
            </a>
            <a href="<?= base_url('API_QUICKSTART.md') ?>" class="btn btn-sm btn-primary" download>
                📄 Quick Start
            </a>
            <a href="<?= base_url('API_INTEGRATION_GUIDE.md') ?>" class="btn btn-sm btn-primary" download>
                📖 Full Guide
            </a>
            <a href="<?= base_url('INTEGRATION_SUMMARY.md') ?>" class="btn btn-sm btn-primary" download>
                🎯 Summary
            </a>
        </div>
        
        <h3>1. Daftarkan aplikasi</h3>
        <p>Klik <strong>Daftar Aplikasi Baru</strong>, lalu simpan data ini: <span class="code-inline">id_aplikasi</span>, <span class="code-inline">user_name</span>, <span class="code-inline">password</span>, dan <span class="code-inline">secret_key</span>.</p>

        <h3>2. Download library dan taruh di project sistem Anda</h3>
        <p>Gunakan tombol <strong>Download Client Libraries</strong>, lalu copy file ke folder berikut:</p>
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 16px; margin: 12px 0 18px 0;">
            <p style="margin: 0 0 8px 0; color: #334155;"><strong>PHP ZIP</strong></p>
            <ul style="margin: 0 0 10px 18px; color: #475569; line-height: 1.6;">
                <li>AuditTrailClient.php</li>
                <li>example.php</li>
                <li>Taruh di project: <span class="code-inline">application/libraries/</span> atau <span class="code-inline">app/Libraries/</span></li>
            </ul>

            <p style="margin: 0 0 8px 0; color: #334155;"><strong>JavaScript ZIP</strong></p>
            <ul style="margin: 0 0 10px 18px; color: #475569; line-height: 1.6;">
                <li>AuditTrailClient.js</li>
                <li>example.js</li>
                <li>Taruh di project: <span class="code-inline">src/libs/</span> atau <span class="code-inline">services/</span></li>
            </ul>

            <p style="margin: 0 0 8px 0; color: #334155;"><strong>Python ZIP</strong></p>
            <ul style="margin: 0 0 0 18px; color: #475569; line-height: 1.6;">
                <li>audit_trail_client.py</li>
                <li>example.py</li>
                <li>Taruh di project: <span class="code-inline">services/</span> atau <span class="code-inline">utils/</span></li>
            </ul>
        </div>

        <h3>3. Buat file konfigurasi di sistem Anda (wajib)</h3>
        <p>Tambahkan setting ini di file config atau <span class="code-inline">.env</span> sistem Anda:</p>
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 16px; margin: 12px 0 18px 0; color: #334155; line-height: 1.65;">
            <ul style="margin: 0 0 0 18px; color: #475569;">
                <li><strong>AUDIT_BASE_URL</strong>: <span class="code-inline"><?= base_url() ?></span></li>
                <li><strong>AUDIT_APP_ID</strong>: id_aplikasi dari dashboard ini</li>
                <li><strong>AUDIT_PASSWORD</strong>: password aplikasi</li>
                <li><strong>AUDIT_SECRET_KEY</strong>: secret_key aplikasi</li>
            </ul>
        </div>

        <h3>4. Edit 1 file service untuk kirim log</h3>
        <p>Buat atau edit satu file khusus audit (contoh: <span class="code-inline">AuditService</span>) agar semua modul sistem Anda memanggil file ini saat ada aktivitas penting.</p>
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 16px; margin: 12px 0 18px 0; color: #334155; line-height: 1.65;">
            <p style="margin: 0 0 6px 0;"><strong>Endpoint yang dipakai service</strong>:</p>
            <ul style="margin: 0 0 0 18px; color: #475569;">
                <li>GET <span class="code-inline"><?= base_url('api/getToken') ?></span> (ambil token)</li>
                <li>POST <span class="code-inline"><?= base_url('api/postToken') ?></span> (kirim activity)</li>
            </ul>
        </div>

        <h3>5. Kirim log dari fitur sistem Anda</h3>
        <p>Pada titik aksi penting (contoh: tambah jadwal, ubah jadwal, hapus rapat), panggil service audit dengan data berikut:</p>
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 16px; margin: 12px 0 18px 0; color: #334155; line-height: 1.65;">
            <ul style="margin: 0 0 0 18px; color: #475569;">
                <li>user: username pengguna</li>
                <li>menu_fitur: nama modul (contoh Sistem Rapat)</li>
                <li>aksi: CREATE / UPDATE / DELETE / LOGIN / LOGOUT</li>
                <li>hasil: success atau failed</li>
                <li>ket: keterangan singkat aktivitas</li>
            </ul>
        </div>

        <h3>6. Uji koneksi sampai benar-benar masuk</h3>
        <p>Lakukan 1 aksi dari sistem Anda, lalu cek menu <span class="code-inline">Activity</span> di Audit Trail. Jika belum muncul, cek lagi <span class="code-inline">AUDIT_APP_ID</span>, <span class="code-inline">AUDIT_PASSWORD</span>, dan status aplikasi harus <strong>Active</strong>.</p>
    </div>

    <!-- Register New App Button -->
    <div style="margin-bottom: 24px; text-align: right;">
        <button class="btn btn-primary" onclick="showRegisterModal()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
            </svg>
            Daftar Aplikasi Baru
        </button>
    </div>

    <!-- Applications List -->
    <?php if (empty($apps)): ?>
        <div class="app-card">
            <div class="no-tokens">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="#cbd5e1" style="margin-bottom: 12px;">
                    <path d="M20 6h-2.18c.11-.31.18-.65.18-1 0-1.66-1.34-3-3-3-1.05 0-1.96.54-2.5 1.35l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm11 15H4v-2h16v2zm0-5H4V8h5.08L7 10.83 8.62 12 11 8.76l1-1.36 1 1.36L15.38 12 17 10.83 14.92 8H20v6z"/>
                </svg>
                <p>Belum ada aplikasi terdaftar</p>
                <p style="font-size: 13px; margin-top: 8px;">Klik tombol "Daftar Aplikasi Baru" untuk menambahkan aplikasi</p>
            </div>
        </div>
    <?php else: ?>
        <div class="bulk-toolbar">
            <div class="bulk-toolbar-left">
                <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" id="selectAllApps" onchange="toggleSelectAllApps(this.checked)">
                    Pilih Semua
                </label>
                <span id="selectedCount">0 aplikasi dipilih</span>
            </div>
            <div class="bulk-toolbar-actions">
                <button type="button" class="btn btn-sm btn-danger" id="bulkDisableBtn" onclick="bulkSetStatus(0)" disabled>
                    Nonaktifkan Terpilih
                </button>
                <button type="button" class="btn btn-sm btn-danger" id="bulkDeleteBtn" onclick="bulkDeleteApps()" disabled>
                    Hapus Terpilih
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="deleteUnusedTokens()">
                    Hapus Semua Token Tidak Terpakai
                </button>
            </div>
        </div>
        <?php foreach ($apps as $app): ?>
        <div class="app-card">
            <div class="app-header">
                <div class="app-select">
                    <input
                        type="checkbox"
                        class="app-select-checkbox"
                        value="<?= $app->id_aplikasi ?>"
                        onchange="updateBulkActionsState()"
                        aria-label="Pilih aplikasi <?= htmlspecialchars($app->NM_APLIKASI) ?>"
                    >
                </div>
                <div class="app-info">
                    <h3><?= htmlspecialchars($app->NM_APLIKASI) ?></h3>
                    <div class="app-meta">
                        <div class="meta-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                            </svg>
                            <?= htmlspecialchars($app->user_name) ?>
                        </div>
                        <div class="meta-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                            <?= htmlspecialchars($app->IP_ADDRESS ?: 'N/A') ?>
                        </div>
                        <span class="status-badge <?= $app->status_active == 1 ? 'status-active' : 'status-inactive' ?>">
                            <?= $app->status_active == 1 ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>
                </div>
                <div class="app-actions">
                    <button class="btn btn-sm btn-primary" onclick="generateToken(<?= $app->id_aplikasi ?>)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                        </svg>
                        Generate Token
                    </button>
                    <button class="btn btn-sm <?= $app->status_active == 1 ? 'btn-danger' : 'btn-success' ?>" 
                            onclick="toggleStatus(<?= $app->id_aplikasi ?>, <?= $app->status_active == 1 ? 0 : 1 ?>)">
                        <?= $app->status_active == 1 ? 'Nonaktifkan' : 'Aktifkan' ?>
                    </button>
                </div>
            </div>

            <div class="credentials">
                <div class="cred-row">
                    <span class="cred-label">ID Aplikasi:</span>
                    <span class="cred-value">
                        <?= $app->id_aplikasi ?>
                        <button class="btn-copy" onclick="copyText('<?= $app->id_aplikasi ?>')">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                            </svg>
                        </button>
                    </span>
                </div>
                <div class="cred-row">
                    <span class="cred-label">Username:</span>
                    <span class="cred-value">
                        <?= htmlspecialchars($app->user_name) ?>
                        <button class="btn-copy" onclick="copyText('<?= htmlspecialchars($app->user_name) ?>')">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                            </svg>
                        </button>
                    </span>
                </div>
                <div class="cred-row">
                    <span class="cred-label">Secret Key:</span>
                    <span class="cred-value">
                        <?= substr($app->secret_key, 0, 20) ?>...
                        <button class="btn-copy" onclick="copyText('<?= $app->secret_key ?>')">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                            </svg>
                        </button>
                    </span>
                </div>
            </div>

            <div class="tokens-section">
                <div class="tokens-header">
                    <h4>🔑 Active Tokens (<?= count($app->tokens) ?>)</h4>
                    <div class="token-list-controls">
                        <label for="token_limit_<?= $app->id_aplikasi ?>">Tampilkan:</label>
                        <select
                            id="token_limit_<?= $app->id_aplikasi ?>"
                            class="token-display-limit"
                            data-app-id="<?= $app->id_aplikasi ?>"
                            onchange="applyTokenDisplayLimit(<?= $app->id_aplikasi ?>)"
                        >
                            <option value="5" selected>5 data</option>
                            <option value="10">10 data</option>
                            <option value="all">Semua</option>
                        </select>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteUnusedTokens(<?= $app->id_aplikasi ?>)">
                            Hapus Token Tidak Terpakai
                        </button>
                    </div>
                </div>
                <?php if (empty($app->tokens)): ?>
                    <div class="no-tokens" style="padding: 16px;">
                        Belum ada token. Klik "Generate Token" untuk membuat token baru.
                    </div>
                <?php else: ?>
                    <?php foreach ($app->tokens as $token): ?>
                        <?php 
                        $is_expired = strtotime($token->exp_date) < time();
                        $is_used = !empty($token->use_date);
                        ?>
                        <div class="token-item <?= $is_expired ? 'token-expired' : '' ?>" data-app-id="<?= $app->id_aplikasi ?>">
                            <div class="token-info">
                                <div class="token-value"><?= substr($token->token, 0, 40) ?>...</div>
                                <div class="token-meta">
                                    <?php if ($is_expired): ?>
                                        ⏰ Expired: <?= date('d M Y H:i', strtotime($token->exp_date)) ?>
                                    <?php else: ?>
                                        ✅ Valid until: <?= date('d M Y H:i', strtotime($token->exp_date)) ?>
                                    <?php endif; ?>
                                    <?php if ($is_used): ?>
                                        | Last used: <?= date('d M Y H:i', strtotime($token->use_date)) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="token-actions">
                                <button class="btn-copy" onclick="copyText('<?= $token->token ?>')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                                    </svg>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="revokeToken('<?= $token->token ?>')">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Toast Notification -->
<div id="toast" style="display: none;"></div>

<!-- Register App Modal -->
<div class="modal" id="registerModal">
    <div class="modal-dialog" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Daftar Aplikasi Baru</h2>
            </div>
            <div class="modal-body">
                <form id="registerForm">
                    <div style="margin-bottom: 16px;">
                        <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #475569;">
                            Nama Aplikasi <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nm_aplikasi" 
                            name="nm_aplikasi" 
                            class="form-control" 
                            placeholder="Contoh: SIMRS, E-Commerce, dll"
                            required
                            style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                        >
                    </div>
                    
                    <div style="margin-bottom: 16px;">
                        <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #475569;">
                            Username <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="user_name" 
                            name="user_name" 
                            class="form-control" 
                            placeholder="Contoh: simrs_app, ecommerce_app"
                            required
                            style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                        >
                        <small style="color: #64748b; font-size: 12px; margin-top: 4px; display: block;">
                            Username harus unik dan tidak boleh sama dengan aplikasi lain
                        </small>
                    </div>
                    
                    <div style="margin-bottom: 16px;">
                        <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #475569;">
                            Password <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control" 
                            placeholder="Minimal 8 karakter"
                            required
                            style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                        >
                        <small style="color: #64748b; font-size: 12px; margin-top: 4px; display: block;">
                            Password akan digunakan untuk autentikasi API
                        </small>
                    </div>
                    
                    <div style="background: #f1f5f9; padding: 12px; border-radius: 8px; margin-top: 16px;">
                        <p style="font-size: 13px; color: #475569; margin: 0;">
                            <strong>📝 Catatan:</strong> Setelah registrasi, Anda akan mendapatkan:
                        </p>
                        <ul style="margin: 8px 0 0 20px; font-size: 13px; color: #64748b;">
                            <li>ID Aplikasi</li>
                            <li>Secret Key (untuk enkripsi)</li>
                            <li>Credentials untuk akses API</li>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="display: flex; gap: 12px; justify-content: flex-end; padding: 16px 24px; border-top: 1px solid #e2e8f0;">
                <button class="btn btn-secondary" onclick="hideRegisterModal()" style="background: #e2e8f0; color: #64748b;">
                    Batal
                </button>
                <button id="registerSubmitBtn" class="btn btn-primary" onclick="submitRegister(this)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 6px;">
                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                    </svg>
                    Daftar Aplikasi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal" id="successModal">
    <div class="modal-dialog" style="max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 20px 20px 0 0;">
                <h2 class="modal-title" style="color: white;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" style="vertical-align: middle; margin-right: 8px;">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    Aplikasi Berhasil Didaftarkan!
                </h2>
            </div>
            <div class="modal-body">
                <p style="color: #64748b; margin-bottom: 20px;">
                    Simpan informasi berikut dengan aman. Anda akan membutuhkannya untuk integrasi API:
                </p>
                
                <div style="background: #f8fafc; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                        <span style="font-weight: 600; color: #475569;">ID Aplikasi:</span>
                        <span style="font-family: 'Courier New', monospace; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                            <span id="modal_id_aplikasi"></span>
                            <button class="btn-copy" onclick="copyFromModal('modal_id_aplikasi')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                                </svg>
                            </button>
                        </span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                        <span style="font-weight: 600; color: #475569;">Username:</span>
                        <span style="font-family: 'Courier New', monospace; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                            <span id="modal_user_name"></span>
                            <button class="btn-copy" onclick="copyFromModal('modal_user_name')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                                </svg>
                            </button>
                        </span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                        <span style="font-weight: 600; color: #475569;">Password:</span>
                        <span style="font-family: 'Courier New', monospace; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                            <span id="modal_password"></span>
                            <button class="btn-copy" onclick="copyFromModal('modal_password')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                                </svg>
                            </button>
                        </span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: start; padding: 8px 0;">
                        <span style="font-weight: 600; color: #475569;">Secret Key:</span>
                        <span style="font-family: 'Courier New', monospace; color: #1e293b; font-size: 12px; display: flex; align-items: center; gap: 8px; max-width: 60%; word-break: break-all;">
                            <span id="modal_secret_key"></span>
                            <button class="btn-copy" onclick="copyFromModal('modal_secret_key')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                                </svg>
                            </button>
                        </span>
                    </div>
                </div>
                
                <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px; border-radius: 8px;">
                    <p style="color: #92400e; font-size: 13px; margin: 0;">
                        <strong>⚠️ Penting:</strong> Simpan informasi ini dengan aman! Secret key tidak akan ditampilkan lagi.
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="display: flex; gap: 12px; justify-content: flex-end; padding: 16px 24px; border-top: 1px solid #e2e8f0;">
                <button class="btn btn-primary" onclick="closeSuccessModal()">
                    Tutup & Reload
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 10000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease-out;
}

.modal-dialog {
    width: 90%;
    max-width: 500px;
}

.modal-content {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: scaleIn 0.3s ease-out;
}

.modal-header {
    padding: 24px 24px 16px 24px;
    border-bottom: 1px solid #e2e8f0;
}

.modal-title {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.modal-body {
    padding: 24px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #475569;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>

<script src="<?= base_url('assets/js/audit.js') ?>"></script>
<script>
function copyText(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Berhasil disalin ke clipboard!', 'success');
    }).catch(() => {
        showToast('Gagal menyalin', 'error');
    });
}

function generateToken(idAplikasi) {
    if (!confirm('Generate token baru untuk aplikasi ini?')) return;
    
    fetch('<?= base_url('audit/ajax_generate_token') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id_aplikasi=' + idAplikasi
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('Terjadi kesalahan', 'error'));
}

function revokeToken(token) {
    if (!confirm('Hapus token ini? Token tidak bisa digunakan lagi.')) return;
    
    fetch('<?= base_url('audit/ajax_revoke_token') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'token=' + encodeURIComponent(token)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('Terjadi kesalahan', 'error'));
}

function toggleStatus(idAplikasi, status) {
    const action = status == 1 ? 'mengaktifkan' : 'menonaktifkan';
    if (!confirm(`Yakin ${action} aplikasi ini?`)) return;
    
    fetch('<?= base_url('audit/ajax_toggle_app_status') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id_aplikasi=' + idAplikasi + '&status=' + status
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('Terjadi kesalahan', 'error'));
}

function getSelectedAppIds() {
    return Array.from(document.querySelectorAll('.app-select-checkbox:checked'))
        .map(el => el.value)
        .filter(Boolean);
}

function updateBulkActionsState() {
    const selectedIds = getSelectedAppIds();
    const selectedCount = selectedIds.length;

    const selectedCountEl = document.getElementById('selectedCount');
    if (selectedCountEl) {
        selectedCountEl.textContent = selectedCount + ' aplikasi dipilih';
    }

    const selectAllEl = document.getElementById('selectAllApps');
    const allCheckboxes = document.querySelectorAll('.app-select-checkbox');
    const allSelected = allCheckboxes.length > 0 && selectedCount === allCheckboxes.length;
    if (selectAllEl) {
        selectAllEl.checked = allSelected;
        selectAllEl.indeterminate = selectedCount > 0 && !allSelected;
    }

    const bulkDisableBtn = document.getElementById('bulkDisableBtn');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    if (bulkDisableBtn) {
        bulkDisableBtn.disabled = selectedCount === 0;
    }

    if (bulkDeleteBtn) {
        bulkDeleteBtn.disabled = selectedCount === 0;
    }
}

function toggleSelectAllApps(checked) {
    document.querySelectorAll('.app-select-checkbox').forEach(el => {
        el.checked = checked;
    });
    updateBulkActionsState();
}

function bulkSetStatus(status) {
    const ids = getSelectedAppIds();
    if (ids.length === 0) {
        showToast('Pilih aplikasi terlebih dahulu', 'error');
        return;
    }

    const actionText = status == 1 ? 'mengaktifkan' : 'menonaktifkan';
    if (!confirm(`Yakin ${actionText} ${ids.length} aplikasi terpilih?`)) return;

    const body = new URLSearchParams();
    body.append('status', status);
    ids.forEach(id => body.append('ids[]', id));

    fetch('<?= base_url('audit/ajax_bulk_toggle_app_status') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: body.toString()
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1200);
        } else {
            showToast(data.message || 'Gagal memproses aksi massal', 'error');
        }
    })
    .catch(() => showToast('Terjadi kesalahan', 'error'));
}

function bulkDeleteApps() {
    const ids = getSelectedAppIds();
    if (ids.length === 0) {
        showToast('Pilih aplikasi terlebih dahulu', 'error');
        return;
    }

    if (!confirm(`Yakin hapus ${ids.length} aplikasi terpilih? Semua token aplikasi terpilih juga akan dihapus.`)) return;

    const body = new URLSearchParams();
    ids.forEach(id => body.append('ids[]', id));

    fetch('<?= base_url('audit/ajax_bulk_delete_apps') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: body.toString()
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1200);
        } else {
            showToast(data.message || 'Gagal menghapus aplikasi terpilih', 'error');
        }
    })
    .catch(() => showToast('Terjadi kesalahan', 'error'));
}

function applyTokenDisplayLimit(appId) {
    const selectEl = document.querySelector(`.token-display-limit[data-app-id="${appId}"]`);
    const items = Array.from(document.querySelectorAll(`.token-item[data-app-id="${appId}"]`));

    if (!selectEl || items.length === 0) {
        return;
    }

    const limitValue = selectEl.value;
    const limit = limitValue === 'all' ? Number.MAX_SAFE_INTEGER : parseInt(limitValue, 10);

    items.forEach((item, index) => {
        item.style.display = index < limit ? 'flex' : 'none';
    });
}

function initializeTokenDisplayLimit() {
    document.querySelectorAll('.token-display-limit').forEach(selectEl => {
        applyTokenDisplayLimit(selectEl.dataset.appId);
    });
}

function deleteUnusedTokens(idAplikasi = null) {
    const hasAppFilter = !(idAplikasi === null || idAplikasi === undefined || idAplikasi === '');
    const scopeText = hasAppFilter ? 'untuk aplikasi ini' : 'untuk semua aplikasi';
    if (!confirm(`Hapus semua token tidak terpakai (${scopeText})? Token yang sudah expired akan dihapus permanen.`)) {
        return;
    }

    const body = new URLSearchParams();
    if (hasAppFilter) {
        body.append('id_aplikasi', String(idAplikasi));
    }

    fetch('<?= base_url('audit/ajax_delete_unused_tokens') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: body.toString()
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1200);
        } else {
            showToast(data.message || 'Gagal menghapus token tidak terpakai', 'error');
        }
    })
    .catch(() => showToast('Terjadi kesalahan', 'error'));
}

function showToast(message, type) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = 'alert alert-' + type;
    toast.style.display = 'flex';
    
    setTimeout(() => {
        toast.style.display = 'none';
    }, 3000);
}

function showRegisterModal() {
    document.getElementById('registerModal').classList.add('show');
}

function hideRegisterModal() {
    document.getElementById('registerModal').classList.remove('show');
    document.getElementById('registerForm').reset();
}

function setRegisterButtonState(buttonEl, isLoading) {
    if (!buttonEl) return;

    buttonEl.disabled = isLoading;
    if (isLoading) {
        buttonEl.textContent = 'Mendaftar...';
        return;
    }

    buttonEl.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 6px;"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>Daftar Aplikasi';
}

function submitRegister(buttonEl) {
    const form = document.getElementById('registerForm');
    const submitBtn = buttonEl || document.getElementById('registerSubmitBtn');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const formData = new FormData(form);
    const data = {
        nm_aplikasi: formData.get('nm_aplikasi'),
        user_name: formData.get('user_name'),
        password: formData.get('password')
    };
    
    setRegisterButtonState(submitBtn, true);
    
    fetch('<?= base_url('api/registerApp') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        setRegisterButtonState(submitBtn, false);
        
        if (result.metadata && Number(result.metadata.code) === 200) {
            // Hide register modal
            hideRegisterModal();
            
            // Show success modal with credentials
            document.getElementById('modal_id_aplikasi').textContent = result.response.id_aplikasi;
            document.getElementById('modal_user_name').textContent = result.response.user_name;
            document.getElementById('modal_password').textContent = result.response.password;
            document.getElementById('modal_secret_key').textContent = result.response.secret_key;
            
            document.getElementById('successModal').classList.add('show');
        } else {
            showToast(result.metadata.message || 'Gagal mendaftar aplikasi', 'error');
        }
    })
    .catch(err => {
        setRegisterButtonState(submitBtn, false);
        showToast('Terjadi kesalahan: ' + err.message, 'error');
    });
}

function closeSuccessModal() {
    document.getElementById('successModal').classList.remove('show');
    setTimeout(() => location.reload(), 500);
}

function copyFromModal(elementId) {
    const text = document.getElementById(elementId).textContent;
    navigator.clipboard.writeText(text).then(() => {
        showToast('Berhasil disalin!', 'success');
    }).catch(() => {
        showToast('Gagal menyalin', 'error');
    });
}

// Close modal on outside click
document.getElementById('registerModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        hideRegisterModal();
    }
});

document.getElementById('successModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeSuccessModal();
    }
});

updateBulkActionsState();
initializeTokenDisplayLimit();
</script>

</body>
</html>
