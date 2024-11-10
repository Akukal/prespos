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
<body class="bg-gray-100">
    <header class="bg-indigo-600 shadow-lg">
        <nav class="mx-4 sm:mx-10 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/prespos/index.php" class="flex-shrink-0 flex items-center text-white font-bold text-xl">
                        POS
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="hidden md:ml-6 md:flex md:space-x-1 items-center whitespace-nowrap">
                            <a href="/prespos/pages/dashboard.php" 
                               class="<?php echo isActive('dashboard.php'); ?> text-white flex items-center hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">
                                Dashboard
                            </a>
                            <a href="/prespos/pages/inventory.php"
                               class="<?php echo isActive('inventory.php'); ?> text-white flex items-center hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">
                                Inventory
                            </a>
                            <a href="/prespos/pages/add_item.php"
                               class="<?php echo isActive('add_item.php'); ?> text-white flex items-center hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">
                                Add Item
                            </a>
                            <a href="/prespos/pages/payment.php"
                               class="<?php echo isActive('payment.php'); ?> text-white flex items-center hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">
                                Payment
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="flex items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span class="text-white mr-4 whitespace-nowrap">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <a href="/prespos/logout.php" 
                           class="text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="/prespos/index.php"
                           class="text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">
                            Login
                        </a>
                    <?php endif; ?>
                </div>
                <div class="flex md:hidden">
                    <button id="menu-button" class="text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
            </div>
            <div id="mobile-menu" class="md:hidden hidden">
                <div class="flex flex-col space-y-1 mt-2">
                    <a href="/prespos/pages/dashboard.php" 
                       class="<?php echo isActive('dashboard.php'); ?> text-white flex items-center hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">
                        Dashboard
                    </a>
                    <a href="/prespos/pages/inventory.php"
                       class="<?php echo isActive('inventory.php'); ?> text-white flex items-center hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">
                        Inventory
                    </a>
                    <a href="/prespos/pages/add_item.php"
                       class="<?php echo isActive('add_item.php'); ?> text-white flex items-center hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">
                        Add Item
                    </a>
                    <a href="/prespos/pages/payment.php"
                       class="<?php echo isActive('payment.php'); ?> text-white flex items-center hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">
                        Payment
                    </a>
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