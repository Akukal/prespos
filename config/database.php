<?php
function getDB() {
    $config = [
        'host' => 'localhost',
        'dbname' => 'db_pos',
        'username' => 'root',
        'password' => ''
    ];

    $conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['dbname']);

    if (!$conn) {
        echo "Koneksi database gagal"();
    } else {
        echo "Koneksi database berhasil";
    }
}
