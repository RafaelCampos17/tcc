<?php
require '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $cpf = trim($_POST['cpf']);
    $email = trim($_POST['email']);
    $senha = password_hash(trim($_POST['senha']), PASSWORD_DEFAULT); // Criptografando a senha

    try {
        // Verificando se já existe um professor com esse CPF ou e-mail
        $verificaSql = "SELECT id FROM professor WHERE cpf = ? OR email = ?";
        $verificaStmt = $pdo->prepare($verificaSql);
        $verificaStmt->execute([$cpf, $email]);
        
        if ($verificaStmt->rowCount() > 0) {
            echo "<script>exibirMensagem('Erro: Professor já cadastrado!', 'error');</script>";
            exit();
        }

        // Inserindo os dados na tabela professor
        $sql = "INSERT INTO professor (nome, cpf, email, senha) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$nome, $cpf, $email, $senha])) {
            echo "<script>exibirMensagem('Professor cadastrado com sucesso!', 'success'); window.location.href='telaprof.html';</script>";
        } else {
            echo "<script>exibirMensagem('Erro ao cadastrar professor.', 'error');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>exibirMensagem('Erro na conexão: " . $e->getMessage() . "', 'error');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Professor</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="caprof.css">

</head>
<body>
    <div class="container">
        <h1>👨‍🏫 Cadastro de Professor</h1>
        <div id="mensagem" class="mensagem"></div>
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
            const cpf = document.getElementById('cpf').value;
            if (cpf.length !== 11 || isNaN(cpf)) {
                exibirMensagem('CPF inválido! Deve conter 11 números.', 'error');
                return false;
            }
            return true;
        }

        function exibirMensagem(texto, tipo) {
            const mensagem = document.getElementById('mensagem');
            mensagem.innerText = texto;
            mensagem.className = 'mensagem ' + tipo;
            mensagem.style.display = 'block';

            setTimeout(() => { mensagem.style.display = 'none'; }, 3000);
        }
    </script>
</body>
</html>
