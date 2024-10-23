<?php
require_once __DIR__ . '/../config/database.php';

function executeQuery($db, $query, $params = []) {
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    return $stmt;
}

function getAllProducts($db) {
    return executeQuery($db, "SELECT * FROM tb_produk ORDER BY id_produk ASC")->fetchAll();
}

function getProductById($db, $id) {
    return executeQuery($db, "SELECT * FROM tb_produk WHERE id_produk = ?", [$id])->fetch();
}

function addProduct($db, $nama, $harga, $stok) {
    $query = "INSERT INTO tb_produk (nama_produk, harga_produk, stok_produk) VALUES (?, ?, ?)";
    return executeQuery($db, $query, [$nama, $harga, $stok])->rowCount() > 0;
}

function updateProduct($db, $id, $nama, $harga, $stok) {
    $query = "UPDATE tb_produk SET nama_produk = ?, harga_produk = ?, stok_produk = ? WHERE id_produk = ?";
    return executeQuery($db, $query, [$nama, $harga, $stok, $id])->rowCount() > 0;
}

function deleteProduct($db, $id) {
    return executeQuery($db, "DELETE FROM tb_produk WHERE id_produk = ?", [$id])->rowCount() > 0;
}

function updateProductStock($db, $id, $quantity) {
    $query = "UPDATE tb_produk SET stok_produk = stok_produk - ? WHERE id_produk = ?";
    return executeQuery($db, $query, [$quantity, $id])->rowCount() > 0;
}

function searchProducts($db, $keyword) {
    $query = "SELECT * FROM tb_produk WHERE nama_produk LIKE ? ORDER BY id_produk ASC";
    return executeQuery($db, $query, ["%$keyword%"])->fetchAll();
}

function getTotalProducts($db) {
    return executeQuery($db, "SELECT COUNT(*) as total FROM tb_produk")->fetch()['total'];
}

function getLowStockProducts($db, $threshold = 10) {
    $query = "SELECT * FROM tb_produk WHERE stok_produk < ? ORDER BY stok_produk ASC";
    return executeQuery($db, $query, [$threshold])->fetchAll();
}
