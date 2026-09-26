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
        $nome = trim($_POST['nome']);
        $descricao = trim($_POST['descricao']);
        $endereco = trim($_POST['endereco']);
        $preco = trim($_POST['preco']);

        $stmt = $pdo->prepare("UPDATE casas SET nome = :nome, descricao = :descricao, endereco = :endereco, preco = :preco WHERE id = :id");
        $stmt->execute([
            'nome' => $nome,
            'descricao' => $descricao,
            'endereco' => $endereco,
            'preco' => $preco,
            'id' => $id
        ]);
        $success = "Casa atualizada com sucesso!";
    }
} else {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM casas WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $casa = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Casa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'menu.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4">
                <h1>Editar Casa</h1>
                <form method="POST">
                    <?php echo csrfInput(); ?>
                    <input type="hidden" name="id" value="<?php echo $casa['id']; ?>">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($casa['nome']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" required><?php echo htmlspecialchars($casa['descricao']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" class="form-control" name="endereco" value="<?php echo htmlspecialchars($casa['endereco']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="preco" class="form-label">Preço</label>
                        <input type="text" class="form-control" name="preco" value="<?php echo htmlspecialchars($casa['preco']); ?>" required>
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
