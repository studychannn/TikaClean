# TikaClean - Sistem Pelaporan Sampah Liar

Aplikasi web Proyek 2 untuk laporan sampah liar dengan GPS otomatis, foto kondisi, tracking pekerjaan, dashboard admin, dan grafik laporan.

## URL

- User: `http://localhost/tikaclean/`
- Tentang: `http://localhost/tikaclean/user/tentang.php`
- Tracking: `http://localhost/tikaclean/tracking.php`
- Laporan Saya: `http://localhost/tikaclean/user/laporan-saya.php`
- Login user: `http://localhost/tikaclean/user/login.php`
- Register user: `http://localhost/tikaclean/user/register.php`
- Admin: `http://localhost/tikaclean/admin/`
- Login admin: `http://localhost/tikaclean/admin/login.php`

## Database MySQL

Database memakai MySQL agar bisa dikelola lewat phpMyAdmin.

Cara import manual:
1. Buka `http://localhost/phpmyadmin`.
2. Pilih menu SQL atau Import.
3. Jalankan/import file `database/database.sql`.
4. Pastikan database bernama `tikaclean` dan tabel `users` serta `reports` sudah ada.

Konfigurasi koneksi ada di `app/db.php`.

Default XAMPP:
- Host: `localhost`
- Database: `tikaclean`
- User: `root`
- Password: kosong

## Akses

- User dapat register, login, logout, mengirim laporan, melihat laporan sendiri, dan tracking.
- Admin memakai folder khusus `admin/`.
- Default admin: `admin` / `admin123`

## Fitur

- Lokasi GPS otomatis dengan feedback visual
- Foto kondisi laporan dengan validasi tipe file
- Register, login, dan logout user
- Laporan Saya — riwayat laporan per akun
- Tracking status pekerjaan dengan filter dan pencarian
- Dashboard admin dengan filter, pencarian, dan notifikasi update
- Grafik laporan per tanggal (line) dan distribusi status (donut)
- Halaman Tentang dengan kategori laporan
- Logo SVG dan favicon
- Navbar dan footer konsisten
- Folder user dan admin terpisah

## Struktur

- `index.php` - Pintu masuk user, memuat `user/index.php`
- `tracking.php` - Pintu tracking, memuat `user/tracking.php`
- `submit.php` - Pintu submit, memuat `user/submit.php`
- `user/` - Halaman dan proses milik user
- `user/tentang.php` - Halaman tentang TikaClean
- `user/laporan-saya.php` - Riwayat laporan milik user
- `admin/` - Login, dashboard, dan logout admin
- `app/db.php` - Koneksi database MySQL
- `app/auth.php` - Autentikasi user dan admin
- `app/layout.php` - Navbar dan footer bersama
- `assets/` - CSS, JavaScript, logo, dan favicon
- `uploads/` - Penyimpanan foto laporan
- `database/database.sql` - Struktur database untuk import phpMyAdmin
