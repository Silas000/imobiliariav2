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

                // Verifica se a imagem tem extensão permitida
                if (!in_array($imageFileType, $allowed_types)) {
                    $error = "Formato não permitido para $foto. Use JPG, JPEG, PNG ou GIF.";
                    continue;
                }

                // Verifica conteúdo real da imagem
                $check = getimagesize($fotos['tmp_name'][$key]);
                if ($check === false) {
                    $error = "O arquivo $foto não é uma imagem válida.";
                    continue;
                }

                // Verifica o tamanho do arquivo (limite 5MB)
                if ($fotos['size'][$key] > 5000000) {
                    $error = "O arquivo $foto é muito grande. Limite de 5MB.";
                    continue;
                }

                // Verifica se há múltiplas extensões (tentativa de bypass)
                $name_without_ext = preg_replace('/\.' . $imageFileType . '$/', '', strtolower($foto));
                $parts = explode('.', $name_without_ext);
                if (count($parts) > 1 && in_array(end($parts), $allowed_types)) {
                    $error = "Nome do arquivo $foto contém múltiplas extensões.";
                    continue;
                }

                // Gera um nome único para a imagem
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
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Casa - Imobiliária</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'menu.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4">
                <h1>Cadastrar Casa</h1>
                <form method="POST" enctype="multipart/form-data">
                    <?php echo csrfInput(); ?>
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome da Casa</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" class="form-control" name="endereco" required>
                    </div>
                    <div class="mb-3">
                        <label for="whatsapp" class="form-label">WhatsApp</label>
                        <input type="text" class="form-control" name="whatsapp" required>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Fotos da Casa</label>
                        <input type="file" class="form-control" name="foto[]" accept="image/*" multiple required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </form>
                <?php if (isset($successMessage)) echo "<div class='alert alert-success mt-3'>$successMessage</div>"; ?>
                <?php if (isset($error)) echo "<div class='alert alert-danger mt-3'>" . htmlspecialchars($error) . "</div>"; ?>
            </main>
        </div>
    </div>
</body>
</html>
