<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once '../config.php';

$data = json_decode(file_get_contents("php://input"), true);

// Verifica se os campos obrigatórios existem
if (!isset($data['nome_livro'], $data['nome_autor'], $data['isbn'])) {
    http_response_code(400);
    echo "❌ Dados incompletos.";
    exit;
}

$nome_livro = $data['nome_livro'];
$nome_autor = $data['nome_autor'];
$isbn = $data['isbn'];

try {
    $stmt = $pdo->prepare("INSERT INTO livro (nome_livro, nome_autor, isbn) VALUES (:nome_livro, :nome_autor, :isbn)");
    $stmt->bindParam(':nome_livro', $nome_livro);
    $stmt->bindParam(':nome_autor', $nome_autor);
    $stmt->bindParam(':isbn', $isbn);
    $stmt->execute();

    echo "✅ Livro salvo com sucesso!";
} catch (PDOException $e) {
    http_response_code(500);
    echo "❌ Erro ao salvar o livro: " . $e->getMessage();
}
?>
