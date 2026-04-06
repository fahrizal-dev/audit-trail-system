<?php
/**
 * QUICK START (PHP)
 * 1) Copy AuditTrailClient.php ke project Anda.
 * 2) Edit 3 config di bawah.
 * 3) Panggil sendAuditLog(...) pada aksi penting.
 */

require_once __DIR__ . '/AuditTrailClient.php';

// === EDIT BAGIAN INI ===
const AUDIT_BASE_URL = 'http://localhost/audit-trail/api';
const AUDIT_APP_ID = '1';
const AUDIT_PASSWORD = 'password123';
// =======================

$audit = new AuditTrailClient(AUDIT_BASE_URL, AUDIT_APP_ID, AUDIT_PASSWORD, false);

function sendAuditLog($user, $aksi, $menu, $ket, $hasil = 'success')
{
    global $audit;

    // Jangan sampai audit error menghentikan proses utama aplikasi.
    try {
        $audit->log($user, $aksi, [
            'menu_fitur' => $menu,
            'hasil' => $hasil,
            'ket' => $ket
        ]);
    } catch (Throwable $e) {
        error_log('Audit log gagal: ' . $e->getMessage());
    }
}

// === CONTOH PEMAKAIAN ===
// sendAuditLog('rapat_user', 'LOGIN', 'Sistem Rapat', 'User login ke sistem');
// sendAuditLog('rapat_user', 'CREATE', 'Sistem Rapat', 'Membuat jadwal rapat');
// sendAuditLog('rapat_user', 'UPDATE', 'Sistem Rapat', 'Mengubah jadwal rapat');
// sendAuditLog('rapat_user', 'DELETE', 'Sistem Rapat', 'Menghapus jadwal rapat');
// sendAuditLog('rapat_user', 'LOGOUT', 'Sistem Rapat', 'User logout dari sistem');
