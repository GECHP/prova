<?php
require_once __DIR__ . '/auth.php';
require_login();

$title = 'Movimentação';
$message = '';

$produtos = $pdo->query('SELECT id, nome, quantidade FROM produtos ORDER BY nome')->fetchAll();
$movimentacao = [
    'produto_id' => '',
    'tipo' => 'entrada',
    'quantidade' => 1,
    'descricao' => '',
    'data' => date('Y-m-d'),
];

$editing = !empty($_GET['id']);
$oldMovement = null;

if ($editing) {
    $stmt = $pdo->prepare('SELECT * FROM movimentacoes WHERE id = ? LIMIT 1');
    $stmt->execute([$_GET['id']]);
    $oldMovement = $stmt->fetch();
    if ($oldMovement) {
        $movimentacao = $oldMovement;
    }
}

function adjust_stock($pdo, $productId, $delta)
{
    $stmt = $pdo->prepare('SELECT quantidade FROM produtos WHERE id = ? LIMIT 1');
    $stmt->execute([$productId]);
    $current = $stmt->fetchColumn();
    if ($current === false) {
        return false;
    }
    $newQuantity = $current + $delta;
    if ($newQuantity < 0) {
        return false;
    }
    $stmt = $pdo->prepare('UPDATE produtos SET quantidade = ? WHERE id = ?');
    $stmt->execute([$newQuantity, $productId]);
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto_id = (int) ($_POST['produto_id'] ?? 0);
    $tipo = $_POST['tipo'] === 'saida' ? 'saida' : 'entrada';
    $quantidade = max(1, (int) ($_POST['quantidade'] ?? 1));
    $descricao = trim($_POST['descricao'] ?? '');
    $data = $_POST['data'] ?: date('Y-m-d');

    if ($produto_id <= 0) {
        $message = 'Selecione um produto.';
    } else {
        $adjust = $tipo === 'entrada' ? $quantidade : -$quantidade;
        $pdo->beginTransaction();
        try {
            if ($editing && $oldMovement) {
                $oldAdjust = $oldMovement['tipo'] === 'entrada' ? $oldMovement['quantidade'] : -$oldMovement['quantidade'];
                if ($produto_id === $oldMovement['produto_id']) {
                    $delta = $adjust - $oldAdjust;
                    if ($delta !== 0 && !adjust_stock($pdo, $produto_id, $delta)) {
                        throw new Exception('Estoque insuficiente para atualizar a movimentação.');
                    }
                } else {
                    if (!adjust_stock($pdo, $oldMovement['produto_id'], -$oldAdjust)) {
                        throw new Exception('Falha ao ajustar o estoque do produto original.');
                    }
                    if (!adjust_stock($pdo, $produto_id, $adjust)) {
                        throw new Exception('Estoque insuficiente para o produto selecionado.');
                    }
                }
                $stmt = $pdo->prepare('UPDATE movimentacoes SET produto_id = ?, tipo = ?, quantidade = ?, descricao = ?, data = ? WHERE id = ?');
                $stmt->execute([$produto_id, $tipo, $quantidade, $descricao, $data, $oldMovement['id']]);
            } else {
                if (!adjust_stock($pdo, $produto_id, $adjust)) {
                    throw new Exception('Estoque insuficiente para esta movimentação.');
                }
                $stmt = $pdo->prepare('INSERT INTO movimentacoes (produto_id, tipo, quantidade, descricao, data) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$produto_id, $tipo, $quantidade, $descricao, $data]);
            }
            $pdo->commit();
            redirect('movimentacoes.php');
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = $e->getMessage();
        }
    }
}

include 'header.php';
?>
<?php if ($message) : ?>
    <div class="error"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<form method="post">
    <?php if ($editing) : ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($oldMovement['id']) ?>" />
    <?php endif; ?>
    <label for="produto_id">Produto</label>
    <select id="produto_id" name="produto_id" required>
        <option value="">Selecione um produto</option>
        <?php foreach ($produtos as $produto) : ?>
            <option value="<?= htmlspecialchars($produto['id']) ?>" <?= ($produto['id'] == ($_POST['produto_id'] ?? $movimentacao['produto_id'])) ? 'selected' : '' ?> >
                <?= htmlspecialchars($produto['nome']) ?> (estoque: <?= htmlspecialchars($produto['quantidade']) ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <label for="tipo">Tipo</label>
    <select id="tipo" name="tipo">
        <option value="entrada" <?= ($_POST['tipo'] ?? $movimentacao['tipo']) === 'entrada' ? 'selected' : '' ?>>Entrada</option>
        <option value="saida" <?= ($_POST['tipo'] ?? $movimentacao['tipo']) === 'saida' ? 'selected' : '' ?>>Saída</option>
    </select>
    <label for="quantidade">Quantidade</label>
    <input type="number" id="quantidade" name="quantidade" min="1" value="<?= htmlspecialchars($_POST['quantidade'] ?? $movimentacao['quantidade']) ?>" required />
    <label for="data">Data</label>
    <input type="date" id="data" name="data" value="<?= htmlspecialchars($_POST['data'] ?? $movimentacao['data']) ?>" required />
    <label for="descricao">Descrição</label>
    <textarea id="descricao" name="descricao" rows="4"><?= htmlspecialchars($_POST['descricao'] ?? $movimentacao['descricao']) ?></textarea>
    <div class="actions">
        <button type="submit" class="button"><?= $editing ? 'Atualizar' : 'Registrar' ?></button>
        <a href="movimentacoes.php" class="button" style="background:#4a90e2;">Cancelar</a>
    </div>
</form>
    </div>
</div>
</body>
</html>
