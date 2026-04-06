<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Dashboard | Audit Trail</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --secondary: #8b5cf6;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --dark: #1e293b;
    --light: #f8fafc;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 24px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

/* Header */
.header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px 32px;
    margin-bottom: 32px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    animation: slideDown 0.5s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.logo {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
}

.logo svg {
    width: 28px;
    height: 28px;
    fill: white;
}

.header-title h1 {
    font-size: 24px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 4px;
}

.header-title p {
    font-size: 14px;
    color: #64748b;
}

.btn {
    padding: 12px 24px;
    border-radius: 12px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-logout {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

.btn-logout:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

/* Cards Grid */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 32px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    transition: all 0.3s;
    animation: fadeIn 0.6s ease-out;
    animation-fill-mode: both;
}

.card:nth-child(1) { animation-delay: 0.1s; }
.card:nth-child(2) { animation-delay: 0.2s; }
.card:nth-child(3) { animation-delay: 0.3s; }

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.card-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.card-icon svg {
    width: 32px;
    height: 32px;
    fill: white;
}

.card-icon.primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
}

.card-icon.warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.card h3 {
    font-size: 22px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 12px;
}

.card p {
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 24px;
    font-size: 15px;
}

.btn-card {
    width: 100%;
    padding: 14px;
    font-size: 15px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
}

.btn-primary:hover {
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
}

.btn-warning:hover {
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
}

/* Export Section */
.export-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 24px 32px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    animation: fadeIn 0.6s ease-out 0.3s both;
}

.export-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--dark);
}

.export-buttons {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-export {
    padding: 10px 20px;
    font-size: 14px;
}

.btn-success {
    background: linear-gradient(135deg, var(--success), #059669);
    color: white;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.btn-success:hover {
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.btn-secondary {
    background: linear-gradient(135deg, #64748b, #475569);
    color: white;
    box-shadow: 0 4px 15px rgba(100, 116, 139, 0.3);
}

.btn-secondary:hover {
    box-shadow: 0 6px 20px rgba(100, 116, 139, 0.4);
}

/* Alert */
.alert {
    position: fixed;
    top: 24px;
    right: 24px;
    padding: 16px 24px;
    border-radius: 12px;
    background: white;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 9999;
    animation: slideInRight 0.4s ease-out;
    max-width: 400px;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.alert-success {
    border-left: 4px solid var(--success);
    color: var(--success);
}

.alert-success::before {
    content: '✓';
    font-size: 20px;
    font-weight: bold;
}

.alert-error {
    border-left: 4px solid var(--danger);
    color: var(--danger);
}

.alert-error::before {
    content: '⚠';
    font-size: 20px;
}

/* Modal */
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

.modal-content {
    background: white;
    border-radius: 20px;
    padding: 32px;
    max-width: 440px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: scaleIn 0.3s ease-out;
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

.modal-header {
    margin-bottom: 20px;
}

.modal-header h2 {
    font-size: 22px;
    font-weight: 700;
    color: var(--dark);
}

.modal-body {
    margin-bottom: 24px;
    color: #64748b;
    line-height: 1.6;
}

.modal-footer {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.btn-cancel {
    background: #e2e8f0;
    color: #64748b;
}

.btn-cancel:hover {
    background: #cbd5e1;
}

/* Responsive */
@media (max-width: 768px) {
    body {
        padding: 16px;
    }
    
    .header {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
    
    .header-left {
        flex-direction: column;
    }
    
    .cards-grid {
        grid-template-columns: 1fr;
    }
    
    .export-section {
        flex-direction: column;
        text-align: center;
    }
    
    .export-buttons {
        width: 100%;
        flex-direction: column;
    }
    
    .btn-export {
        width: 100%;
    }
}
  </style>
</head>
<body>

<!-- Alert -->
<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success">
    <?= $this->session->flashdata('success'); ?>
</div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-error">
    <?= $this->session->flashdata('error'); ?>
</div>
<?php endif; ?>

<div class="container">
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <div class="logo">
                <svg viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
            </div>
            <div class="header-title">
                <h1>Audit Trail Dashboard</h1>
                <p>Monitoring & Logging System</p>
            </div>
        </div>
        <button class="btn btn-logout" onclick="showLogoutModal()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
            </svg>
            Logout
        </button>
    </div>

    <!-- Cards -->
    <div class="cards-grid">
        <div class="card">
            <div class="card-icon primary">
                <svg viewBox="0 0 24 24">
                    <path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/>
                </svg>
            </div>
            <h3>Activity Log</h3>
            <p>Pantau semua aktivitas user dan admin dalam sistem, termasuk aksi, menu, IP address, dan keterangan detail.</p>
            <a href="<?= base_url('audit/activity') ?>" class="btn btn-card btn-primary">
                Lihat Activity Log
            </a>
        </div>

        <div class="card">
            <div class="card-icon warning">
                <svg viewBox="0 0 24 24">
                    <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                </svg>
            </div>
            <h3>API Log</h3>
            <p>Lihat catatan lengkap request dan response API yang terekam dalam sistem untuk monitoring integrasi.</p>
            <a href="<?= base_url('audit/api_log') ?>" class="btn btn-card btn-warning">
                Lihat API Log
            </a>
        </div>

        <div class="card">
            <div class="card-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                <svg viewBox="0 0 24 24">
                    <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                </svg>
            </div>
            <h3>Manage Aplikasi</h3>
            <p>Kelola aplikasi terdaftar, generate token API, dan lihat dokumentasi integrasi untuk sistem eksternal.</p>
            <a href="<?= base_url('audit/apps') ?>" class="btn btn-card" style="background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                Kelola Aplikasi
            </a>
        </div>
    </div>

    <!-- Export Section -->
    <div class="export-section">
        <div class="export-title">📊 Export Data</div>
        <div class="export-buttons">
            <a href="<?= base_url('audit/export_activity_csv') ?>" class="btn btn-export btn-success">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 12v7H5v-7H3v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2zm-6 .67l2.59-2.58L17 11.5l-5 5-5-5 1.41-1.41L11 12.67V3h2z"/>
                </svg>
                Export Activity CSV
            </a>
            <a href="<?= base_url('audit/export_log_api_csv') ?>" class="btn btn-export btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 12v7H5v-7H3v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2zm-6 .67l2.59-2.58L17 11.5l-5 5-5-5 1.41-1.41L11 12.67V3h2z"/>
                </svg>
                Export API CSV
            </a>
        </div>
    </div>
</div>

<!-- Logout Modal -->
<div class="modal" id="logoutModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Konfirmasi Logout</h2>
        </div>
        <div class="modal-body">
            Apakah Anda yakin ingin keluar dari sistem?
        </div>
        <div class="modal-footer">
            <button class="btn btn-cancel" onclick="hideLogoutModal()">Batal</button>
            <a href="<?= base_url('auth/logout') ?>" class="btn btn-logout">Logout</a>
        </div>
    </div>
</div>

<script>
// Auto hide alert
setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) {
        alert.style.animation = 'slideInRight 0.4s ease-out reverse';
        setTimeout(() => alert.remove(), 400);
    }
}, 5000);

// Modal functions
function showLogoutModal() {
    document.getElementById('logoutModal').classList.add('show');
}

function hideLogoutModal() {
    document.getElementById('logoutModal').classList.remove('show');
}

// Close modal on outside click
document.getElementById('logoutModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideLogoutModal();
    }
});
</script>

</body>
</html>
