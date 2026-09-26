<?php
session_start();
include 'db.php';
include 'csrf.php';

$stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
$total_usuarios = $stmt->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Token de segurança inválido. Tente novamente.";
    } else {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            $error = "Usuário e senha são obrigatórios.";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($password, $usuario['password'])) {
                session_regenerate_id(true);
                $_SESSION['admin'] = $usuario['username'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Usuário ou senha inválidos.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<?php $page_title = 'Login - Imobiliária'; include 'head.php'; ?>
<body class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <h2>Login do Administrador</h2>
            <p>Acesse o painel de controle da imobiliária</p>
        </div>
        <div class="login-body">
            <form method="POST">
                <?php echo csrfInput(); ?>
                <div class="mb-3">
                    <label for="username" class="form-label">Usuário</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <button type="submit" class="btn login-btn">Entrar</button>
            </form>
            <?php if (isset($error)) echo "<div class='alert alert-danger mt-3'>" . htmlspecialchars($error) . "</div>"; ?>
            <?php if ($total_usuarios == 0): ?>
                <div class="first-user-link">
                    <small>Nenhum usuário cadastrado. <a href="cadastrar_usuario.php">Criar primeiro administrador</a></small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
