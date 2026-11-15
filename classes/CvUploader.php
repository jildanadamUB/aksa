<?php
namespace App;

// Menangani upload file CV: validasi, lalu simpan ke folder uploads/cv/
class CvUploader
{
    // Konstanta: nilai tetap selama program berjalan
    const MAX_SIZE = 10 * 1024 * 1024;   // 10 MB dalam byte
    const ALLOWED  = [
        'pdf'  => 'application/pdf',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
    ];

    private string $folder;
    private string $error = '';

    public function __construct()
    {
        $this->folder = __DIR__ . '/../uploads/cv/';
        if (!is_dir($this->folder)) {
            mkdir($this->folder, 0755, true);   // buat folder kalau belum ada
        }
    }

    // Mengembalikan nama file yang tersimpan, atau null kalau gagal
    public function upload(array $file, int $userId): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->error = "Upload gagal. Pastikan file CV sudah dipilih.";
            return null;
        }
        if ($file['size'] > self::MAX_SIZE) {
            $this->error = "Ukuran file maksimal 10 MB.";
            return null;
        }

        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $mime = mime_content_type($file['tmp_name']);

        // Ekstensi harus ada di whitelist DAN isi file harus sesuai ekstensinya
        if (!isset(self::ALLOWED[$ext]) || self::ALLOWED[$ext] !== $mime) {
            $this->error = "Format file harus PDF, PNG, atau JPG.";
            return null;
        }

        // Nama baru buatan sendiri, tidak memakai nama asli dari user
        $namaBaru = 'cv_' . $userId . '_' . time() . '.' . $ext;

        if (!move_uploaded_file($file['tmp_name'], $this->folder . $namaBaru)) {
            $this->error = "File gagal disimpan.";
            return null;
        }
        return $namaBaru;
    }

    public function getError(): string
    {
        return $this->error;
    }
}