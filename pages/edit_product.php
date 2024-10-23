<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /pos/index.php");
    exit();
}

require_once '../config/database.php';
require_once '../functions/product_functions.php';

$db = getDB();

include '../includes/header.php';

$id = $_GET['id'] ?? null;
$product = null;
$message = '';

if (!$id) {
    $message = '<div class="alert alert-danger">ID produk tidak valid.</div>';
} else {
    $product = getProductById($db, $id);
    if (!$product) {
        $message = '<div class="alert alert-danger">Produk tidak ditemukan.</div>';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $product) {
    $nama_produk = $_POST['nama_produk'] ?? '';
    $harga_produk = $_POST['harga_produk'] ?? '';
    $stok_produk = $_POST['stok_produk'] ?? '';

    // Validasi input
    if (empty($nama_produk) || empty($harga_produk) || empty($stok_produk)) {
        $message = '<div class="alert alert-danger">Semua field harus diisi.</div>';
    } elseif (!is_numeric($harga_produk) || !is_numeric($stok_produk)) {
        $message = '<div class="alert alert-danger">Harga dan stok harus berupa angka.</div>';
    } else {
        // Coba update produk
        if (updateProduct($db, $id, $nama_produk, $harga_produk, $stok_produk)) {
            $_SESSION['success_message'] = "Produk berhasil diupdate.";
            header("Location: inventory.php");
            exit();
        } else {
            $message = '<div class="alert alert-danger">Gagal mengupdate produk. Silakan coba lagi.</div>';
        }
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Edit Produk
                </div>
                <div class="card-body">
                    <?php echo $message; ?>
                    <?php if ($product): ?>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="nama_produk" class="form-label" style="color: #122D4F;"><font color="#122D4F">Nama Produk</font></label>
                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" style="color: #122D4F;" value="<?php echo htmlspecialchars($product['nama_produk']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="harga_produk" class="form-label">Harga Produk</label>
                                <input type="number" class="form-control" id="harga_produk" name="harga_produk" value="<?php echo $product['harga_produk']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="stok_produk" class="form-label">Stok Produk</label>
                                <input type="number" class="form-control" id="stok_produk" name="stok_produk" value="<?php echo $product['stok_produk']; ?>" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Update Produk</button>
                                <a href="inventory.php" class="btn btn-secondary">Kembali ke Inventori</a>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info">Produk tidak ditemukan atau ID tidak valid.</div>
                        <a href="inventory.php" class="btn btn-primary">Kembali ke Inventori</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
