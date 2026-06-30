# Implementation Plan — Fixing & Debugging

## Ringkasan Hasil Audit

Setelah audit menyeluruh pada seluruh file repositori, ditemukan **1 root cause utama** yang menyebabkan **kedua bug sekaligus**, ditambah 1 masalah terkait pada filter route.

---

## Bug #1: Admin Tidak Bisa Menambahkan Meja Baru

### Root Cause

Pada `app/Controllers/Meja.php` method `store()` (baris 57-58), setelah meja berhasil disimpan ke database, controller langsung memanggil:

```php
helper('qr');
generate_qr_meja($nomorMeja);
```

Fungsi `generate_qr_meja()` di `app/Helpers/qr_helper.php` menggunakan **API library `chillerlan/php-qrcode` versi lama (v4/v5)**:

```php
// KODE SAAT INI (SALAH — API v4/v5):
$options = new QROptions([
    'version'      => 5,
    'outputType'   => ChillerlanQRCode::OUTPUT_IMAGE_PNG,  // ❌ TIDAK ADA di v6
    'eccLevel'     => ChillerlanQRCode::ECC_L,             // ❌ TIDAK ADA di v6
    'scale'        => 10,
    'imageBase64'  => false,                               // ❌ Property ini sudah diganti nama
]);
```

Tetapi `composer.json` menginstall **`chillerlan/php-qrcode` v6.x**, yang **menghapus semua class constants** tersebut. Di v6:
- `QRCode::OUTPUT_IMAGE_PNG` → **tidak ada** → diganti dengan `outputInterface` berupa FQCN class
- `QRCode::ECC_L` → **tidak ada** → diganti dengan `EccLevel::L` dari class `chillerlan\QRCode\Common\EccLevel`
- `imageBase64` → **diganti** menjadi `outputBase64`

Akibatnya, ketika `store()` dipanggil, PHP melempar **fatal error** `Undefined constant chillerlan\QRCode\QRCode::OUTPUT_IMAGE_PNG` (terlihat pada screenshot error di folder `error_message/`). Karena error terjadi **sebelum redirect**, maka:
- Meja **mungkin sudah tersimpan** di DB, tapi user melihat halaman error
- Atau pada beberapa konfigurasi, seluruh request gagal dan meja tidak tersimpan

### Instruksi Fix

**File yang diedit:** `app/Helpers/qr_helper.php`

**Langkah:**

1. Tambahkan import/use untuk class yang dibutuhkan di v6:
   ```php
   use chillerlan\QRCode\QRCode as ChillerlanQRCode;
   use chillerlan\QRCode\QROptions;
   use chillerlan\QRCode\Common\EccLevel;
   use chillerlan\QRCode\Output\QRGdImagePNG;
   ```

2. Di **fungsi `generate_qr_meja()`** (sekitar baris 19-26), ganti blok `$options` dari:
   ```php
   $options = new QROptions([
       'version'      => 5,
       'outputType'   => ChillerlanQRCode::OUTPUT_IMAGE_PNG,
       'eccLevel'     => ChillerlanQRCode::ECC_L,
       'scale'        => 10,
       'imageBase64'  => false,
   ]);
   ```
   Menjadi:
   ```php
   $options = new QROptions([
       'version'          => 5,
       'outputInterface'  => QRGdImagePNG::class,
       'eccLevel'         => EccLevel::L,
       'scale'            => 10,
       'outputBase64'     => false,
   ]);
   ```

3. Di **fungsi `generate_qr_takeaway()`** (sekitar baris 63-69), lakukan **perubahan yang sama persis** pada blok `$options`.

4. Pastikan pemanggilan `$qrcode->render($url, $filePath)` **tidak perlu diubah** — method signature-nya tetap sama di v6.

---

## Bug #2: Admin Tidak Bisa Generate QR Code pada Meja Baru

### Root Cause

**Sama persis** dengan Bug #1. Error terjadi di `qr_helper.php` karena konstanta yang tidak ada.

