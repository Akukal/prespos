<?php
require_once '../config/database.php';
require_once '../functions/product_functions.php';
require_once '../functions/transaction_functions.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /prespos/index.php");
    exit();
}

$db = getDB();
include_once '../includes/header.php';

$products = getAllProducts($db);
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $items = json_decode($_POST['items'], true);
    $total_price = $_POST['total_price'];
    $payment_method = $_POST['payment_method'];

    if (!empty($items)) {
        $transaction_id = createTransaction($db, $items, $total_price, $payment_method);
        $message = $transaction_id ? 
            '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">Transaksi berhasil. ID Transaksi: ' . $transaction_id . '</div>' :
            '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">Gagal melakukan transaksi. Silakan coba lagi.</div>';
    } else {
        $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">Keranjang belanja kosong.</div>';
    }
}
?>

<div class="mx-4 sm:mx-10 px-4 sm:px-6 lg:px-8 mb-10">
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Daftar Produk</h3>
        </div>
        <div class="p-6">
            <input type="text" id="search-input" class="w-full mb-4 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" onkeyup="searchProducts()" placeholder="Cari produk...">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="product-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($product['nama_produk']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp <?= number_format($product['harga_produk'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $product['stok_produk'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button class="add-to-cart inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        data-id="<?= $product['id_produk'] ?>"
                                        data-name="<?= htmlspecialchars($product['nama_produk']) ?>"
                                        data-price="<?= $product['harga_produk'] ?>"
                                        data-stock="<?= $product['stok_produk'] ?>">
                                    Tambah
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Pembayaran</h3>
        </div>
        <div class="p-6">
            <?= $message ?>
            <form id="payment-form" method="POST" class="space-y-6">
                <!-- Cart Items Section -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h4 class="text-base font-normal text-gray-900 mb-3">Keranjang Belanja</h4>
                    <div id="cart-items" class="space-y-3 max-h-60 overflow-y-auto"></div>
                </div>

                <!-- Payment Details Section -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h4 class="text-lg font-medium text-gray-900 mb-3">Detail Pembayaran</h4>
                    
                    <div class="space-y-4">
                        <!-- Total Price -->
                        <div>
                            <label for="total_price" class="block text-sm font-medium text-gray-700">Total Harga</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="text" 
                                       id="total_price" 
                                       name="total_price" 
                                       class="pl-12 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                       readonly>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                            <div class="mt-1 grid grid-cols-2 gap-3">
                                <label class="relative flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white cursor-pointer hover:border-indigo-500 [&:has(input:checked)]:border-indigo-500">
                                    <input type="radio" name="payment_method" value="Qris" class="sr-only peer" required>
                                    <span class="flex items-center">
                                        <span class="h-4 w-4 border border-gray-300 rounded-full mr-2 [&:has(input:checked)]:bg-indigo-500 [&:has(input:checked)]:border-indigo-500"></span>
                                        <span class="text-sm font-medium text-gray-900">QRIS</span>
                                    </span>
                                </label>
                                <label class="relative flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white cursor-pointer hover:border-indigo-500 [&:has(input:checked)]:border-indigo-500">
                                    <input type="radio" name="payment_method" value="Cash" class="sr-only peer">
                                    <span class="flex items-center">
                                        <span class="h-4 w-4 border border-gray-300 rounded-full mr-2 [&:has(input:checked)]:bg-indigo-500 [&:has(input:checked)]:border-indigo-500"></span>
                                        <span class="text-sm font-medium text-gray-900">Cash</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="items" name="items">
                
                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Proses Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>
</div>

<script>
let cart = [];

function updateCart() {
    let cartItemsDiv = document.getElementById('cart-items');
    let totalPrice = 0;
    cartItemsDiv.innerHTML = '';
    
    cart.forEach((item, index) => {
        let itemTotal = item.price * item.quantity;
        totalPrice += itemTotal;
        
        // Updated cart item layout
        cartItemsDiv.innerHTML += `
            <div class="flex flex-col bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-medium text-gray-900">${item.name}</span>
                    <button type="button" class="text-red-600 hover:text-red-800" onclick="removeFromCart(${index})">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <div class="flex items-center space-x-2">
                        <span>Jumlah: ${item.quantity}</span>
                        <span>x</span>
                        <span>Rp ${item.price.toLocaleString()}</span>
                    </div>
                    <span class="font-medium">Rp ${itemTotal.toLocaleString()}</span>
                </div>
            </div>
        `;
    });
    
    document.getElementById('total_price').value = totalPrice;
    document.getElementById('items').value = JSON.stringify(cart);
}

function addToCart(id, name, price, stock) {
    let existingItem = cart.find(item => item.id === id);
    if (existingItem && existingItem.quantity < stock) {
        existingItem.quantity++;
    } else if (!existingItem) {
        cart.push({id, name, price, quantity: 1});
    } else {
        alert('Stok tidak mencukupi');
        return;
    }
    updateCart();
}

function removeFromCart(index) {
    cart[index].quantity > 1 ? cart[index].quantity-- : cart.splice(index, 1);
    updateCart();
}

document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        addToCart(
            this.getAttribute('data-id'),
            this.getAttribute('data-name'),
            parseFloat(this.getAttribute('data-price')),
            parseInt(this.getAttribute('data-stock'))
        );
    });
});

document.getElementById('payment-form').addEventListener('submit', function(e) {
    if (cart.length === 0) {
        e.preventDefault();
        alert('Keranjang belanja kosong. Silakan tambahkan produk terlebih dahulu.');
    }
});

function searchProducts() {
    let input = document.getElementById('search-input');
    let filter = input.value.toUpperCase();
    let table = document.getElementById('product-table');
    let tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName('td')[0];
        if (td) {
            let txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
}
</script>

<?php include '../includes/footer.php'; ?>
