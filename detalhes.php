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
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($casa['nome']); ?> - Detalhes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1><?php echo htmlspecialchars($casa['nome']); ?></h1>
    </header>
    <div class="container mt-4">
        <div id="carousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($imagens as $index => $imagem): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <img src="uploads/<?php echo htmlspecialchars($imagem['foto']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($casa['nome']); ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Próximo</span>
            </button>
        </div>
        <div class="mt-4 row">
            <div class="col-md-8">
                <h5>Descrição</h5>
                <p><?php echo htmlspecialchars($casa['descricao']); ?></p>
                <h5>Endereço</h5>
                <p><?php echo htmlspecialchars($casa['endereco']); ?></p>
            </div>
            <div class="col-md-4 text-end">
                <h5>Contato</h5>
                <a href="https://wa.me/<?php echo htmlspecialchars($casa['whatsapp']); ?>" class="btn btn-success">Contato via WhatsApp</a>
            </div>
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
