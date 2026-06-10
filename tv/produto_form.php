<?php
require_once __DIR__ . '/auth.php';
require_login();

$title = 'Produto';
$message = '';
$produto = [
    'nome' => '',
    'descricao' => '',
    'quantidade' => 0,
];

if (!empty($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM produtos WHERE id = ? LIMIT 1');
    $stmt->execute([$_GET['id']]);
    $produto = $stmt->fetch() ?: $produto;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $quantidade = (int) $_POST['quantidade'];

    if ($nome === '') {
        $message = 'O nome do produto é obrigatório.';
    } else {
        if (!empty($_POST['id'])) {
            $stmt = $pdo->prepare('UPDATE produtos SET nome = ?, descricao = ?, quantidade = ? WHERE id = ?');
            $stmt->execute([$nome, $descricao, $quantidade, $_POST['id']]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO produtos (nome, descricao, quantidade) VALUES (?, ?, ?)');
            $stmt->execute([$nome, $descricao, $quantidade]);
        }
        redirect('produtos.php');
    }
}

include 'header.php';
?>
<?php if ($message) : ?>
    <div class="error"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<form method="post">
    <?php if (!empty($produto['id'])) : ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($produto['id']) ?>" />
    <?php endif; ?>
    <label for="nome">Nome</label>
    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? $produto['nome']) ?>" required />
    <label for="descricao">Descrição</label>
    <textarea id="descricao" name="descricao" rows="4"><?= htmlspecialchars($_POST['descricao'] ?? $produto['descricao']) ?></textarea>
    <label for="quantidade">Quantidade</label>
    <input type="number" id="quantidade" name="quantidade" value="<?= htmlspecialchars($_POST['quantidade'] ?? $produto['quantidade']) ?>" min="0" required />
    <div class="actions">
        <button type="submit" class="button">Salvar</button>
        <a href="produtos.php" class="button" style="background:#4a90e2;">Cancelar</a>
    </div>
</form>
    </div>
</div>
</body>
</html>
