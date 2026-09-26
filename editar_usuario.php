<?php
session_start();
include 'db.php';
include 'csrf.php';

$stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
$total_usuarios = $stmt->fetchColumn();

if (!isset($_SESSION['admin']) && $total_usuarios > 0) {
    header('Location: login.php');
    exit;
}

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
<?php $page_title = 'Editar Usuário'; include 'head.php'; ?>
<body>
    <div class="container-fluid">
        <div class="row admin-layout">
            <?php include 'menu.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Editar Usuário</h1>
                    <a href="listar_usuarios.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
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
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
