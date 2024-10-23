<?php
require_once '../config/database.php';
require_once '../functions/product_functions.php';
require_once '../functions/transaction_functions.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /pos/index.php");
    exit();
}

$db = getDB();

$pageTitle = 'Pembayaran';
include_once '../includes/header.php';

$products = getAllProducts($db);
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $items = json_decode($_POST['items'], true);
    $total_price = $_POST['total_price'];
    $payment_method = $_POST['payment_method'];

    if (empty($items)) {
        $message = '<div class="alert alert-danger">Keranjang belanja kosong.</div>';
    } else {
        $transaction_id = createTransaction($db, $items, $total_price, $payment_method);
        if ($transaction_id) {
            $message = '<div class="alert alert-success">Transaksi berhasil. ID Transaksi: ' . $transaction_id . '</div>';
        } else {
            $message = '<div class="alert alert-danger">Gagal melakukan transaksi. Silakan coba lagi.</div>';
        }
    }
}
?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Daftar Produk
            </div>
            <div class="card-body">
                <input type="text" id="search-input" class="form-control mb-3" onkeyup="searchProducts()" placeholder="Cari produk...">
                <table class="table table-striped">
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
                            <td><?php echo htmlspecialchars($product['nama_produk']); ?></td>
                            <td>Rp <?php echo number_format($product['harga_produk'], 0, ',', '.'); ?></td>
                            <td><?php echo $product['stok_produk']; ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary add-to-cart" 
                                        data-id="<?php echo $product['id_produk']; ?>"
                                        data-name="<?php echo htmlspecialchars($product['nama_produk']); ?>"
                                        data-price="<?php echo $product['harga_produk']; ?>"
                                        data-stock="<?php echo $product['stok_produk']; ?>">
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
            <div class="card-header">
                Pembayaran
            </div>
            <div class="card-body">
                <?php echo $message; ?>
                <form id="payment-form" method="POST">
                    <div id="cart-items"></div>
                    <div class="mb-3">
                        <label for="total_price" class="form-label">Total Harga</label>
                        <input type="text" class="form-control" id="total_price" name="total_price" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Metode Pembayaran</label>
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
    if (existingItem) {
        if (existingItem.quantity < stock) {
            existingItem.quantity++;
        } else {
            alert('Stok tidak mencukupi');
            return;
        }
    } else {
        cart.push({id: id, name: name, price: price, quantity: 1});
    }
    updateCart();
}

function removeFromCart(index) {
    if (cart[index].quantity > 1) {
        cart[index].quantity--;
    } else {
        cart.splice(index, 1);
    }
    updateCart();
}

document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        let id = this.getAttribute('data-id');
        let name = this.getAttribute('data-name');
        let price = parseFloat(this.getAttribute('data-price'));
        let stock = parseInt(this.getAttribute('data-stock'));
        addToCart(id, name, price, stock);
    });
});

document.getElementById('payment-form').addEventListener('submit', function(e) {
    if (cart.length === 0) {
        e.preventDefault();
        alert('Keranjang belanja kosong. Silakan tambahkan produk terlebih dahulu.');
    }
});
</script>

<?php include '../includes/footer.php'; ?>
