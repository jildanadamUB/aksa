# Changelog

Semua perubahan penting pada project Aksa. Versi terbaru di paling atas. Penomoran versi mengikuti format MAYOR.MINOR.PATCH.

## [1.1.0] - 2026-11-17
### Ditambahkan
- API JSON `api/lowongan.php` (GET): daftar lowongan, pencarian, pengurutan, detail berdasarkan id
- Kode status HTTP 200, 404, 405, 500 dan penangan error khusus API yang menjawab dengan JSON
- Bagian API di README

## [1.0.0] - 2026-11-17
### Diubah
- Home mengambil 3 kursus dengan rating tertinggi dan 3 lowongan terbaru dari basis data (sebelumnya data contoh berupa array)
- Kartu di Home bisa diklik menuju halaman detail
### Diperbaiki
- Pengamanan teks dilakukan dua kali pada sapaan nama di Home
### Ditambahkan
- README.md

## [0.10.0] - 2026-11-16
### Ditambahkan
- AksaLearn: tabel `courses`, class `Course`, daftar kursus dengan filter kategori dan pencarian
- Detail kursus dengan pemutar video HTML5 (file lokal, pengecekan file ada)

## [0.9.0] - 2026-11-16
### Ditambahkan
- Unggah CV saat melamar (class `CvUploader`: batas ukuran, ekstensi, MIME, nama file baru)
- Catatan error ke `logs/error.log`, penangan error global, halaman gangguan saat basis data mati
- Peringatan error terbaru dan pemantauan penyimpanan CV (kuota 100 MB) di dashboard admin

## [0.8.0] - 2026-11-16
### Ditambahkan
- View, function, stored procedure, dan trigger di basis data
- Halaman riwayat lamaran (memakai stored procedure dan function)
- Halaman profil dengan ubah nama

## [0.7.0] - 2026-11-15
### Ditambahkan
- Class `Admin` turunan `User`, pengalihan setelah login sesuai peran, penjaga halaman admin
- Kelola lowongan oleh admin: tambah, ubah, hapus
### Diperbaiki
- Penamaan variabel hasil code review

## [0.6.0] - 2026-11-15
### Ditambahkan
- Halaman detail lowongan
- Melamar lowongan dengan transaksi (`commit`/`rollback`), class `Application`

## [0.5.0] - 2026-11-15
### Ditambahkan
- Tabel `jobs` dan `applications` dengan foreign key dan index
- Interface `CrudInterface` dan class `Job`
- Halaman AksaWork: daftar lowongan, pencarian, pengurutan
- Fungsi `formatRupiah()` dan `e()`

## [0.4.0] - 2026-11-14
### Ditambahkan
- Logout, proteksi halaman, nama pengguna dari session

## [0.3.0] - 2026-11-14
### Ditambahkan
- Register dan login dengan class `User` (prepared statement, password hash, session)

## [0.2.0] - 2026-11-14
### Diubah
- Koneksi basis data dari mysqli prosedural menjadi class `Database` (OOP), ditambah `config/init.php` dan autoload

## [0.1.0] - 2026-11-14
### Ditambahkan
- Tampilan login, register, dan home (Bootstrap, warna dan font sesuai Figma)
- Template header, footer, navbar
- Basis data `aksa` dan tabel `users`
