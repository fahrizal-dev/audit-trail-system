# Ringkasan Integrasi Audit Trail

Dokumen singkat untuk admin Audit Trail dan tim developer sistem eksternal.

## Tugas Admin Audit Trail

1. Daftarkan aplikasi di `/audit/apps`
2. Kirim ke partner:
- `id_aplikasi`
- `password`
- `secret_key`
- base URL API (contoh: `http://domain-anda/audit-trail/api`)
3. Pastikan status aplikasi `Active`

## Tugas Developer Sistem Eksternal

1. Copy library sesuai bahasa dari `client-libraries/`
2. Edit config di file `example`:
- `AUDIT_BASE_URL`
- `AUDIT_APP_ID`
- `AUDIT_PASSWORD`
3. Panggil fungsi audit pada aksi penting:
- `LOGIN`, `LOGOUT`, `CREATE`, `UPDATE`, `DELETE`

## Endpoint yang Dipakai

- `GET /api/getToken` untuk ambil token
- `POST /api/postToken` untuk kirim activity

## Payload Minimal Activity

```json
{
  "data": {
    "user": "username",
    "menu_fitur": "Nama Modul",
    "aksi": "CREATE",
    "hasil": "success",
    "ket": "Keterangan aksi"
  }
}
```

## Validasi Sukses

- Jalankan 1 aksi dari sistem eksternal
- Cek menu `/audit/activity`
- Jika log belum masuk: cek status aplikasi, kredensial, dan token

## Rekomendasi Implementasi

- Simpan logic audit di 1 service/helper
- Jangan biarkan error audit menghentikan proses utama aplikasi
- Saat token expired, ambil token baru lalu retry kirim log
