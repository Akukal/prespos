<?php
function loginUser($db, $username, $password) {
    $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user && password_verify($password, $user['password']) ? $user : false;
}

function registerUser($db, $username, $password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $db->prepare($query);
    return $stmt->execute(['username' => $username, 'password' => $hashedPassword]);
}

function addInitialUser($db, $username, $password) {
    return !userExists($db, $username) && registerUser($db, $username, $password);
}

function userExists($db, $username) {
    $query = "SELECT COUNT(*) FROM users WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->execute(['username' => $username]);
    return $stmt->fetchColumn() > 0;
}
