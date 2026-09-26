<?php
include 'db.php';
include 'csrf.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Imóveis Disponíveis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1>Imóveis à Venda</h1>
    </header>
    <div class="banner">
        <img src="banner.jpg" alt="Banner" class="img-fluid" style="width:100%;">
    </div>
    <div class="container mt-4">
        <div class="row">
            <?php foreach ($casas as $casa): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div id="carousel-<?php echo $casa['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php
                                $stmt_imagens = $pdo->prepare("SELECT * FROM imagens WHERE casa_id = :casa_id");
                                $stmt_imagens->execute(['casa_id' => $casa['id']]);
                                $imagens = $stmt_imagens->fetchAll(PDO::FETCH_ASSOC);
                                
                                foreach ($imagens as $index => $imagem): ?>
                                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <img src="uploads/<?php echo htmlspecialchars($imagem['foto']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($casa['nome']); ?>">
                                    </div>
                                <?php endforeach; ?>
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
                            <a href="detalhes.php?id=<?php echo $casa['id']; ?>" class="btn btn-primary">Ver Detalhes</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer class="mt-5">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Imobiliária. Programado por Silas Rosário.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
