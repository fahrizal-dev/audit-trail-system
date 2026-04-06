/**
 * QUICK START (JavaScript)
 * 1) Copy AuditTrailClient.js ke project Anda.
 * 2) Edit 3 config di bawah.
 * 3) Panggil sendAuditLog(...) pada aksi penting.
 */

// Untuk Node.js:
// const AuditTrailClient = require('./AuditTrailClient');
// Untuk ES Module / bundler:
// import AuditTrailClient from './AuditTrailClient';

// === EDIT BAGIAN INI ===
const AUDIT_BASE_URL = 'http://localhost/audit-trail/api';
const AUDIT_APP_ID = '1';
const AUDIT_PASSWORD = 'password123';
// =======================

const audit = new AuditTrailClient(AUDIT_BASE_URL, AUDIT_APP_ID, AUDIT_PASSWORD, false);

async function sendAuditLog(user, aksi, menuFitur, ket, hasil = 'success') {
    // Jangan sampai audit error menghentikan proses utama aplikasi.
    try {
        await audit.log(user, aksi, {
            menu_fitur: menuFitur,
            hasil,
            ket
        });
    } catch (err) {
        console.error('Audit log gagal:', err.message);
    }
}

// === CONTOH PEMAKAIAN ===
// await sendAuditLog('rapat_user', 'LOGIN', 'Sistem Rapat', 'User login ke sistem');
// await sendAuditLog('rapat_user', 'CREATE', 'Sistem Rapat', 'Membuat jadwal rapat');
// await sendAuditLog('rapat_user', 'UPDATE', 'Sistem Rapat', 'Mengubah jadwal rapat');
// await sendAuditLog('rapat_user', 'DELETE', 'Sistem Rapat', 'Menghapus jadwal rapat');
// await sendAuditLog('rapat_user', 'LOGOUT', 'Sistem Rapat', 'User logout dari sistem');
