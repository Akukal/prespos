<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /pos/index.php");
    exit();
}

require_once '../config/database.php';
require_once '../functions/product_functions.php';

$db = getDB();

$pageTitle = 'Tambah Produk Baru';
include '../includes/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = $_POST['nama_produk'] ?? '';
    $harga_produk = $_POST['harga_produk'] ?? '';
    $stok_produk = $_POST['stok_produk'] ?? '';

    // Validasi input
    if (empty($nama_produk) || empty($harga_produk) || empty($stok_produk)) {
        $message = '<div class="alert alert-danger">Semua field harus diisi.</div>';
    } elseif (!is_numeric($harga_produk) || !is_numeric($stok_produk)) {
        $message = '<div class="alert alert-danger">Harga dan stok harus berupa angka.</div>';
    } else {
        // Coba tambahkan produk
        if (addProduct($db, $nama_produk, $harga_produk, $stok_produk)) {
            $message = '<div class="alert alert-success">Produk berhasil ditambahkan.</div>';
            // Reset form
            $nama_produk = $harga_produk = $stok_produk = '';
        } else {
            $message = '<div class="alert alert-danger">Gagal menambahkan produk. Silakan coba lagi.</div>';
        }
    }
}
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">
                Tambah Produk Baru
            </div>
            <div class="card-body">
                <?php echo $message; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="nama_produk" class="form-label" style="color: black;"><font color="#122D4F">Nama Produk</font></label>
                        <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?php echo htmlspecialchars($nama_produk ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga_produk" class="form-label" style="color: black;"><font color="#122D4F">Harga Produk</font></label>
                        <input type="number" class="form-control" id="harga_produk" name="harga_produk" value="<?php echo htmlspecialchars($harga_produk ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="stok_produk" class="form-label" style="color: black;"><font color="#122D4F">Stok Produk</font></label>
                        <input type="number" class="form-control" id="stok_produk" name="stok_produk" value="<?php echo htmlspecialchars($stok_produk ?? ''); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Produk</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>