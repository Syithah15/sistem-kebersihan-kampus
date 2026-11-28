# Lapor Bersih Kampus

Sistem Pelaporan & Monitoring Kebersihan Kampus berbasis web. Aplikasi ini dikembangkan sebagai proyek besar mata kuliah **Rekayasa Perangkat Lunak (RPL)** oleh **Kelompok 5, Kelas AC**, menggunakan metode **Scrum** serta kolaborasi **Git & GitHub**.

## Deskripsi

**Lapor Bersih Kampus** membantu civitas kampus melaporkan masalah kebersihan lingkungan kampus dan memastikan laporan tersebut ditindaklanjuti. Aplikasi ini mencakup:

- autentikasi pengguna (login/logout) sesuai peran,
- pelaporan masalah kebersihan oleh pelapor,
- penugasan dan tindak lanjut oleh petugas,
- ringkasan dan monitoring oleh admin,
- dashboard, peta sebaran lokasi, dan notifikasi.

## Fitur Utama

| User Story | Modul | Deskripsi |
| --- | --- | --- |
| US-1 | Autentikasi, Manajemen Pengguna & Database Migrations | Login/logout, pengelolaan pengguna, dan struktur database melalui migrations |
| US-2 | Dokumentasi Proyek & Halaman Utama | README dan tata letak (layout) utama aplikasi |
| US-3 | Modul Pelapor & Pengajuan Laporan | Pelapor membuat dan memantau laporan kebersihan kampus |
| US-4 | Modul Petugas & Tindak Lanjut Tugas | Petugas melihat daftar tugas dan memperbarui status tindak lanjut |
| US-5 | Modul Admin, Ringkasan & Monitoring | Admin melihat ringkasan laporan dan memonitor kinerja petugas |
| US-6 | Modul Dashboard, Peta Sebaran & Notifikasi | Dashboard, peta sebaran lokasi, dan notifikasi |

## Peran Pengguna

- **Pelapor**: membuat dan memantau laporan kebersihan.
- **Petugas**: melihat tugas dan menindaklanjuti laporan.
- **Admin**: memantau ringkasan laporan dan kinerja petugas.

## Teknologi

- PHP 8.2 atau lebih baru
- CodeIgniter 4
- MySQL (database: `sistem_kebersihan_kampus`)
- Composer
- Git & GitHub

## Persyaratan Sistem

- PHP 8.2 atau lebih baru (ekstensi `intl`, `mbstring`, dan `mysqlnd` aktif)
- Composer
- MySQL / MariaDB
- Git
- Disarankan memakai [Laragon](https://laragon.org/) di Windows

## Cara Instalasi

1. **Clone repository**

   ```bash
   git clone https://github.com/Syithah15/sistem-kebersihan-kampus.git
   cd sistem-kebersihan-kampus
   ```

2. **Pasang dependensi**

   ```bash
   composer install
   ```

3. **Siapkan file environment**

   Salin file `env` menjadi `.env`:

   ```bash
   copy env .env
   ```

   (di Linux/macOS: `cp env .env`)

4. **Atur konfigurasi di `.env`**

   Hapus tanda `#` di depan baris yang dibutuhkan, lalu sesuaikan:

   ```ini
   CI_ENVIRONMENT = development

   app.baseURL = 'http://localhost:8080/'

   database.default.hostname = localhost
   database.default.database = sistem_kebersihan_kampus
   database.default.username = root
   database.default.password = <password-mysql-kamu>
   database.default.DBDriver = MySQLi
   database.default.port = 3306
   ```

   > File `.env` bersifat pribadi dan **tidak boleh** di-push ke GitHub.

5. **Buat database**

   Buat database kosong bernama `sistem_kebersihan_kampus` di MySQL (lewat phpMyAdmin/HeidiSQL Laragon atau perintah berikut):

   ```sql
   CREATE DATABASE sistem_kebersihan_kampus;
   ```

6. **Jalankan migrations**

   ```bash
   php spark migrate
   ```

## Cara Menjalankan Aplikasi

```bash
php spark serve
```

Buka browser dan akses **http://localhost:8080/**.

## Akun Demo

Gunakan akun berikut untuk mencoba aplikasi:

| Peran | Email | Password |
| --- | --- | --- |
| Pelapor | pelapor@test.com | password123 |
| Petugas | petugas@test.com | password123 |

> Akun ini hanya untuk keperluan pengembangan dan demo. Jangan dipakai di lingkungan produksi.

## Anggota Tim (Kelompok 5)

| Nama | Peran Scrum | User Story | Branch |
| --- | --- | --- | --- |
| Masyithah | Scrum Master | US-1: Autentikasi & Database Migrations | `fitur/us-1-auth` |
| Muh. Dani Nugrah Ramadhan | Product Owner | US-2: Dokumentasi & Halaman Utama | `fitur/us-2-readme-layout` |
| Fatimah Alya Shabirah | Developer 1 | US-3: Modul Pelapor | `fitur/us-3-pelapor` |
| Reinaldi Novaldo | Developer 2 | US-4: Modul Petugas | `fitur/us-4-petugas` |
| Nurul Aprilianti Arif | Developer 3 | US-5: Modul Admin & Monitoring | `fitur/us-5-admin-monitoring` |
| Audryl Aulya Galo | Developer 4 | US-6: Dashboard, Peta Sebaran & Notifikasi | `fitur/us-6-dashboard-peta` |

## Alur Kolaborasi Git

- Setiap anggota bekerja di *branch* sendiri dengan format `fitur/<kode-us>-<nama-fitur>`.
- **Tidak boleh** push langsung ke branch `main`.
- Perubahan digabungkan melalui *Pull Request* yang di-*review* oleh satu rekan, lalu di-*merge* oleh Scrum Master.
- Urutan merge: US-1 → US-2 → US-3 → US-4 → US-5 → US-6.

## Lisensi

Proyek ini dibuat untuk keperluan pembelajaran pada mata kuliah Rekayasa Perangkat Lunak dan menggunakan lisensi MIT (lihat file `LICENSE`).
