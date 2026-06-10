<?php
require_once __DIR__ . '/auth.php';
require_login();

$title = 'Movimentações';
include 'header.php';

$movimentacoes = $pdo->query(
    'SELECT m.*, p.nome AS produto_nome FROM movimentacoes m
     JOIN produtos p ON p.id = m.produto_id
     ORDER BY m.data DESC, m.id DESC'
)->fetchAll();
?>
<a href="movimentacao_form.php" class="button">Adicionar movimentação</a>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Produto</th>
            <th>Tipo</th>
            <th>Quantidade</th>
            <th>Data</th>
            <th>Descrição</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($movimentacoes as $mov) : ?>
            <tr>
                <td><?= htmlspecialchars($mov['id']) ?></td>
                <td><?= htmlspecialchars($mov['produto_nome']) ?></td>
                <td><?= htmlspecialchars($mov['tipo']) ?></td>
                <td><?= htmlspecialchars($mov['quantidade']) ?></td>
                <td><?= htmlspecialchars($mov['data']) ?></td>
                <td><?= nl2br(htmlspecialchars($mov['descricao'])) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    </div>
</div>
</body>
</html>
