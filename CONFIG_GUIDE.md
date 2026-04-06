# ⚙️ Panduan Konfigurasi Audit Trail

Panduan untuk mengatur konfigurasi sistem Audit Trail.

---

## 📁 File Konfigurasi

File: `application/config/audit_config.php`

---

## 🔧 Konfigurasi Token

### 1. Token Expiry Duration

**Parameter:** `audit_token_expiry`  
**Type:** Integer (menit)  
**Default:** 15 menit

Durasi token sebelum expired.

```php
$config['audit_token_expiry'] = 15;
```

**Rekomendasi:**

| Environment | Durasi | Alasan |
|-------------|--------|--------|
| Development | 5-10 menit | Testing, sering restart |
| Production | 15-30 menit | ✅ **Balanced** (recommended) |
| High Traffic | 30-60 menit | Kurangi overhead refresh |
| Internal Only | 60 menit | Lebih nyaman, masih aman |

**Contoh:**

```php
// Development
$config['audit_token_expiry'] = 5;

// Production (Recommended)
$config['audit_token_expiry'] = 15;

// High Traffic
$config['audit_token_expiry'] = 30;

// Internal System
$config['audit_token_expiry'] = 60;
```

---

### 2. Token Auto-Extend

**Parameter:** `audit_token_auto_extend`  
**Type:** Boolean  
**Default:** TRUE

Apakah token otomatis diperpanjang setiap kali digunakan?

```php
$config['audit_token_auto_extend'] = TRUE;
```

**TRUE (Recommended):**
- ✅ Token diperpanjang setiap kali API dipanggil
- ✅ User tidak perlu sering request token baru
- ✅ Lebih user-friendly

**FALSE:**
- Token akan expired sesuai waktu awal
- User harus request token baru setelah expired
- Lebih strict, tapi kurang nyaman

---

## 🚀 Konfigurasi API

### 3. Rate Limiting

**Parameter:** `audit_rate_limit`  
**Type:** Integer (request per menit)  
**Default:** 0 (unlimited)

Maksimal request per menit per aplikasi.

```php
$config['audit_rate_limit'] = 0;  // Unlimited
```

**Contoh:**

```php
// Allow 100 request per menit
$config['audit_rate_limit'] = 100;

// Allow 1000 request per menit (high traffic)
$config['audit_rate_limit'] = 1000;

// Unlimited (default)
$config['audit_rate_limit'] = 0;
```

---

### 4. API Timeout

**Parameter:** `audit_api_timeout`  
**Type:** Integer (detik)  
**Default:** 30

Timeout untuk API request.

```php
$config['audit_api_timeout'] = 30;
```

---

## 📊 Konfigurasi Logging

### 5. Log Retention

**Parameter:** `audit_log_retention`  
**Type:** Integer (hari)  
**Default:** 0 (unlimited)

Berapa lama log disimpan.

```php
$config['audit_log_retention'] = 0;  // Unlimited
```

**Contoh:**

```php
// Simpan log 30 hari
$config['audit_log_retention'] = 30;

// Simpan log 90 hari
$config['audit_log_retention'] = 90;

// Simpan log 1 tahun
$config['audit_log_retention'] = 365;

// Unlimited (default)
$config['audit_log_retention'] = 0;
```

---

### 6. Auto Clean Old Logs

**Parameter:** `audit_auto_clean_logs`  
**Type:** Boolean  
**Default:** FALSE

Apakah otomatis hapus log lama?

```php
$config['audit_auto_clean_logs'] = FALSE;
```

**Note:** Hanya bekerja jika `audit_log_retention` > 0

---

## 🔒 Konfigurasi Security

### 7. Require HTTPS

**Parameter:** `audit_require_https`  
**Type:** Boolean  
**Default:** FALSE

Apakah wajib menggunakan HTTPS untuk API?

```php
$config['audit_require_https'] = FALSE;
```

**Rekomendasi:**
- Development: FALSE
- Production: TRUE ✅

---

### 8. IP Whitelist

**Parameter:** `audit_ip_whitelist`  
**Type:** Array  
**Default:** [] (allow all)

Daftar IP yang diizinkan akses API.

```php
$config['audit_ip_whitelist'] = [];  // Allow all
```

**Contoh:**

```php
// Allow specific IPs
$config['audit_ip_whitelist'] = [
    '192.168.1.100',
    '10.0.0.50',
    '172.16.0.10'
];

// Allow all (default)
$config['audit_ip_whitelist'] = [];
```

---

### 9. Require Encryption

**Parameter:** `audit_require_encryption`  
**Type:** Boolean  
**Default:** FALSE

Apakah wajib menggunakan enkripsi RC4?

```php
$config['audit_require_encryption'] = FALSE;
```

**FALSE (Default):**
- Enkripsi opsional
- Bisa kirim plain JSON atau encrypted

**TRUE:**
- Wajib pakai enkripsi RC4
- Request tanpa enkripsi akan ditolak

---

## 📝 Contoh Konfigurasi

### Development Environment

```php
$config['audit_token_expiry'] = 5;
$config['audit_token_auto_extend'] = TRUE;
$config['audit_rate_limit'] = 0;
$config['audit_api_timeout'] = 30;
$config['audit_log_retention'] = 7;
$config['audit_auto_clean_logs'] = TRUE;
$config['audit_require_https'] = FALSE;
$config['audit_ip_whitelist'] = [];
$config['audit_require_encryption'] = FALSE;
```

### Production Environment (Recommended)

```php
$config['audit_token_expiry'] = 15;
$config['audit_token_auto_extend'] = TRUE;
$config['audit_rate_limit'] = 1000;
$config['audit_api_timeout'] = 30;
$config['audit_log_retention'] = 90;
$config['audit_auto_clean_logs'] = TRUE;
$config['audit_require_https'] = TRUE;
$config['audit_ip_whitelist'] = [];
$config['audit_require_encryption'] = FALSE;
```

### High Security Environment

```php
$config['audit_token_expiry'] = 10;
$config['audit_token_auto_extend'] = TRUE;
$config['audit_rate_limit'] = 500;
$config['audit_api_timeout'] = 20;
$config['audit_log_retention'] = 365;
$config['audit_auto_clean_logs'] = TRUE;
$config['audit_require_https'] = TRUE;
$config['audit_ip_whitelist'] = [
    '192.168.1.100',
    '10.0.0.50'
];
$config['audit_require_encryption'] = TRUE;
```

---

## 🔄 Cara Mengubah Konfigurasi

1. **Edit file:** `application/config/audit_config.php`
2. **Ubah nilai** sesuai kebutuhan
3. **Save file**
4. **Tidak perlu restart** - langsung aktif

---

## ⚠️ Catatan Penting

1. **Token Expiry:**
   - Jangan terlalu pendek (< 5 menit) - terlalu sering refresh
   - Jangan terlalu panjang (> 60 menit) - kurang aman
   - Recommended: 15-30 menit

2. **Auto-Extend:**
   - Selalu TRUE untuk production
   - FALSE hanya untuk testing

3. **Rate Limiting:**
   - Set sesuai traffic aplikasi
   - Monitor di `/audit/api_log`

4. **HTTPS:**
   - Wajib TRUE di production
   - Protect credentials & data

5. **IP Whitelist:**
   - Gunakan jika sistem internal
   - Kosongkan untuk public API

---

## 📞 Support

Jika ada pertanyaan tentang konfigurasi, hubungi admin sistem.

---

**Happy Configuring! ⚙️**
