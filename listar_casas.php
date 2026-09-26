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
<?php $page_title = 'Listar Casas - Imobiliária'; include 'head.php'; ?>
<body>
    <div class="container-fluid">
        <div class="row admin-layout">
            <?php include 'menu.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Casas Cadastradas</h1>
                    <a href="cadastrar_casa.php" class="btn btn-primary btn-sm">Nova Casa</a>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <?php if (!empty($casas)): ?>
                            <table class="table table-hover mb-0">
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
                                                <a href="editar_casa.php?id=<?php echo $casa['id']; ?>" class="btn btn-edit btn-sm">Editar</a>
                                                <a href="#" class="btn btn-delete btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $casa['id']; ?>">
                                                    <i class="bi bi-trash"></i> Excluir
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted mb-0">Nenhuma casa cadastrada. <a href="cadastrar_casa.php">Cadastre uma agora</a>.</p>
                        <?php endif; ?>
                    </div>
                </div>
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
                        <button type="submit" class="btn btn-delete">Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <?php include 'footer.php'; ?>
</body>
</html>
