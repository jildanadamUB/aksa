# Aksa: Platform Belajar dan Lowongan Kerja

Aksa adalah aplikasi web responsif untuk mencari lowongan kerja dan mengikuti kursus singkat. Project ini dibuat sebagai portofolio Sertifikasi BNSP Skema **Web Developer**.

Tampilannya diimplementasikan dari desain UI/UX "Aksa" yang meraih **Juara 1 UI/UX Design Competition, Informatics Festival 2025**. Desain aslinya untuk aplikasi HP, lalu disesuaikan menjadi web yang bisa dibuka di HP maupun laptop.

## Fitur

| Modul | Fitur |
| --- | --- |
| Autentikasi | Register, login, logout, proteksi halaman, peran `user` dan `admin` |
| AksaWork | Daftar lowongan, pencarian, pengurutan (terbaru, gaji tertinggi, gaji terendah), detail lowongan, melamar dengan unggah CV, riwayat lamaran beserta status |
| AksaLearn | Daftar kursus, filter kategori, pencarian, detail kursus dengan pemutar video |
| Admin | Tambah, ubah, hapus lowongan; statistik pengguna, lowongan, dan lamaran; pemantauan penyimpanan CV; peringatan error terbaru |
| Profil | Data pengguna, jumlah lamaran, ubah nama |

## Teknologi

- PHP 8 dengan pemrograman berorientasi objek (namespace `App`, autoload, interface, inheritance)
- MySQL/MariaDB melalui `mysqli` dengan prepared statement
- Bootstrap 5.3, Bootstrap Icons, font Rubik
- HTML5 `<video>` untuk materi kursus
- Git untuk riwayat versi

## Struktur folder

```
aksa/
├── admin/        Dashboard dan kelola lowongan (khusus admin)
├── assets/       CSS dan video
├── auth/         Login, register, logout
├── classes/      Class PHP: Database, User, Admin, Job, Application, CvUploader, Course, CrudInterface
├── config/       init.php (session, autoload, penangan error, koneksi)
├── includes/     Template (header, footer, navbar), penjaga halaman, fungsi bantu, halaman gangguan
├── learn/        AksaLearn
├── work/         AksaWork
├── index.php     Home
├── profile.php   Profil
└── database.sql  Struktur dan data contoh basis data
```

## Basis data

`database.sql` membuat basis data `aksa` berisi:

- 4 tabel: `users`, `jobs`, `applications`, `courses`, dengan foreign key, `ON DELETE CASCADE`, `UNIQUE`, dan index
- View `v_riwayat_lamaran` (JOIN tiga tabel)
- Function `fn_jumlah_lamaran`
- Stored procedure `sp_riwayat_lamaran`
- Trigger `trg_kurangi_pelamar`
- Data contoh: 5 lowongan dan 8 kursus

Proses melamar memakai transaksi (`commit` dan `rollback`).

## Cara menjalankan

1. Pasang [XAMPP](https://www.apachefriends.org/) dengan PHP 8.
2. Salin folder project ke `C:\xampp\htdocs\aksa`.
3. Buka XAMPP Control Panel, jalankan **Apache** dan **MySQL**.
4. Buka `http://localhost/phpmyadmin`, pilih **Import**, lalu unggah `database.sql`.
5. Buka `http://localhost/aksa` dan daftar akun baru.
6. Untuk menjadikan akun sebagai admin, jalankan di tab SQL phpMyAdmin:
   ```sql
   UPDATE users SET role = 'admin' WHERE email = 'email@kamu.com';
   ```
7. (Opsional) Simpan video MP4 di `assets/video/intro-uiux.mp4` agar video kursus UI/UX Design bisa diputar. File video tidak disertakan di repository karena ukurannya besar.

Folder `uploads/` (file CV) dan `logs/` (catatan error) dibuat otomatis saat pertama kali dibutuhkan, dan tidak disimpan di repository.

## Keamanan yang diterapkan

- Prepared statement untuk semua query yang memuat input pengguna (mencegah SQL Injection)
- Kata sandi disimpan sebagai hash (`password_hash`, `password_verify`)
- Semua teks yang ditampilkan diamankan dengan `htmlspecialchars` (mencegah XSS)
- ID session diganti setelah login
- Pilihan pengurutan, tipe pekerjaan, dan kategori dibatasi dengan daftar nilai yang diizinkan
- Unggah CV dibatasi ukuran (10 MB), ekstensi, dan isi file (MIME), lalu disimpan dengan nama baru
- Hapus data hanya lewat form POST dengan konfirmasi
- Detail error hanya dicatat di log; pengunjung melihat halaman gangguan yang ramah

## Catatan

Koneksi basis data di `classes/Database.php` memakai pengaturan bawaan XAMPP (`root` tanpa kata sandi). Pengaturan ini hanya untuk pengembangan di komputer lokal.

## Pembuat

Jildan Adam Zein, S1 Teknik Komputer, Universitas Brawijaya
