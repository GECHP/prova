<?php
require_once __DIR__ . '/auth.php';
require_login();

$title = 'Produtos';
include 'header.php';

$produtos = $pdo->query('SELECT * FROM produtos ORDER BY id DESC')->fetchAll();
?>
<a href="produto_form.php" class="button">Adicionar produto</a>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Quantidade</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produtos as $produto) : ?>
            <tr>
                <td><?= htmlspecialchars($produto['id']) ?></td>
                <td><?= htmlspecialchars($produto['nome']) ?></td>
                <td><?= htmlspecialchars($produto['quantidade']) ?></td>
                <td><?= nl2br(htmlspecialchars($produto['descricao'])) ?></td>
                <td>
                    <a href="produto_form.php?id=<?= urlencode($produto['id']) ?>">Editar</a>
                    |
                    <a href="produto_delete.php?id=<?= urlencode($produto['id']) ?>" onclick="return confirm('Excluir este produto?');">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    </div>
</div>
</body>
</html>
