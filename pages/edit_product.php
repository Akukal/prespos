<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /prespos/index.php");
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
    $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">ID produk tidak valid.</div>';
} else {
    $product = getProductById($db, $id);
    if (!$product) {
        $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">Produk tidak ditemukan.</div>';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $product) {
    $nama_produk = $_POST['nama_produk'] ?? '';
    $harga_produk = $_POST['harga_produk'] ?? '';
    $stok_produk = $_POST['stok_produk'] ?? '';

    // Validasi input
    if (empty($nama_produk) || empty($harga_produk) || empty($stok_produk)) {
        $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">Semua field harus diisi.</div>';
    } elseif (!is_numeric($harga_produk) || !is_numeric($stok_produk)) {
        $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">Harga dan stok harus berupa angka.</div>';
    } else {
        // Coba update produk
        if (updateProduct($db, $id, $nama_produk, $harga_produk, $stok_produk)) {
            $_SESSION['success_message'] = "Produk berhasil diupdate.";
            header("Location: inventory.php");
            exit();
        } else {
            $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">Gagal mengupdate produk. Silakan coba lagi.</div>';
        }
    }
}
?>
<div class="mx-4 sm:mx-10 px-4 sm:px-6 lg:px-8">
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Edit Produk</h2>
            <?php echo $message; ?>
            <?php if ($product): ?>
                <form method="POST" action="" class="space-y-6">
                    <div>
                        <label for="nama_produk" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                        <input type="text" 
                               id="nama_produk" 
                               name="nama_produk" 
                               value="<?php echo htmlspecialchars($product['nama_produk']); ?>" 
                               required 
                               class="py-1 px-1 block w-full rounded-md border-2 border-solid border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="harga_produk" class="block text-sm font-medium text-gray-700">Harga Produk</label>
                        <input type="number" 
                               id="harga_produk" 
                               name="harga_produk" 
                               value="<?php echo $product['harga_produk']; ?>" 
                               required 
                               class="py-1 px-1 block w-full rounded-md border-2 border-solid border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="stok_produk" class="block text-sm font-medium text-gray-700">Stok Produk</label>
                        <input type="number" 
                               id="stok_produk" 
                               name="stok_produk" 
                               value="<?php echo $product['stok_produk']; ?>" 
                               required 
                               class="py-1 px-1 block w-full rounded-md border-2 border-solid border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Produk
                        </button>
                        <a href="inventory.php" 
                           class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Kembali ke Inventori
                        </a>
                    </div>
                </form>
            <?php else: ?>
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative">
                    Produk tidak ditemukan atau ID tidak valid.
                </div>
                <div class="mt-4">
                    <a href="inventory.php" 
                       class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Kembali ke Inventori
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>

<?php include '../includes/footer.php'; ?>
