<?php
require '../config.php'; // Certifique-se que config.php está configurado corretamente!

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $serie = trim($_POST['serie']);
    $email = trim($_POST['email']);

    try {
        // Verifica se o e-mail já está cadastrado
        $verificaSql = "SELECT id FROM aluno WHERE email = ?";
        $verificaStmt = $pdo->prepare($verificaSql);
        $verificaStmt->execute([$email]);

        if ($verificaStmt->rowCount() > 0) {
            echo "<script>alert('Erro: Aluno já cadastrado!'); window.location.href='cadastro_aluno.php';</script>";
            exit();
        }

        // Insere os dados na tabela aluno
        $sql = "INSERT INTO aluno (nome, serie, email) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$nome, $serie, $email])) {
            echo "<script>alert('Aluno cadastrado com sucesso!'); window.location.href='telaaluno.html';</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar aluno.');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Erro na conexão: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📚 Cadastro de Aluno - Sistema Acadêmico</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

</head>
<body>
    <div class="container">
        <div class="header-icon">
            <i class="fas fa-user-graduate"></i>
        </div>
        
        <h1>Cadastro de Aluno</h1>
        <p class="subtitle">Preencha os dados para cadastrar um novo aluno no sistema</p>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="nome" name="nome" placeholder="Digite o nome completo do aluno" required>
                </div>
            </div>
        
            <div class="form-group">
                <label for="serie">Série:</label>
                <div class="input-wrapper">
                    <i class="fas fa-book input-icon"></i>
                    <input type="text" id="serie" name="serie" placeholder="Digite a série do aluno" required>
                </div>
            </div>
        
            <div class="form-group">
                <label for="email">E-mail:</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" placeholder="Digite o e-mail" required>
                </div>
            </div>
        
            <button type="submit">Cadastrar</button>
        </form>
        <a href="telaaluno.html" class="voltar">🔙 Voltar ao Menu</a>
    </div>
</body>
</html>