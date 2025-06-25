<?php
require '../config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID do professor não fornecido.");
}

$stmt = $pdo->prepare("SELECT * FROM professor WHERE id = ?");
$stmt->execute([$id]);
$professor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$professor) {
    die("Professor não encontrado.");
}

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $email = $_POST['email'] ?? '';

    $stmt = $pdo->prepare("UPDATE professor SET nome = ?, cpf = ?, email = ? WHERE id = ?");
    $stmt->execute([$nome, $cpf, $email, $id]);

    $success = true;

    $professor['nome'] = $nome;
    $professor['cpf'] = $cpf;
    $professor['email'] = $email;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Editar Professor</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="editar_professor.css" />
    <script>
        function cpfMask(input) {
            let v = input.value.replace(/\D/g, '');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            input.value = v;
        }
    </script>
</head>
<body>
    <main class="form-container" role="main">
        <h1>Editar Professor</h1>

        <?php if ($success): ?>
            <p class="success-message">Alterações salvas com sucesso!</p>
        <?php endif; ?>

        <form method="post" novalidate>
            <label for="nome">Nome:</label>
            <input
                type="text"
                id="nome"
                name="nome"
                value="<?= htmlspecialchars($professor['nome']) ?>"
                placeholder="Digite o nome completo"
                required
                autocomplete="name"
                autofocus
            >

            <label for="cpf">CPF:</label>
            <input
                type="text"
                id="cpf"
                name="cpf"
                value="<?= htmlspecialchars($professor['cpf']) ?>"
                placeholder="000.000.000-00"
                required
                maxlength="14"
                oninput="cpfMask(this)"
                autocomplete="off"
            >

            <label for="email">Email:</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($professor['email']) ?>"
                placeholder="email@exemplo.com"
                required
                autocomplete="email"
            >

            <button type="submit" aria-label="Salvar alterações">Salvar Alterações</button>
        </form>

        <a href="ver_professores.php" class="voltar" aria-label="Voltar para a lista de professores">Voltar</a>
    </main>
</body>
</html>