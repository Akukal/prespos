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

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = $_POST['nama_produk'] ?? '';
    $harga_produk = $_POST['harga_produk'] ?? '';
    $stok_produk = $_POST['stok_produk'] ?? '';

    // Validasi input
    if (empty($nama_produk) || empty($harga_produk) || empty($stok_produk)) {
        $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">Semua field harus diisi.</div>';
    } elseif (!is_numeric($harga_produk) || !is_numeric($stok_produk)) {
        $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">Harga dan stok harus berupa angka.</div>';
    } else {
        // Coba tambahkan produk
        if (addProduct($db, $nama_produk, $harga_produk, $stok_produk)) {
            $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">Produk berhasil ditambahkan.</div>';
            // Reset form
            $nama_produk = $harga_produk = $stok_produk = '';
        } else {
            $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">Gagal menambahkan produk. Silakan coba lagi.</div>';
        }
    }
}
?>

<div class="mx-4 sm:mx-10 px-4 sm:px-6 lg:px-8 mb-10">
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Tambah Produk Baru</h2>
            <?php echo $message; ?>
            <form method="POST" action="" class="space-y-6">
                <div>
                    <label for="nama_produk" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" 
                           id="nama_produk" 
                           name="nama_produk" 
                           value="<?php echo htmlspecialchars($nama_produk ?? ''); ?>" 
                           required 
                           class="py-1 px-1 block w-full rounded-md border-2 border-solid border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="harga_produk" class="block text-sm font-medium text-gray-700">Harga Produk</label>
                    <input type="number" 
                           id="harga_produk" 
                           name="harga_produk" 
                           value="<?php echo htmlspecialchars($harga_produk ?? ''); ?>" 
                           required 
                           class="py-1 px-1 block w-full rounded-md border-2 border-solid border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="stok_produk" class="block text-sm font-medium text-gray-700">Stok Produk</label>
                    <input type="number" 
                           id="stok_produk" 
                           name="stok_produk" 
                           value="<?php echo htmlspecialchars($stok_produk ?? ''); ?>" 
                           required 
                           class="py-1 px-1 block w-full rounded-md border-2 border-solid border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Tambah Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>