Bukti dari screenshot `error_message/Screenshot 2026-06-30 122200.png`:
```
Error: Undefined constant chillerlan\QRCode\QRCode::OUTPUT_IMAGE_PNG
APPPATH\Helpers\qr_helper.php at line 65
```

Backtrace (`Screenshot 2026-06-30 122246.png`) menunjukkan error dipicu dari `QRCode::generateTakeaway()` → `generate_qr_takeaway()` di `qr_helper.php`.

Jadi baik generate QR saat tambah meja (Bug #1), generate satuan dari halaman QR Code, maupun generate semua dari halaman QR Code — **semuanya gagal karena root cause yang sama**.

### Instruksi Fix

**Tidak ada langkah tambahan.** Fix di Bug #1 sudah menyelesaikan Bug #2 sekaligus, karena kedua fungsi (`generate_qr_meja` dan `generate_qr_takeaway`) ada di file yang sama dan menggunakan pattern API yang sama.

---

## Catatan Tambahan dari Audit

### Filter Route `auth:admin` — Potensial Masalah

Di `Routes.php`, route group menggunakan `'filter' => 'auth:admin'`. Sintaks `auth:admin` di CI4 artinya **memanggil filter `auth` dengan argument `admin`**. Namun, `AuthFilter.php` hanya mengecek `session()->get('logged_in')` — **tidak mengecek role sama sekali** dan tidak menggunakan `$arguments`.

Pengecekan role seharusnya dilakukan oleh `RoleFilter` yang memang sudah terdaftar sebagai alias `role` di `Filters.php`.

**Ini berarti**: Route `meja`, `qrcode`, `kategori`, `menu`, `laporan`, `user` **tidak punya proteksi role** — user kasir bisa mengakses halaman admin jika mereka tahu URL-nya. Menu sidebar memang tersembunyi untuk non-admin, tapi URL-nya tidak terproteksi.

**Opsi fix** (opsional, tapi disarankan): Ubah filter di Routes.php dari `'filter' => 'auth:admin'` menjadi `'filter' => ['auth', 'role:admin']` agar kedua filter berjalan, ATAU tambahkan logika role-checking ke dalam `AuthFilter` agar menerima argument role.

---

## Checklist Ringkas

| # | File | Aksi | Prioritas |
|---|------|------|-----------|
| 1 | `app/Helpers/qr_helper.php` | Tambah `use` untuk `EccLevel`, `QRGdImagePNG` | **Wajib** |
| 2 | `app/Helpers/qr_helper.php` | Ganti `outputType` → `outputInterface` + FQCN class | **Wajib** |
| 3 | `app/Helpers/qr_helper.php` | Ganti `ChillerlanQRCode::ECC_L` → `EccLevel::L` | **Wajib** |
| 4 | `app/Helpers/qr_helper.php` | Ganti `imageBase64` → `outputBase64` | **Wajib** |
| 5 | `app/Helpers/qr_helper.php` | Lakukan perubahan #2-#4 untuk **kedua fungsi** (`generate_qr_meja` dan `generate_qr_takeaway`) | **Wajib** |
| 6 | `app/Config/Routes.php` | *(Opsional)* Perbaiki filter dari `auth:admin` menjadi `['auth', 'role:admin']` | Disarankan |

---

## Verifikasi Setelah Fix

1. Login sebagai admin
2. Buka halaman Manajemen Meja → klik "Tambah Meja" → isi nomor → submit
3. Pastikan meja baru muncul di daftar dan **tidak ada error**
4. Buka halaman QR Code Meja → pastikan meja baru muncul dengan tombol "Generate QR"
5. Klik "Generate QR" pada meja baru → pastikan gambar QR muncul
6. Klik "Generate Ulang Semua QR Code" → pastikan semua QR (termasuk Takeaway) berhasil
7. Cek folder `public/QR_images/` — pastikan file `.png` baru terbuat
