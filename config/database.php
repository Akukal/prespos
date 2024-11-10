<?php
function getDB() {
    $config = [
        'host' => 'sql100.infinityfree.com',
        'dbname' => 'if0_37685285_db_pos',
        'username' => 'if0_37685285',
        'password' => 'ttgJN61cIV'
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