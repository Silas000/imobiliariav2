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
        $nome = trim($_POST['nome']);
        $descricao = trim($_POST['descricao']);
        $endereco = trim($_POST['endereco']);
        $whatsapp = trim($_POST['whatsapp']);

        if (empty($nome) || empty($descricao) || empty($endereco) || empty($whatsapp)) {
            $error = "Todos os campos são obrigatórios.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO casas (nome, descricao, endereco, whatsapp) VALUES (:nome, :descricao, :endereco, :whatsapp)");
            $stmt->execute([
                'nome' => $nome,
                'descricao' => $descricao,
                'endereco' => $endereco,
                'whatsapp' => $whatsapp
            ]);

            $casa_id = $pdo->lastInsertId();

            // Processar o upload das imagens
            $fotos = $_FILES['foto'];
            $success = [];

            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

            foreach ($fotos['name'] as $key => $foto) {
                $imageFileType = strtolower(pathinfo($foto, PATHINFO_EXTENSION));

                if (!in_array($imageFileType, $allowed_types)) {
                    $error = "Formato não permitido para $foto. Use JPG, JPEG, PNG ou GIF.";
                    continue;
                }

                $check = getimagesize($fotos['tmp_name'][$key]);
                if ($check === false) {
                    $error = "O arquivo $foto não é uma imagem válida.";
                    continue;
                }

                if ($fotos['size'][$key] > 5000000) {
                    $error = "O arquivo $foto é muito grande. Limite de 5MB.";
                    continue;
                }

                // Verifica double extension (tentativa de bypass)
                $name_without_ext = preg_replace('/\.' . $imageFileType . '$/', '', strtolower($foto));
                $parts = explode('.', $name_without_ext);
                if (count($parts) > 1 && in_array(end($parts), $allowed_types)) {
                    $error = "Nome do arquivo $foto contém múltiplas extensões.";
                    continue;
                }

                $unique_name = uniqid('img_', true) . '.' . $imageFileType;
                $target_file = 'uploads/' . $unique_name;

                if (move_uploaded_file($fotos['tmp_name'][$key], $target_file)) {
                    $stmt = $pdo->prepare("INSERT INTO imagens (casa_id, foto) VALUES (:casa_id, :foto)");
                    $stmt->execute(['casa_id' => $casa_id, 'foto' => $unique_name]);
                    $success[] = "Arquivo $foto enviado com sucesso!";
                } else {
                    $error = "Erro ao enviar o arquivo $foto.";
                }
            }

            if (!empty($success)) {
                $successMessage = implode("<br>", $success);
            } elseif (empty($error) && empty($success)) {
                $successMessage = "Nenhum arquivo foi enviado.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<?php $page_title = 'Cadastrar Casa - Imobiliária'; include 'head.php'; ?>
<body>
    <div class="container-fluid">
        <div class="row admin-layout">
            <?php include 'menu.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Cadastrar Casa</h1>
                    <a href="listar_casas.php" class="btn btn-outline-secondary btn-sm">Ver Casas</a>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <?php echo csrfInput(); ?>
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome do Imóvel</label>
                                <input type="text" class="form-control" name="nome" required>
                            </div>
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" name="descricao" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="endereco" class="form-label">Endereço</label>
                                <input type="text" class="form-control" name="endereco" required>
                            </div>
                            <div class="mb-3">
                                <label for="whatsapp" class="form-label">WhatsApp</label>
                                <input type="text" class="form-control" name="whatsapp" id="whatsapp" required>
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="form-label">Fotos do Imóvel</label>
                                <input type="file" class="form-control" name="foto[]" accept="image/*" multiple required>
                                <small class="text-muted">Até 5MB por imagem. Formatos: JPG, JPEG, PNG, GIF</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                        </form>
                        <?php if (isset($successMessage)) echo "<div class='alert alert-success mt-3'>$successMessage</div>"; ?>
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
                    } else if (v.length <= 6) {
                        e.target.value = '(' + v.substring(0, 2) + ') ' + v.substring(2);
                    } else if (v.length <= 10) {
                        e.target.value = '(' + v.substring(0, 2) + ') ' + v.substring(2, 6) + '-' + v.substring(6);
                    } else {
                        e.target.value = '(' + v.substring(0, 2) + ') ' + v.substring(2, 7) + '-' + v.substring(7, 11);
                    }
                }
            });
        }
    </script>
</body>
</html>
