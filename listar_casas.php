<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';
include 'csrf.php';

$stmt = $pdo->query("SELECT * FROM casas");
$casas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Listar Casas - Imobiliária</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'menu.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4">
                <h1>Casas Cadastradas</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Endereço</th>
                            <th>WhatsApp</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($casas as $casa): ?>
                            <tr>
                                <td><?php echo $casa['id']; ?></td>
                                <td><?php echo htmlspecialchars($casa['nome']); ?></td>
                                <td><?php echo htmlspecialchars($casa['descricao']); ?></td>
                                <td><?php echo htmlspecialchars($casa['endereco']); ?></td>
                                <td><?php echo htmlspecialchars($casa['whatsapp']); ?></td>
                                <td>
                                    <a href="editar_casa.php?id=<?php echo $casa['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="excluir_casa.php?id=<?php echo $casa['id']; ?>" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $casa['id']; ?>">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>

    <?php foreach ($casas as $casa): ?>
    <div class="modal fade" id="deleteModal<?php echo $casa['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="excluir_casa.php">
                    <?php echo csrfInput(); ?>
                    <input type="hidden" name="id" value="<?php echo $casa['id']; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir a casa <strong><?php echo htmlspecialchars($casa['nome']); ?></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <footer class="mt-5">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Imobiliária. Programado por Silas Rosário.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
