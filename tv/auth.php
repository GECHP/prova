<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function current_user()
{
    global $pdo;
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    $stmt = $pdo->prepare('SELECT id, username FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function require_login()
{
    if (empty($_SESSION['user_id'])) {
        redirect('index.php');
    }
}

function find_user_by_username($username)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function create_user($username, $password)
{
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
    return $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
}
