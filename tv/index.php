<?php
require_once __DIR__ . '/auth.php';

if (!empty($_SESSION['user_id'])) {
    redirect('dashboard.php');
}

$title = 'Login';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $message = 'Preencha o usuário e a senha.';
    } else {
        $user = find_user_by_username($username);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            redirect('dashboard.php');
        }
        $message = 'Usuário ou senha inválidos.';
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
    <div class="actions">
        <button type="submit" class="button">Entrar</button>
        <a href="register.php" class="button" style="background:#4a90e2;">Registrar</a>
    </div>
</form>
    </div>
</div>
</body>
</html>
