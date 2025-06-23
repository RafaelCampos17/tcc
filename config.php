<?php
// Configuração do banco de dados
$host = "localhost";
$dbname = "tcc";
$user = "root"; // Alterar se necessário
$password = ""; // Alterar se necessário

try {
    // Criando a conexão com PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
