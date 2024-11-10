<?php
// Mulai session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fungsi untuk mengecek apakah halaman saat ini aktif
function isActive($page) {
    $currentPage = basename($_SERVER['PHP_SELF']);
    return ($currentPage == $page) ? 'bg-indigo-700' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - POS System' : 'POS System'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Ganti URL Font Awesome ke CDN yang benar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600;700&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <?php if (isset($extraStyles)): ?>
        <?php foreach ($extraStyles as $style): ?>
            <link rel="stylesheet" href="<?php echo $style; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <header class="main-header">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="/prespos/index.php">PP POS</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo isActive('dashboard.php'); ?>" href="/prespos/pages/dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo isActive('inventory.php'); ?>" href="/prespos/pages/inventory.php">Inventory</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo isActive('add_item.php'); ?>" href="/prespos/pages/add_item.php">Add Item</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo isActive('payment.php'); ?>" href="/prespos/pages/payment.php">Payment</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item">
                                <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/prespos/logout.php">Logout</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/prespos/index.php">Login</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <?php if (isset($pageTitle)): ?>
            <h1 class="text-3xl font-bold text-gray-900 mb-6"><?php echo $pageTitle; ?></h1>
        <?php endif; ?>

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
    </main>

    <script>
        // Toggle mobile menu
        const menuButton = document.getElementById('menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        menuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>