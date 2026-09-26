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
        $whatsapp = trim($_POST['whatsapp']);

        $stmt = $pdo->prepare("UPDATE casas SET nome = :nome, descricao = :descricao, endereco = :endereco, preco = :preco, whatsapp = :whatsapp WHERE id = :id");
        $stmt->execute([
            'nome' => $nome,
            'descricao' => $descricao,
            'endereco' => $endereco,
            'preco' => $preco,
            'whatsapp' => $whatsapp,
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
<?php $page_title = 'Editar Casa'; include 'head.php'; ?>
<body>
    <div class="container-fluid">
        <div class="row admin-layout">
            <?php include 'menu.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Editar Casa</h1>
                    <a href="listar_casas.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="POST">
                            <?php echo csrfInput(); ?>
                            <input type="hidden" name="id" value="<?php echo $casa['id']; ?>">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($casa['nome']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" name="descricao" rows="3" required><?php echo htmlspecialchars($casa['descricao']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="endereco" class="form-label">Endereço</label>
                                <input type="text" class="form-control" name="endereco" value="<?php echo htmlspecialchars($casa['endereco']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="preco" class="form-label">Preço</label>
                                <input type="text" class="form-control" name="preco" value="<?php echo htmlspecialchars($casa['preco']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="whatsapp" class="form-label">WhatsApp</label>
                                <input type="text" class="form-control" name="whatsapp" id="whatsapp" value="<?php echo htmlspecialchars($casa['whatsapp']); ?>" required>
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
    <script>
        const whatsappInput = document.getElementById('whatsapp');
        if (whatsappInput) {
            whatsappInput.addEventListener('input', function(e) {
                let v = e.target.value.replace(/\D/g, '');
                if (v.length > 11) v = v.substring(0, 11);
                if (v.length > 0) {
                    if (v.length <= 2) {
                        e.target.value = v;
                    } else if (v.length <= 10) {
                        e.target.value = '(' + v.substring(0, 2) + ') ' + v.substring(2);
                    } else {
                        e.target.value = '(' + v.substring(0, 2) + ') ' + v.substring(2, 2) + v.substring(2, 6) + '-' + v.substring(6, 10);
                    }
                }
            });
        }
    </script>
</body>
</html>
