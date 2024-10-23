-- Membuat database
CREATE DATABASE IF NOT EXISTS db_pos;

-- Menggunakan database
USE db_pos;

-- Membuat tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Membuat tabel tb_produk
CREATE TABLE IF NOT EXISTS tb_produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(100) NOT NULL,
    harga_produk DECIMAL(10, 2) NOT NULL,
    stok_produk INT NOT NULL
);

-- Membuat tabel tb_history
CREATE TABLE IF NOT EXISTS tb_history (
    id_transaction INT AUTO_INCREMENT PRIMARY KEY,
    total_price DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    date DATETIME NOT NULL
);