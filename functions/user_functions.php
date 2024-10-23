<?php
function loginUser($db, $username, $password) {
    $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debugging
    error_log("Attempting login for username: " . $username);
    error_log("User found: " . ($user ? "Yes" : "No"));

    if ($user) {
        error_log("Stored hashed password: " . $user['password']);
        error_log("Provided password: " . $password);
        
        if (password_verify($password, $user['password'])) {
            error_log("Password verified successfully");
            return $user;
        } else {
            error_log("Password verification failed");
        }
    }

    return false;
}

function registerUser($db, $username, $password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $db->prepare($query);
    return $stmt->execute([
        'username' => $username,
        'password' => $hashedPassword
    ]);
}

// Fungsi untuk menambahkan pengguna baru (gunakan ini untuk membuat pengguna pertama)
function addInitialUser($db, $username, $password) {
    if (!userExists($db, $username)) {
        return registerUser($db, $username, $password);
    }
    return false;
}

// Fungsi untuk memeriksa apakah pengguna sudah ada
function userExists($db, $username) {
    $query = "SELECT COUNT(*) FROM users WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->execute(['username' => $username]);
    return $stmt->fetchColumn() > 0;
}
