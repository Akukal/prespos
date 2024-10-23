<?php
function getDB() {
    $config = [
        'host' => 'localhost',
        'dbname' => 'db_pos',
        'username' => 'root',
        'password' => ''
    ];

    try {
        $db = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4", $config['username'], $config['password']);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $db;
    } catch(PDOException $e) {
        die("Koneksi database gagal: " . $e->getMessage());
    }
}
