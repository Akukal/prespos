<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /pos/index.php");
    exit();
}

require_once '../config/database.php';
require_once '../functions/product_functions.php';
require_once '../functions/transaction_functions.php';

$db = getDB();

// Mengambil data untuk dashboard
$totalProducts = getTotalProducts($db);
$lowStockProducts = getLowStockProducts($db);
$dailyIncome = getDailyIncome($db);
$dailyTransactions = getDailyTransactionCount($db);
$recentTransactions = getAllTransactions($db); // Kita akan membatasi jumlah yang ditampilkan di view

include_once '../includes/header.php';
?>
<div class="mx-4 sm:mx-10 px-4 sm:px-6 lg:px-8 mb-10">
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-indigo-900 text-white rounded-lg shadow-lg p-6">
        <h5 class="text-lg font-semibold mb-2">Total Produk</h5>
        <p class="text-3xl font-bold"><?php echo $totalProducts; ?></p>
    </div>
    
    <div class="bg-indigo-900 text-white rounded-lg shadow-lg p-6">
        <h5 class="text-lg font-semibold mb-2">Stok Rendah</h5>
        <p class="text-3xl font-bold"><?php echo count($lowStockProducts); ?></p>
    </div>
    
    <div class="bg-indigo-900 text-white rounded-lg shadow-lg p-6 md:col-span-1">
        <h5 class="text-lg font-semibold mb-2">Pemasukan Hari Ini</h5>
        <p class="text-3xl font-bold">Rp <?php echo number_format($dailyIncome, 0, ',', '.'); ?></p>
    </div>
    
    <div class="bg-indigo-900 text-white rounded-lg shadow-lg p-6">
        <h5 class="text-lg font-semibold mb-2">Transaksi Hari Ini</h5>
        <p class="text-3xl font-bold"><?php echo $dailyTransactions; ?></p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-1">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="bg-white px-6 py-4 rounded-t-lg border-b">
                <h3 class="text-lg font-semibold">Produk dengan Stok Rendah</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto max-h-[250px] overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-yellow-500 text-white sticky top-0">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Nama Produk</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Stok</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($lowStockProducts as $product): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($product['nama_produk']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $product['stok_produk']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="bg-white px-6 py-4 rounded-t-lg border-b">
                <h3 class="text-lg font-semibold">Riwayat Transaksi</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto max-h-[250px] overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-yellow-500 text-white sticky top-0">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold">ID Transaksi</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Total Harga</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Metode Pembayaran</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($recentTransactions as $transaction): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $transaction['id_transaction']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp <?php echo number_format($transaction['total_price'], 0, ',', '.'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $transaction['payment_method']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo date('d/m/Y H:i', strtotime($transaction['date'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
</div>

<?php include '../includes/footer.php'; ?>