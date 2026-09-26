<?php
include 'db.php';

$stmt = $pdo->query("SELECT * FROM casas");
$casas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<?php $page_title = 'Imóveis Disponíveis'; include 'head.php'; ?>
<body>
    <header class="bg-primary text-white text-center py-4">
        <div class="container">
            <h1 class="header-title mb-0">Imóveis à Venda</h1>
        </div>
    </header>
    <div class="container mt-4">
        <div class="row">
            <?php foreach ($casas as $casa): ?>
                <div class="col-md-4 mb-4">
                    <div class="card property-card">
                        <div id="carousel-<?php echo $casa['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php
                                $stmt_imagens = $pdo->prepare("SELECT * FROM imagens WHERE casa_id = :casa_id");
                                $stmt_imagens->execute(['casa_id' => $casa['id']]);
                                $imagens = $stmt_imagens->fetchAll(PDO::FETCH_ASSOC);

                                if (!empty($imagens)):
                                    foreach ($imagens as $index => $imagem): ?>
                                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                            <img src="uploads/<?php echo htmlspecialchars($imagem['foto']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($casa['nome']); ?>">
                                        </div>
                                    <?php endforeach;
                                else: ?>
                                    <div class="carousel-item active">
                                        <img src="uploads/placeholder.svg" class="d-block w-100" alt="Sem imagem">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?php echo $casa['id']; ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel-<?php echo $casa['id']; ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Próximo</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($casa['nome']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($casa['descricao']); ?></p>
                            <a href="detalhes.php?id=<?php echo $casa['id']; ?>" class="btn card-btn">Ver Detalhes</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if (empty($casas)): ?>
            <div class="text-center py-5">
                <i class="bi bi-house-door fs-1 text-muted mb-3"></i>
                <p class="text-muted">Nenhum imóvel cadastrado no momento.</p>
            </div>
        <?php endif; ?>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
