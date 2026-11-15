CREATE DATABASE aksa;
USE aksa;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    posisi VARCHAR(100) NOT NULL,
    perusahaan VARCHAR(100) NOT NULL,
    kota VARCHAR(50) NOT NULL,
    tipe ENUM('Fulltime', 'Parttime', 'Remote', 'Magang') NOT NULL,
    gaji INT NOT NULL,
    deskripsi TEXT,
    kualifikasi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_kota (kota),
    INDEX idx_posisi (posisi)
);

CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_id INT NOT NULL,
    cv VARCHAR(255),
    status ENUM('Terkirim', 'Ditinjau', 'Diterima', 'Ditolak') NOT NULL DEFAULT 'Terkirim',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    UNIQUE (user_id, job_id)
);

INSERT INTO jobs (posisi, perusahaan, kota, tipe, gaji, deskripsi, kualifikasi) VALUES
('Product Designer', 'Yahoo', 'Jakarta', 'Fulltime', 7500000, 'Merancang pengalaman pengguna untuk produk digital Yahoo.', 'Menguasai Figma\nMemahami prinsip UX\nPengalaman 1 tahun'),
('IT Support', 'Zenius', 'Bandung', 'Parttime', 4500000, 'Menangani masalah perangkat dan jaringan kantor.', 'Memahami jaringan dasar\nTeliti dan komunikatif'),
('Digital Marketing', 'Tokopedia', 'Jakarta', 'Fulltime', 20000000, 'Merencanakan dan menjalankan kampanye pemasaran digital.', 'Menguasai SEO dan iklan digital\nPengalaman 3 tahun'),
('Web Developer', 'Ruangguru', 'Jakarta', 'Remote', 9000000, 'Membangun dan memelihara aplikasi web.', 'Menguasai PHP dan MySQL\nMemahami HTML, CSS, JavaScript'),
('Content Writer', 'Kompas', 'Yogyakarta', 'Magang', 2500000, 'Menulis artikel dan konten media sosial.', 'Mampu menulis dengan baik\nMemahami SEO dasar');

ALTER TABLE jobs ADD jumlah_pelamar INT NOT NULL DEFAULT 0;

CREATE VIEW v_riwayat_lamaran AS
SELECT a.id, a.user_id, u.nama, j.id AS job_id, j.posisi, j.perusahaan, j.kota,
       a.status, a.cv, a.created_at
FROM applications a
JOIN users u ON a.user_id = u.id
JOIN jobs  j ON a.job_id  = j.id;

DELIMITER $$
CREATE FUNCTION fn_jumlah_lamaran(p_user_id INT) RETURNS INT
READS SQL DATA
BEGIN
    DECLARE total INT;
    SELECT COUNT(*) INTO total FROM applications WHERE user_id = p_user_id;
    RETURN total;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_riwayat_lamaran(IN p_user_id INT)
BEGIN
    SELECT * FROM v_riwayat_lamaran
    WHERE user_id = p_user_id
    ORDER BY created_at DESC;
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER trg_kurangi_pelamar
AFTER DELETE ON applications
FOR EACH ROW
BEGIN
    UPDATE jobs SET jumlah_pelamar = jumlah_pelamar - 1
    WHERE id = OLD.job_id;
END $$
DELIMITER ;

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    deskripsi TEXT,
    rating DECIMAL(2,1) NOT NULL DEFAULT 0,
    jumlah_video INT NOT NULL DEFAULT 0,
    jumlah_bacaan INT NOT NULL DEFAULT 0,
    video VARCHAR(255),
    INDEX idx_kategori (kategori)
);

INSERT INTO courses (judul, kategori, deskripsi, rating, jumlah_video, jumlah_bacaan, video) VALUES
('UI/UX Design', 'Desain & Kreatif', 'Pelajari dasar riset pengguna, wireframe, hingga prototipe interaktif.', 5.0, 5, 3, 'intro-uiux.mp4'),
('Desain Grafis', 'Desain & Kreatif', 'Prinsip desain visual, tipografi, dan komposisi warna.', 4.7, 6, 2, NULL),
('Editor Video', 'Desain & Kreatif', 'Teknik dasar memotong, menyusun, dan memberi efek pada video.', 4.5, 8, 1, NULL),
('Ilustrator Digital', 'Desain & Kreatif', 'Menggambar ilustrasi digital dari sketsa hingga final.', 4.6, 5, 2, NULL),
('Digital Marketing', 'Pemasaran Digital', 'Strategi pemasaran online, media sosial, dan iklan digital.', 4.8, 6, 2, NULL),
('Content Writer SEO', 'Penulisan & Konten', 'Menulis konten yang menarik dan mudah ditemukan mesin pencari.', 4.6, 4, 4, NULL),
('Administrasi Digital', 'Bisnis & Administrasi', 'Mengelola dokumen dan data kantor dengan alat digital.', 4.5, 5, 3, NULL),
('QA Software Tester', 'Teknologi & Web', 'Dasar pengujian perangkat lunak dan menulis skenario uji.', 4.7, 6, 3, NULL);