# Panduan Integrasi API Audit Trail

Panduan ringkas dan jelas untuk integrasi ke sistem eksternal.

## Alur Integrasi

1. Daftarkan aplikasi di `/audit/apps`
2. Simpan kredensial: `id_aplikasi`, `password`, `secret_key`
3. Ambil token via `GET /api/getToken`
4. Kirim activity via `POST /api/postToken`
5. Verifikasi di `/audit/activity`

## Endpoint Utama

### 1) Ambil Token

- Method: `GET`
- URL: `/api/getToken`
- Header wajib:
  - `x-userid: <id_aplikasi>`
  - `x-password: <password>`

Contoh response:

```json
{
  "response": {
    "token": "..."
  },
  "metadata": {
    "message": "OK",
    "code": 200
  }
}
```

### 2) Kirim Activity

- Method: `POST`
- URL: `/api/postToken`
- Header wajib:
  - `x-token: <token>`
  - `Content-Type: application/json`

Body minimal:

```json
{
  "data": {
    "user": "rapat_user",
    "menu_fitur": "Sistem Rapat",
    "aksi": "CREATE",
    "hasil": "success",
    "ket": "Membuat jadwal rapat"
  }
}
```

## Field yang Direkomendasikan

- `user`: username pelaku aksi
- `menu_fitur`: nama modul
- `aksi`: `LOGIN`, `LOGOUT`, `CREATE`, `UPDATE`, `DELETE`
- `hasil`: `success` atau `failed`
- `ket`: keterangan singkat
- Opsional: `no_rm`, `rawat`, `trx_id`, `ip_address`

## Integrasi dengan Client Library

Gunakan file siap pakai di folder `client-libraries/`:

- PHP: `php/AuditTrailClient.php` + `php/example.php`
- JavaScript: `javascript/AuditTrailClient.js` + `javascript/example.js`
- Python: `python/audit_trail_client.py` + `python/example.py`

Edit konfigurasi di file `example`:

- `AUDIT_BASE_URL`
- `AUDIT_APP_ID`
- `AUDIT_PASSWORD`

Lalu panggil helper `sendAuditLog(...)` / `send_audit_log(...)` dari fitur bisnis Anda.

## Troubleshooting Singkat

- `401` saat ambil token: cek `id_aplikasi` dan `password`
- `401` saat kirim activity: token invalid/expired, ambil token baru
- Log tidak muncul: cek status aplikasi harus `Active`
- Tetap gagal: cek URL base API dan koneksi jaringan
