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

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white .bg-orange">
            <div class="card-body">
                <h5 class="card-title">Total Produk</h5>
                <p class="card-text display-5"><?php echo $totalProducts; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Produk Stok Rendah</h5>
                <p class="card-text display-5"><?php echo count($lowStockProducts); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Pemasukan Hari Ini</h5>
                <p class="card-text display-5">Rp <?php echo number_format($dailyIncome, 0, ',', '.'); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Transaksi Hari Ini</h5>
                <p class="card-text display-5"><?php echo $dailyTransactions; ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                Produk dengan Stok Rendah
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped rounded overflow-hidden">
                        <thead class="bg-warning text-white">
                            <tr>
                                <th class="rounded-top-left-4">Nama Produk</th>
                                <th class="rounded-top-right-4">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lowStockProducts as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['nama_produk']); ?></td>
                                <td><?php echo $product['stok_produk']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                Riwayat Transaksi
            </div>
            <div class="card-body">
                <table class="table table-striped rounded overflow-hidden">
                    <thead class="bg-warning text-white">
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Total Harga</th>
                            <th>Metode Pembayaran</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($recentTransactions, 0, 5) as $transaction): ?>
                        <tr>
                            <td><?php echo $transaction['id_transaction']; ?></td>
                            <td>Rp <?php echo number_format($transaction['total_price'], 0, ',', '.'); ?></td>
                            <td><?php echo $transaction['payment_method']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($transaction['date'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>