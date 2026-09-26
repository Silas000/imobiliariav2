<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Imobiliária</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'menu.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4">
                <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin']); ?></h1>
                <p>Use o menu à esquerda para gerenciar usuários e casas.</p>
            </main>
        </div>
    </div>

    <footer class="mt-5">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Imobiliária. Programado por Silas Rosário.</p>
        </div>
    </footer>
</body>
</html>
