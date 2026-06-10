<?php
require_once __DIR__ . '/auth.php';
require_login();

if (empty($_GET['id'])) {
    redirect('produtos.php');
}

$stmt = $pdo->prepare('DELETE FROM produtos WHERE id = ?');
$stmt->execute([$_GET['id']]);
redirect('produtos.php');
