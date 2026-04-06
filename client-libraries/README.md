# Audit Trail Client Libraries

Panduan cepat agar sistem eksternal langsung bisa kirim log ke Audit Trail.

## Isi Download

- `php/AuditTrailClient.php`
- `php/example.php`
- `javascript/AuditTrailClient.js`
- `javascript/example.js`
- `python/audit_trail_client.py`
- `python/example.py`

## 5 Langkah Cepat

1. Daftarkan aplikasi di dashboard Audit Trail, lalu simpan:
- `id_aplikasi`
- `password`

2. Copy file library utama ke project eksternal:
- PHP: `AuditTrailClient.php`
- JavaScript: `AuditTrailClient.js`
- Python: `audit_trail_client.py`

3. Buka file `example` sesuai bahasa, lalu ganti config ini:
- `AUDIT_BASE_URL`
- `AUDIT_APP_ID`
- `AUDIT_PASSWORD`

4. Pindahkan contoh fungsi `sendAuditLog(...)` ke service/helper di sistem Anda.

5. Panggil service itu pada aksi penting:
- `LOGIN`, `LOGOUT`, `CREATE`, `UPDATE`, `DELETE`

## Cek Berhasil

- Jalankan 1 aksi di sistem eksternal.
- Buka menu Activity di Audit Trail.
- Jika log belum masuk: cek status aplikasi harus `Active` dan config harus benar.
