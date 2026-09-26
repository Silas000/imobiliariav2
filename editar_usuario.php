<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';
include 'csrf.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Token de segurança inválido. Tente novamente.";
    } else {
        $id = intval($_POST['id']);
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username)) {
            $error = "Usuário é obrigatório.";
        } else {
            if (!empty($password)) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE usuarios SET username = :username, password = :password WHERE id = :id");
                $stmt->execute(['username' => $username, 'password' => $password_hash, 'id' => $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE usuarios SET username = :username WHERE id = :id");
                $stmt->execute(['username' => $username, 'id' => $id]);
            }
            $success = "Usuário atualizado com sucesso!";
        }
    }
} else {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'menu.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4">
                <h1>Editar Usuário</h1>
                <form method="POST">
                    <?php echo csrfInput(); ?>
                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuário</label>
                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($usuario['username']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control" name="password" placeholder="Deixe em branco para manter a senha atual">
                    </div>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </form>
                <?php if (isset($success)) echo "<div class='alert alert-success mt-3'>" . htmlspecialchars($success) . "</div>"; ?>
                <?php if (isset($error)) echo "<div class='alert alert-danger mt-3'>" . htmlspecialchars($error) . "</div>"; ?>
            </main>
        </div>
    </div>
</body>
</html>
