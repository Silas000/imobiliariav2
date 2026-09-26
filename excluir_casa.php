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
        $id = intval($_POST['id']);
        $stmt = $pdo->prepare("DELETE FROM casas WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
header('Location: listar_casas.php');
exit;
?>
