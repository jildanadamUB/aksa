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