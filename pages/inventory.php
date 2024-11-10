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

    <div class="mx-4 sm:mx-10 px-4 sm:px-6 lg:px-8 mb-10">
    <a href="add_item.php" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        Tambah Produk Baru
    </a>
</div>

<?php
// Tampilkan pesan sukses atau error jika ada
if (isset($_SESSION['success_message'])) {
    echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}
?>

<div class="mx-4 sm:mx-10 px-4 sm:px-6 lg:px-8 mb-10">
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Daftar Produk</h3>
    </div>
    <div class="px-4 py-5 sm:p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $product['id_produk']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($product['nama_produk']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp <?php echo number_format($product['harga_produk'], 0, ',', '.'); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $product['stok_produk']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="edit_product.php?id=<?php echo $product['id_produk']; ?>" class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 mr-2">Edit</a>
                            <form method="POST" action="" class="inline">
                                <input type="hidden" name="delete_product" value="<?php echo $product['id_produk']; ?>">
                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php include '../includes/footer.php'; ?>
