<?php
require '../config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID do aluno não fornecido.");
}

$stmt = $pdo->prepare("SELECT * FROM aluno WHERE id = ?");
$stmt->execute([$id]);
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$aluno) {
    die("Aluno não encontrado.");
}

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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="edit_aluno.css"> <!-- CSS Premium já está pronto -->
</head>
<body>
    <div class="container">
        <h1>Editar Aluno</h1>
        <form method="post">
            <label for="nome">Nome do Aluno</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($aluno['nome']) ?>" required placeholder="Digite o nome completo">

            <label for="serie">Série</label>
            <input type="text" id="serie" name="serie" value="<?= htmlspecialchars($aluno['serie']) ?>" required placeholder="Ex: 3º Ano B">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($aluno['email']) ?>" required placeholder="email@exemplo.com">

            <button type="submit">Salvar Alterações</button>
        </form>

        <a href="ver_alunos.php">Voltar</a>
    </div>
</body>
</html>