<?php
include 'db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$casa_id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM casas WHERE id = :id");
$stmt->execute(['id' => $casa_id]);
$casa = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt_imagens = $pdo->prepare("SELECT * FROM imagens WHERE casa_id = :casa_id");
$stmt_imagens->execute(['casa_id' => $casa_id]);
$imagens = $stmt_imagens->fetchAll(PDO::FETCH_ASSOC);

if (!$casa) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<?php $page_title = $casa['nome'] . ' - Detalhes'; include 'head.php'; ?>
<body>
    <header class="bg-primary text-white text-center py-4">
        <div class="container">
            <h1 class="header-title mb-0"><?php echo htmlspecialchars($casa['nome']); ?></h1>
        </div>
    </header>
    <div class="container mt-4">
        <div id="carousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php if (!empty($imagens)): ?>
                    <?php foreach ($imagens as $index => $imagem): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <img src="uploads/<?php echo htmlspecialchars($imagem['foto']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($casa['nome']); ?>">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carousel-item active">
                        <img src="uploads/placeholder.svg" class="d-block w-100" alt="Sem imagem">
                    </div>
                <?php endif; ?>
            </div>
            <?php if (count($imagens) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            <?php endif; ?>
        </div>
        <div class="row mt-4">
            <div class="col-md-8">
                <h5>Descrição</h5>
                <p><?php echo htmlspecialchars($casa['descricao']); ?></p>
                <h5>Endereço</h5>
                <p><?php echo htmlspecialchars($casa['endereco']); ?></p>
                <?php if (!empty($casa['preco'])): ?>
                    <h5>Preço</h5>
                    <p class="fw-bold text-primary"><?php echo htmlspecialchars($casa['preco']); ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-4 text-end">
                <h5>Contato</h5>
                <a href="https://wa.me/<?php echo preg_replace('/\D/', '', htmlspecialchars($casa['whatsapp'])); ?>" class="btn whatsapp-btn" target="_blank">
                    <i class="bi bi-whatsapp"></i> Contato via WhatsApp
                </a>
                <a href="index.php" class="btn btn-outline-secondary mt-2 d-block">Voltar</a>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
