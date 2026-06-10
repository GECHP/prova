<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($title)) {
    $title = 'Almoxarifado';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($title) ?> | Almoxarifado</title>
    <style>
        body { margin: 0; font-family: Arial, Helvetica, sans-serif; background: #f4f7fb; color: #333; }
        nav { background: #1d4f91; padding: 12px 20px; }
        nav a { color: #fff; text-decoration: none; margin-right: 16px; font-weight: 600; }
        nav a:hover { text-decoration: underline; }
        .container { max-width: 980px; margin: 20px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); }
        .actions { margin-top: 16px; }
        .button { display: inline-block; background: #1d4f91; color: #fff; padding: 10px 16px; border-radius: 6px; text-decoration: none; transition: background .2s ease; }
        .button:hover { background: #163e6f; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 12px 10px; border-bottom: 1px solid #e6e9ef; text-align: left; }
        th { background: #f1f5fb; }
        input, select, textarea { width: 100%; padding: 10px; margin-top: 6px; border: 1px solid #cfd8e4; border-radius: 6px; box-sizing: border-box; }
        label { display: block; margin-top: 14px; font-weight: 600; }
        .error { color: #b71c1c; background: #ffebee; padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; }
        .success { color: #1b5e20; background: #e8f5e9; padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; }
        .row { display: flex; flex-wrap: wrap; gap: 16px; }
        .row > div { flex: 1; min-width: 220px; }
    </style>
</head>
<body>
<nav>
    <a href="dashboard.php">Dashboard</a>
    <?php if (!empty($_SESSION['user_id'])) : ?>
        <a href="produtos.php">Produtos</a>
        <a href="movimentacoes.php">Movimentações</a>
        <a href="logout.php">Sair</a>
    <?php else : ?>
        <a href="index.php">Login</a>
        <a href="register.php">Registrar</a>
    <?php endif; ?>
</nav>
<div class="container">
    <div class="card">
        <h1><?= htmlspecialchars($title) ?></h1>
