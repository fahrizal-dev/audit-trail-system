# 🎨 Design Guide - Audit Trail System

## Panduan Penggunaan Desain Baru

---

## 📱 Halaman-Halaman

### 1. Login Page
**URL:** `http://localhost/audit-trail/auth/login`

**Fitur:**
- Form login dengan animasi background
- Auto-redirect jika sudah login
- Alert error yang auto-hide
- Responsive untuk mobile

**Cara Pakai:**
1. Masukkan username/email
2. Masukkan password
3. Klik "Masuk ke Dashboard"

---

### 2. Dashboard Home
**URL:** `http://localhost/audit-trail/audit/home`

**Fitur:**
- 2 Card utama: Activity Log & API Log
- Export buttons untuk CSV
- Logout button dengan konfirmasi

**Navigasi:**
- Klik "Lihat Activity Log" → ke halaman Activity
- Klik "Lihat API Log" → ke halaman API Log
- Klik "Export Activity CSV" → download CSV
- Klik "Export API CSV" → download CSV
- Klik "Logout" → konfirmasi logout

---

### 3. Activity Log Page
**URL:** `http://localhost/audit-trail/audit/activity`

**Fitur:**
- Stats bar (Total, Page, Per Page)
- Filter lengkap dengan 9 options
- Table dengan data activity
- Pagination
- Detail modal saat klik row

**Filter Options:**
1. **Urutkan:** Default / Terbaru / Terlama
2. **Aplikasi:** Dropdown semua aplikasi
3. **User:** Input text untuk cari user
4. **Aksi:** Dropdown semua aksi
5. **Hasil:** Dropdown success/failed
6. **Keyword:** Cari di keterangan
7. **Tanggal Awal:** Date picker
8. **Tanggal Akhir:** Date picker (auto-validate)
9. **Tampilkan:** 10 data / Semua data

**Cara Pakai Filter:**
1. Pilih filter yang diinginkan
2. Klik "Terapkan Filter"
3. Klik "Reset" untuk clear semua filter

**Cara Lihat Detail:**
1. Klik pada row table
2. Modal akan muncul dengan detail lengkap
3. Klik "Close" untuk tutup modal

---

### 4. API Log Page
**URL:** `http://localhost/audit-trail/audit/api_log`

**Fitur:**
- Stats bar untuk API calls
- Filter dengan 5 options
- Table dengan method badges
- Request/Response preview
- Detail modal dengan formatted JSON

**Filter Options:**
1. **Urutkan:** Default / Terbaru / Terlama
2. **Tanggal Awal:** Date picker
3. **Tanggal Akhir:** Date picker
4. **Keyword:** Cari di request/response
5. **Tampilkan:** 10 data / Semua data

**Method Badges:**
- 🔵 GET (Blue)
- 🟢 POST (Green)
- 🟡 PUT (Yellow)
- 🔴 DELETE (Red)

**Cara Lihat Detail:**
1. Klik pada row table
2. Modal muncul dengan:
   - Info waktu, IP, metode
   - Request JSON (formatted)
   - Response JSON (formatted)
3. Klik "Close" untuk tutup

---

### 5. Manage Aplikasi & Token
**URL:** `http://localhost/audit-trail/audit/apps`

**Fitur:**
- Daftar semua aplikasi terdaftar
- **Form registrasi aplikasi baru** (NEW!)
- Generate token untuk aplikasi
- Lihat & copy credentials (ID, username, secret key)
- Manage status aplikasi (aktif/nonaktif)
- Lihat daftar token aktif & expired
- Revoke/hapus token
- Dokumentasi API lengkap

**Cara Daftar Aplikasi Baru:**
1. Klik tombol "Daftar Aplikasi Baru"
2. Isi form:
   - Nama Aplikasi (contoh: SIMRS)
   - Username (contoh: simrs_app)
   - Password (minimal 8 karakter)
3. Klik "Daftar Aplikasi"
4. Modal sukses akan muncul dengan credentials
5. **PENTING:** Simpan semua credentials (ID, username, password, secret key)
6. Klik "Tutup & Reload" untuk refresh halaman

**Cara Generate Token:**
1. Klik tombol "Generate Token" pada aplikasi
2. Token baru akan muncul di daftar token
3. Copy token untuk digunakan di sistem lain
4. Token berlaku 15 menit, auto-extend saat dipakai

**Cara Revoke Token:**
1. Klik tombol "Hapus" pada token yang ingin dihapus
2. Konfirmasi penghapusan
3. Token tidak bisa digunakan lagi

**Cara Nonaktifkan Aplikasi:**
1. Klik tombol "Nonaktifkan" pada aplikasi
2. Aplikasi tidak bisa request token baru
3. Token existing masih bisa dipakai sampai expired

**Info Credentials:**
- **ID Aplikasi:** Untuk header `x-userid`
- **Username:** Nama user aplikasi
- **Secret Key:** Untuk enkripsi RC4 (opsional)
- Semua bisa di-copy dengan klik icon copy

---

## 🎨 Design Elements

### Buttons

#### Primary Button
```html
<button class="btn btn-primary">Button Text</button>
```
- Gradient purple
- Untuk aksi utama

