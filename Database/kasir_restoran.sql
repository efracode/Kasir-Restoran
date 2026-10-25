CREATE DATABASE IF NOT EXISTS kasir_restoran;
USE kasir_restoran;

CREATE TABLE menu (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kd_menu VARCHAR(20) UNIQUE NOT NULL,
    nama_menu VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    harga INT NOT NULL,
    stock INT NOT NULL,
    deskripsi TEXT
);

CREATE TABLE transaksi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    no_transaksi VARCHAR(50) UNIQUE NOT NULL,
    tanggal DATETIME NOT NULL,
    total_harga INT NOT NULL,
    total_bayar INT NOT NULL,
    kembalian INT NOT NULL,
    metode_bayar VARCHAR(30) DEFAULT 'Tunai',
    nama_kasir VARCHAR(50) DEFAULT 'Kasir 1'
);

CREATE TABLE transaksi_detail (
    id INT PRIMARY KEY AUTO_INCREMENT,
    transaksi_id INT NOT NULL,
    menu_id INT NOT NULL,
    nama_menu VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    jumlah INT NOT NULL,
    subtotal INT NOT NULL,
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE
);

INSERT INTO menu (kd_menu, nama_menu, kategori, harga, stock, deskripsi) VALUES
('MN001', 'Nasi Goreng Spesial', 'Makanan', 25000, 25, 'Nasi goreng dengan suwiran ayam, telur mata sapi, sosis, dan acar mentimun.'),
('MN002', 'Ayam Bakar Madu', 'Makanan', 28000, 20, 'Ayam bakar dengan bumbu madu legit, disajikan bersama sambal terasi dan lalapan segar.'),
('MN003', 'Mie Goreng Seafood', 'Makanan', 27000, 15, 'Mie goreng kenyal dengan udang, cumi segar, telur, dan sayuran renyah.'),
('MN004', 'Soto Ayam Lamongan', 'Makanan', 22000, 18, 'Soto ayam berkuah kuning gurih dengan taburan koya khas Lamongan.'),
('MN005', 'Es Teh Manis', 'Minuman', 6000, 50, 'Teh melati seduh dingin yang menyegarkan dahaga.'),
('MN006', 'Jus Alpukat Kocok', 'Minuman', 15000, 20, 'Jus alpukat mentega kental dengan saus cokelat manis.'),
('MN007', 'Kopi Susu Gula Aren', 'Minuman', 18000, 30, 'Espresso arabika dipadu susu segar dan manis alami gula aren.'),
('MN008', 'French Fries Crispy', 'Snack', 16000, 25, 'Kentang goreng renyah disajikan dengan saus sambal dan mayones.'),
('MN009', 'Tahu Walik Krispi', 'Snack', 14000, 20, 'Tahu goreng renyah dengan isian adonan ayam gurih dan sambal kecap pedas.'),
('MN010', 'Pisang Bakar Keju Cokelat', 'Dessert', 18000, 15, 'Pisang bakar manis dengan taburan keju cheddar parut dan susu kental cokelat.');
