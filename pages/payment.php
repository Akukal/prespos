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
            '<div class="alert alert-success">Transaksi berhasil. ID Transaksi: ' . $transaction_id . '</div>' :
            '<div class="alert alert-danger">Gagal melakukan transaksi. Silakan coba lagi.</div>';
    } else {
        $message = '<div class="alert alert-danger">Keranjang belanja kosong.</div>';
    }
}
?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Daftar Produk</div>
            <div class="card-body">
                <input type="text" id="search-input" class="form-control mb-3" onkeyup="searchProducts()" placeholder="Cari produk...">
                <table class="table table-striped" id="product-table">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['nama_produk']) ?></td>
                            <td>Rp <?= number_format($product['harga_produk'], 0, ',', '.') ?></td>
                            <td><?= $product['stok_produk'] ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary add-to-cart" 
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
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Pembayaran</div>
            <div class="card-body">
                <?= $message ?>
                <form id="payment-form" method="POST">
                    <div id="cart-items"></div>
                    <div class="mb-3">
                        <label for="total_price" class="form-label"><font color="#122D4F">Total Harga</font></label>
                        <input type="text" class="form-control" id="total_price" name="total_price" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label"><font color="#122D4F">Metode Pembayaran</font></label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="Qris">Qris</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                    <input type="hidden" id="items" name="items">
                    <button type="submit" class="btn btn-primary">Proses Pembayaran</button>
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
        cartItemsDiv.innerHTML += `
            <div class="mb-2">
                ${item.name} x ${item.quantity} = Rp ${itemTotal.toLocaleString()}
                <button type="button" class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">Hapus</button>
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
