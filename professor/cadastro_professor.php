<?php
require '../config.php';

$mensagem = "";
$mensagemTipo = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $cpf = trim($_POST['cpf']);
    $email = trim($_POST['email']);
    $senha = password_hash(trim($_POST['senha']), PASSWORD_DEFAULT);

    try {
        $verificaSql = "SELECT id FROM professor WHERE cpf = ? OR email = ?";
        $verificaStmt = $pdo->prepare($verificaSql);
        $verificaStmt->execute([$cpf, $email]);

        if ($verificaStmt->rowCount() > 0) {
            $mensagem = "Erro: Professor já cadastrado!";
            $mensagemTipo = "error";
        } else {
            $sql = "INSERT INTO professor (nome, cpf, email, senha) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([$nome, $cpf, $email, $senha])) {
                $mensagem = "Professor cadastrado com sucesso!";
                $mensagemTipo = "success";
                echo "<script>setTimeout(() => { window.location.href = 'telaprof.html'; }, 2000);</script>";
            } else {
                $mensagem = "Erro ao cadastrar professor.";
                $mensagemTipo = "error";
            }
        }
    } catch (PDOException $e) {
        $mensagem = "Erro na conexão: " . $e->getMessage();
        $mensagemTipo = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Professor</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="cadastro_prof.css">
</head>
<body>
<div class="galaxy-bg">
  <div class="stars"></div>
  <div class="stars2"></div>
  <div class="stars3"></div>
</div>

    <div class="container">
        <h1>👨‍🏫 Cadastro de Professor</h1>

        <?php if (!empty($mensagem)): ?>
            <div id="mensagem" class="mensagem <?= $mensagemTipo ?>"><?= $mensagem ?></div>
        <?php endif; ?>

        <form method="post" action="" onsubmit="return validarFormulario()">
            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" placeholder="Digite o nome completo" required>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" placeholder="Digite o CPF (apenas números)" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" placeholder="Digite o e-mail" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" placeholder="Crie uma senha segura" required>

            <button type="submit">Cadastrar</button>
        </form>

        <button onclick="window.location.href='telaprof.html'" class="voltar">🔙 Voltar</button>
    </div>

    <script>
        function validarFormulario() {
            const cpf = document.getElementById('cpf').value.trim();
            if (!/^\d{11}$/.test(cpf)) {
                exibirMensagem('CPF inválido! Deve conter exatamente 11 números.', 'error');
                return false;
            }
            return true;
        }

        function exibirMensagem(texto, tipo) {
            const mensagem = document.getElementById('mensagem');
            mensagem.textContent = texto;
            mensagem.className = 'mensagem ' + tipo;
            mensagem.style.display = 'block';

            setTimeout(() => {
                mensagem.style.display = 'none';
            }, 4000);
        }
    </script>
</body>
</html>