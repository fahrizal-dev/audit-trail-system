"""
QUICK START (Python)
1) Copy audit_trail_client.py ke project Anda.
2) Edit 3 config di bawah.
3) Panggil send_audit_log(...) pada aksi penting.
"""

from audit_trail_client import AuditTrailClient

# === EDIT BAGIAN INI ===
AUDIT_BASE_URL = 'http://localhost/audit-trail/api'
AUDIT_APP_ID = '1'
AUDIT_PASSWORD = 'password123'
# =======================

audit = AuditTrailClient(
    base_url=AUDIT_BASE_URL,
    user_id=AUDIT_APP_ID,
    password=AUDIT_PASSWORD,
    debug=False
)


def send_audit_log(user, aksi, menu_fitur, ket, hasil='success'):
    # Jangan sampai audit error menghentikan proses utama aplikasi.
    try:
        audit.log(
            user,
            aksi,
            menu_fitur=menu_fitur,
            hasil=hasil,
            ket=ket
        )
    except Exception as exc:
        print(f'Audit log gagal: {exc}')


# === CONTOH PEMAKAIAN ===
# send_audit_log('rapat_user', 'LOGIN', 'Sistem Rapat', 'User login ke sistem')
# send_audit_log('rapat_user', 'CREATE', 'Sistem Rapat', 'Membuat jadwal rapat')
# send_audit_log('rapat_user', 'UPDATE', 'Sistem Rapat', 'Mengubah jadwal rapat')
# send_audit_log('rapat_user', 'DELETE', 'Sistem Rapat', 'Menghapus jadwal rapat')
# send_audit_log('rapat_user', 'LOGOUT', 'Sistem Rapat', 'User logout dari sistem')
