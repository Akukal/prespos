<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/functions/user_functions.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek apakah file ada
if (!file_exists(__DIR__ . '/functions/user_functions.php')) {
    die('File user_functions.php tidak ditemukan');
}

// Cek isi file
$content = file_get_contents(__DIR__ . '/functions/user_functions.php');
if (strpos($content, 'function loginUser') === false) {
    die('Fungsi loginUser tidak ditemukan di user_functions.php');
}

// Jika pengguna sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: pages/dashboard.php");
    exit();
}

$db = getDB();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi.";
    } else {
        $user = loginUser($db, $username, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: pages/dashboard.php");
            exit();
        } else {
            $error = "Username atau password salah.";
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Login
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
