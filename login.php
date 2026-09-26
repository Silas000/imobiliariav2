<?php
session_start();
include 'db.php';
include 'csrf.php';

// Verifica se há usuários cadastrados (para mostrar link de primeiro acesso)
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
<head>
    <meta charset="UTF-8">
    <title>Login - Imobiliária</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card" style="width: 20rem;">
        <div class="card-body">
            <h5 class="card-title">Login do Administrador</h5>
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
                <button type="submit" class="btn btn-primary">Entrar</button>
            </form>
            <?php if (isset($error)) echo "<div class='alert alert-danger mt-3'>" . htmlspecialchars($error) . "</div>"; ?>
            <?php if ($total_usuarios == 0): ?>
                <div class="alert alert-info mt-3">
                    <small>Nenhum usuário cadastrado. <a href="cadastrar_usuario.php">Criar primeiro administrador</a></small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