#### Success Button
```html
<button class="btn btn-success">Button Text</button>
```
- Gradient green
- Untuk export/save

#### Danger Button
```html
<button class="btn btn-danger">Button Text</button>
```
- Gradient red
- Untuk logout/delete

#### Outline Buttons
```html
<button class="btn btn-outline-primary">Button Text</button>
<button class="btn btn-outline-secondary">Button Text</button>
```
- White background dengan border
- Untuk aksi sekunder

---

### Cards

#### Glass Card
```html
<div class="card-glass">
  <!-- Content -->
</div>
```
- Background putih transparan
- Blur effect
- Shadow halus

---

### Forms

#### Input Field
```html
<div>
  <label class="form-label">Label</label>
  <input type="text" class="form-control" placeholder="Placeholder">
</div>
```

#### Select Dropdown
```html
<div>
  <label class="form-label">Label</label>
  <select class="form-select">
    <option>Option 1</option>
    <option>Option 2</option>
  </select>
</div>
```

---

### Modals

#### Show Modal
```javascript
showModal('modalId');
```

#### Hide Modal
```javascript
hideModal('modalId');
```

#### Modal Structure
```html
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Title</h2>
      </div>
      <div class="modal-body">
        Content
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" onclick="hideModal('myModal')">Close</button>
      </div>
    </div>
  </div>
</div>
```

---

### Alerts

#### Success Alert
```html
<div class="alert alert-success">
  Success message
</div>
```

#### Error Alert
```html
<div class="alert alert-error">
  Error message
</div>
```

**Note:** Alerts auto-hide setelah 5 detik

---

### Tables

#### Basic Table
```html
<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th>Header 1</th>
        <th>Header 2</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Data 1</td>
        <td>Data 2</td>
      </tr>
    </tbody>
  </table>
</div>
```

**Features:**
- Hover effect pada rows
- Responsive scroll
- Gradient header

---

### Status Pills

```html
<span class="status-pill" style="background: #10b981; color: white;">
  Success
</span>

<span class="status-pill" style="background: #ef4444; color: white;">
  Failed
</span>
```

---

## 🎯 Animations

### Available Animations:
1. **slideDown** - Header entrance
2. **fadeIn** - Cards entrance
3. **slideInRight** - Alerts entrance
4. **scaleIn** - Modal entrance
5. **spin** - Loading spinner

### Usage:
```css
animation: fadeIn 0.6s ease-out;
```

---

## 📱 Responsive Design

### Desktop (> 768px)
- Full layout dengan sidebar
- Multi-column grids
- Hover effects aktif

### Mobile (≤ 768px)
- Single column layout
- Stacked buttons
- Touch-friendly spacing
- Simplified tables

---

## 🎨 Color Usage Guide

### When to Use:

**Primary (Purple):**
- Main actions
- Links
- Active states

**Success (Green):**
- Success messages
- Export buttons
- Positive actions

**Warning (Orange):**
- API-related items
- Warnings
- Caution states

**Danger (Red):**
- Errors
- Delete actions
- Logout
- Failed states

**Secondary (Gray):**
- Cancel buttons
- Disabled states
- Secondary actions

---

## 🔧 Customization

### Mengubah Warna Tema:

Edit `assets/css/audit.css`:
```css
:root {
    --primary: #6366f1;     /* Ubah warna primary */
    --secondary: #8b5cf6;   /* Ubah warna secondary */
    --success: #10b981;     /* Ubah warna success */
    --warning: #f59e0b;     /* Ubah warna warning */
    --danger: #ef4444;      /* Ubah warna danger */
}
```

### Mengubah Font:

Edit di `<head>`:
```html
<link href="https://fonts.googleapis.com/css2?family=YourFont:wght@300;400;600;700&display=swap" rel="stylesheet">
```

Lalu di CSS:
```css
body {
    font-family: 'YourFont', sans-serif;
}
```

---

## 💡 Tips & Tricks

### 1. Loading State
```javascript
showLoading();  // Show spinner
// ... do something
hideLoading();  // Hide spinner
```

### 2. Toast Notification
```javascript
showToast('Message here', 'success');
showToast('Error message', 'error');
```

### 3. Copy to Clipboard
```javascript
copyToClipboard('Text to copy');
```

### 4. Format JSON
```javascript
const formatted = formatJSON(jsonString);
```

### 5. Debounce Search
```javascript
const debouncedSearch = debounce(function(term) {
    // Search logic
}, 500);
```

---

## 🐛 Troubleshooting

### Modal tidak muncul?
- Pastikan ID modal benar
- Cek console untuk error
- Pastikan `audit.js` ter-load

### Filter tidak bekerja?
- Cek form action URL
- Pastikan name attribute benar
- Cek backend controller

### Styling tidak muncul?
- Clear browser cache
- Cek path CSS file
- Inspect element untuk debug

### Responsive tidak bekerja?
- Cek viewport meta tag
- Test di browser dev tools
- Cek media queries

---

## 📞 Support

Jika ada pertanyaan atau issue:
1. Cek DESIGN_CHANGELOG.md untuk detail perubahan
2. Cek browser console untuk error
3. Test di browser berbeda
4. Clear cache dan reload

---

**Happy Coding! 🚀**
