<?php
// Cek sesi
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: /prespos/index.php");
    exit();
}

require_once '../config/database.php';
require_once '../functions/product_functions.php';

$db = getDB();

$pageTitle = 'Inventori';
include '../includes/header.php';

// Proses penghapusan produk jika ada
if (isset($_POST['delete_product'])) {
    $id_to_delete = $_POST['delete_product'];
    if (deleteProduct($db, $id_to_delete)) {
        $_SESSION['success_message'] = "Produk berhasil dihapus.";
    } else {
        $_SESSION['error_message'] = "Gagal menghapus produk.";
    }
    // Redirect untuk menghindari pengiriman ulang form
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Ambil semua produk
$products = getAllProducts($db);
?>

<div class="row mb-3">
    <div class="col">
        <a href="add_item.php" class="btn btn-primary">Tambah Produk Baru</a>
    </div>
</div>

<?php
// Tampilkan pesan sukses atau error jika ada
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}
?>

<div class="card">
    <div class="card-header">
        Daftar Produk
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['id_produk']; ?></td>
                    <td><?php echo htmlspecialchars($product['nama_produk']); ?></td>
                    <td>Rp <?php echo number_format($product['harga_produk'], 0, ',', '.'); ?></td>
                    <td><?php echo $product['stok_produk']; ?></td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $product['id_produk']; ?>" class="btn btn-sm btn-warning" style="background-color: #122D4F; border-color: #122D4F; color: white;">Edit</a>
                        <form method="POST" action="" style="display: inline;">
                            <input type="hidden" name="delete_product" value="<?php echo $product['id_produk']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
