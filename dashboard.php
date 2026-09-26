<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<?php $page_title = 'Dashboard - Imobiliária'; include 'head.php'; ?>
<body>
    <div class="container-fluid">
        <div class="row admin-layout">
            <?php include 'menu.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin']); ?>!</h1>
                </div>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <p class="mb-0">Use o menu à esquerda para gerenciar usuários e casas.</p>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-house fs-1 text-primary mb-2"></i>
                                <h5 class="card-title">Casas Cadastradas</h5>
                                <?php
                                include 'db.php';
                                $stmt = $pdo->query("SELECT COUNT(*) FROM casas");
                                $total_casas = $stmt->fetchColumn();
                                ?>
                                <p class="display-5 fw-bold text-primary"><?php echo $total_casas; ?></p>
                                <a href="listar_casas.php" class="btn btn-outline-primary btn-sm">Ver todas</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-person-lines-fill fs-1 text-primary mb-2"></i>
                                <h5 class="card-title">Usuários Cadastrados</h5>
                                <?php
                                $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
                                $total_usuarios = $stmt->fetchColumn();
                                ?>
                                <p class="display-5 fw-bold text-primary"><?php echo $total_usuarios; ?></p>
                                <a href="listar_usuarios.php" class="btn btn-outline-primary btn-sm">Ver todos</a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
