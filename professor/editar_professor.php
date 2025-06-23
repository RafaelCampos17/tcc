<?php
require '../config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID do professor não fornecido.");
}

// Buscar professor existente
$stmt = $pdo->prepare("SELECT * FROM professor WHERE id = ?");
$stmt->execute([$id]);
$professor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$professor) {
    die("Professor não encontrado.");
}

// Atualizar se enviado por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $email = $_POST['email'] ?? '';

    $stmt = $pdo->prepare("UPDATE professor SET nome = ?, cpf = ?, email = ? WHERE id = ?");
    $stmt->execute([$nome, $cpf, $email, $id]);

    header("Location: ver_professores.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Professor</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #36d1dc, #5b86e5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: #fff;
            padding: 25px 35px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4caf50;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #388e3c;
        }
        .voltar {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #555;
            text-decoration: none;
        }
        .voltar:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Editar Professor</h1>
        <form method="post">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($professor['nome']) ?>" required>

            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" value="<?= htmlspecialchars($professor['cpf']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($professor['email']) ?>" required>

            <button type="submit">Salvar Alterações</button>
        </form>
        <a class="voltar" href="ver_professores.php">← Voltar</a>
    </div>
</body>
</html>
