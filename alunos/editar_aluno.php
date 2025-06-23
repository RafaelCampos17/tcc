<?php
require '../config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID do aluno não fornecido.");
}

// Buscar aluno existente
$stmt = $pdo->prepare("SELECT * FROM aluno WHERE id = ?");
$stmt->execute([$id]);
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$aluno) {
    die("Aluno não encontrado.");
}

// Atualizar se enviado por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $serie = $_POST['serie'] ?? '';
    $email = $_POST['email'] ?? '';

    $stmt = $pdo->prepare("UPDATE aluno SET nome = ?, serie = ?, email = ? WHERE id = ?");
    $stmt->execute([$nome, $serie, $email, $id]);

    header("Location: ver_alunos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Aluno</title>
    <link rel="stylesheet" href="editar_aluno.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

</head>
<body>
    <div class="container">
        <h1>Editar Aluno</h1>
        <form method="post">
            <label>Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($aluno['nome']) ?>" required>

            <label>Série:</label>
            <input type="text" name="serie" value="<?= htmlspecialchars($aluno['serie']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($aluno['email']) ?>" required>

            <button type="submit">Salvar Alterações</button>
        </form>
        <a href="ver_alunos.php">Voltar</a>
    </div>
</body>

