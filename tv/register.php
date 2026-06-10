<?php
require_once __DIR__ . '/auth.php';

if (!empty($_SESSION['user_id'])) {
    redirect('dashboard.php');
}

$title = 'Registrar';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($username === '' || $password === '' || $password_confirm === '') {
        $message = 'Preencha todos os campos.';
    } elseif ($password !== $password_confirm) {
        $message = 'As senhas não coincidem.';
    } elseif (find_user_by_username($username)) {
        $message = 'Este usuário já está cadastrado.';
    } else {
        create_user($username, $password);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        redirect('dashboard.php');
    }
}

include 'header.php';
?>
<?php if ($message) : ?>
    <div class="error"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<form method="post">
    <label for="username">Usuário</label>
    <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required />
    <label for="password">Senha</label>
    <input type="password" id="password" name="password" required />
    <label for="password_confirm">Confirmar senha</label>
    <input type="password" id="password_confirm" name="password_confirm" required />
    <div class="actions">
        <button type="submit" class="button">Registrar</button>
        <a href="index.php" class="button" style="background:#4a90e2;">Voltar ao login</a>
    </div>
</form>
    </div>
</div>
</body>
</html>
