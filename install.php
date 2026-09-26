<?php
/*
 * Script de Instalação - Sistema Imobiliária
 * Cria o banco de dados e as tabelas necessárias.
 * Após a instalação, REMOVA ou proteja este arquivo.
 */

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'imobiliaria2';

echo "<h2>Instalação do Sistema Imobiliária</h2>";

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Criar banco de dados
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8 COLLATE utf8_general_ci");
    echo "<p>Banco de dados '<strong>$dbname</strong>' criado com sucesso.</p>";

    $pdo->exec("USE `$dbname`");

    // Tabela de usuários
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `usuarios` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ");
    echo "<p>Tabela <strong>usuarios</strong> criada com sucesso.</p>";

    // Tabela de casas
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `casas` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            descricao TEXT NOT NULL,
            endereco VARCHAR(255) NOT NULL,
            whatsapp VARCHAR(50) NOT NULL,
            preco VARCHAR(100) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ");
    echo "<p>Tabela <strong>casas</strong> criada com sucesso.</p>";

    // Tabela de imagens
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `imagens` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            casa_id INT NOT NULL,
            foto VARCHAR(255) NOT NULL,
            FOREIGN KEY (casa_id) REFERENCES casas(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ");
    echo "<p>Tabela <strong>imagens</strong> criada com sucesso.</p>";

    echo "<hr><p><strong>Instalação concluída!</strong></p>";
    echo "<p>Crie seu primeiro usuário admin em: <a href='cadastrar_usuario.php'>cadastrar_usuario.php</a></p>";
    echo "<p>Em seguida, faça login em: <a href='login.php'>login.php</a></p>";
    echo "<p><em>Remova este arquivo (install.php) após a instalação por segurança.</em></p>";

} catch (PDOException $e) {
    die("<p>Erro: " . $e->getMessage() . "</p>");
}
?>
