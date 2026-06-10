<?php
require_once __DIR__ . '/auth.php';
require_login();

$title = 'Painel';
include 'header.php';

$stmt = $pdo->query('SELECT COUNT(*) AS total FROM produtos');
$produtosCount = $stmt->fetchColumn();
$stmt = $pdo->query('SELECT COUNT(*) AS total FROM movimentacoes');
$movimentacoesCount = $stmt->fetchColumn();
?>
<p>Bem-vindo ao sistema de almoxarifado. Use os links acima para gerenciar produtos e movimentações.</p>
<div class="row">
    <div class="card" style="flex:1; min-width:220px;">
        <h2>Produtos</h2>
        <p>Total de produtos cadastrados: <strong><?= htmlspecialchars($produtosCount) ?></strong></p>
        <a href="produtos.php" class="button">Ver produtos</a>
    </div>
    <div class="card" style="flex:1; min-width:220px;">
        <h2>Movimentações</h2>
        <p>Total de movimentações registradas: <strong><?= htmlspecialchars($movimentacoesCount) ?></strong></p>
        <a href="movimentacoes.php" class="button">Ver movimentações</a>
    </div>
</div>
    </div>
</div>
</body>
</html>
