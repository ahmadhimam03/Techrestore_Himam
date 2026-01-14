-- Membuat Database Portfolio
CREATE DATABASE IF NOT EXISTS portfolio_techrestore;
USE portfolio_techrestore;

-- Tabel Portfolio
CREATE TABLE IF NOT EXISTS portfolio (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    deskripsi TEXT NOT NULL,
    kategori ENUM('Screen Repair', 'Battery Service', 'Hardware Repair', 'Software Fix', 'Water Damage', 'Other') NOT NULL,
    gambar VARCHAR(255) NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_kategori (kategori),
    INDEX idx_tanggal (tanggal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Testimoni
CREATE TABLE IF NOT EXISTS testimoni (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    komentar TEXT NOT NULL,
    rating INT(1) NOT NULL CHECK (rating >= 1 AND rating <= 5),
    status ENUM('active', 'inactive') DEFAULT 'active',
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Kontak/Pesan
CREATE TABLE IF NOT EXISTS kontak (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    pesan TEXT NOT NULL,
    status ENUM('unread', 'read', 'replied') DEFAULT 'unread',
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Admin
CREATE TABLE IF NOT EXISTS admin_portfolio (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    nama_lengkap VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Default Admin (password: admin123)
INSERT INTO admin_portfolio (username, password, email, nama_lengkap) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@techrestore.com', 'Administrator');

-- Insert Sample Portfolio Data
INSERT INTO portfolio (judul, deskripsi, kategori, gambar, tanggal) VALUES
('iPhone 13 Pro Max Screen Replacement', 'Complete screen replacement with original OLED display. Customer reported cracked screen after accidental drop. Repair completed in 1 hour with full functionality tested.', 'Screen Repair', 'sample1.jpg', '2024-01-15'),
('Samsung Galaxy S21 Battery Service', 'Battery replacement service for Galaxy S21 experiencing rapid battery drain. Replaced with original Samsung battery with 1-year warranty. Device now holds charge for full day.', 'Battery Service', 'sample2.jpg', '2024-01-10'),
('MacBook Pro Motherboard Repair', 'Complex motherboard repair for liquid damage. Successfully recovered all data and restored full functionality. Components cleaned and replaced where necessary.', 'Hardware Repair', 'sample3.jpg', '2024-01-05'),
('Xiaomi Redmi Note 10 Water Damage Recovery', 'Emergency water damage repair. Phone was submerged for 30 minutes. Complete disassembly, cleaning, and component testing. Successfully restored to working condition.', 'Water Damage', 'sample4.jpg', '2023-12-28'),
('OnePlus 9 Pro Camera Module Replacement', 'Replaced faulty camera module causing blurry images. Installed genuine OnePlus camera module. All camera functions tested and working perfectly.', 'Hardware Repair', 'sample5.jpg', '2023-12-20'),
('iPad Air Screen and Digitizer Repair', 'Screen and digitizer replacement for iPad Air. Touch functionality restored completely. High-quality replacement parts used with 6-month warranty.', 'Screen Repair', 'sample6.jpg', '2023-12-15');

-- Insert Sample Testimonials
INSERT INTO testimoni (nama, email, komentar, rating, status) VALUES
('Ahmad Rifai', 'ahmad@email.com', 'Excellent service! My iPhone screen was replaced quickly and the result is perfect. The technician was very professional and explained everything clearly.', 5, 'active'),
('Siti Nurhaliza', 'siti@email.com', 'Very satisfied with the battery replacement service. My phone battery now lasts much longer. Highly recommended!', 5, 'active'),
('Budi Santoso', 'budi@email.com', 'Great experience! They managed to save my water-damaged phone when I thought it was beyond repair. Thank you TechRestore!', 5, 'active'),
('Dewi Lestari', 'dewi@email.com', 'Fast and reliable service. My Samsung phone is working like new again after the screen repair. Will definitely come back if needed.', 5, 'active'),
('Raka Pratama', 'raka@email.com', 'Professional service with reasonable prices. The repair was done in just 2 hours as promised. Very happy with the results!', 4, 'active');

-- Create uploads directory instruction
-- Note: You need to manually create an 'uploads' directory in your project folder
-- and add sample images named: sample1.jpg, sample2.jpg, sample3.jpg, sample4.jpg, sample5.jpg, sample6.jpg