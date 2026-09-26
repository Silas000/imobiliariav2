<?php
session_start();
include 'db.php';
include 'csrf.php';

// Verifica se é o primeiro usuário (tabela vazia) - permite cadastro sem login
$stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
$total_usuarios = $stmt->fetchColumn();

if (!isset($_SESSION['admin'])) {
    if ($total_usuarios > 0) {
        header('Location: login.php');
        exit;
    }
}

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
            if ($stmt->rowCount() > 0) {
                $error = "Usuário já existe. Escolha outro nome de usuário.";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (username, password) VALUES (:username, :password)");
                $stmt->execute(['username' => $username, 'password' => $password_hash]);
                $success = "Usuário cadastrado com sucesso!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário - Imobiliária</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'menu.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4">
                <h1>Cadastrar Usuário</h1>
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
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </form>
                <?php if (isset($success)) echo "<div class='alert alert-success mt-3'>" . htmlspecialchars($success) . "</div>"; ?>
                <?php if (isset($error)) echo "<div class='alert alert-danger mt-3'>" . htmlspecialchars($error) . "</div>"; ?>
            </main>
        </div>
    </div>
</body>
</html>
