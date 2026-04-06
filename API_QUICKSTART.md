# Quick Start - Audit Trail API

Panduan paling cepat untuk mulai kirim log dari sistem eksternal.

## 1) Daftarkan Aplikasi

Dari dashboard:
- Buka `/audit/apps`
- Klik **Daftar Aplikasi Baru**
- Simpan: `id_aplikasi`, `password`, `secret_key`

## 2) Ambil Token

```bash
curl -X GET http://localhost/audit-trail/api/getToken \
  -H "x-userid: 1" \
  -H "x-password: password123"
```

Ambil nilai `response.token`.

## 3) Kirim Log Activity

```bash
curl -X POST http://localhost/audit-trail/api/postToken \
  -H "x-token: TOKEN_DARI_STEP_2" \
  -H "Content-Type: application/json" \
  -d '{
    "data": {
      "user": "rapat_user",
      "menu_fitur": "Sistem Rapat",
      "aksi": "CREATE",
      "hasil": "success",
      "ket": "Membuat jadwal rapat"
    }
  }'
```

## 4) Verifikasi

- Buka `/audit/activity`
- Pastikan log baru muncul.

## Jika Gagal

- Pastikan aplikasi **Active** di `/audit/apps`
- Cek `x-userid` dan `x-password`
- Jika token expired, ambil token baru lalu kirim ulang

## Rekomendasi

Agar lebih cepat, gunakan file di `client-libraries/`:
- `php/AuditTrailClient.php`
- `javascript/AuditTrailClient.js`
- `python/audit_trail_client.py`
