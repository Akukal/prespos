<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/functions/user_functions.php';

session_start();

// Redirect jika user sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: pages/dashboard.php");
    exit();
}

$db = getDB();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $message = '<div class="alert alert-danger">Semua field harus diisi.</div>';
    } elseif ($password !== $confirm_password) {
        $message = '<div class="alert alert-danger">Password tidak cocok.</div>';
    } elseif (strlen($password) < 1) {
        $message = '<div class="alert alert-danger">Password harus minimal 1 karakter.</div>';
    } elseif (userExists($db, $username)) {
        $message = '<div class="alert alert-danger">Username sudah digunakan.</div>';
    } else {
        if (registerUser($db, $username, $password)) {
            $message = '<div class="alert alert-success">Akun berhasil dibuat. Silakan <a href="index.php">login</a>.</div>';
        } else {
            $message = '<div class="alert alert-danger">Gagal membuat akun. Silakan coba lagi.</div>';
        }
    }
}

$pageTitle = 'Register';
include __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Register
                </div>
                <div class="card-body">
                    <?php echo $message; ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label" style="color: black;">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label" style="color: black;">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label" style="color: black;">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    Sudah punya akun? <a href="index.php">Login di sini</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
