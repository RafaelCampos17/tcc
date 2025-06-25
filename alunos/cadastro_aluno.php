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
<style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #e3f2fd, #ffffff);
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        color: #333;
    }

    .container {
        background: #fff;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px;
        box-sizing: border-box;
    }

    .header-icon {
        font-size: 50px;
        color: #007bff;
        text-align: center;
        margin-bottom: 10px;
    }

    h1 {
        text-align: center;
        margin-bottom: 10px;
        font-weight: 700;
        font-size: 26px;
    }

    .subtitle {
        text-align: center;
        margin-bottom: 30px;
        font-size: 14px;
        color: #666;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        top: 50%;
        left: 10px;
        transform: translateY(-50%);
        color: #aaa;
    }

    input[type="text"],
    input[type="email"] {
        width: 100%;
        padding: 10px 10px 10px 35px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
        transition: 0.3s;
    }

    input:focus {
        border-color: #007bff;
        outline: none;
    }

    button {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
    }

    .voltar {
        display: block;
        margin-top: 20px;
        text-align: center;
        color: #007bff;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.3s;
    }

    .voltar:hover {
        color: #0056b3;
    }

    @media (max-width: 600px) {
        .container {
            margin: 20px;
            padding: 30px 20px;
        }
    }
</style>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📚 Cadastro de Aluno - Sistema Acadêmico</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="cadastro_aluno.css">
</head>
<body>
    <div class="container">
        
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

    <script>
        // Forçar scroll habilitado
        document.addEventListener('DOMContentLoaded', function() {
            // Remove qualquer CSS que impeça o scroll
            document.documentElement.style.overflow = 'auto';
            document.documentElement.style.height = 'auto';
            document.body.style.overflow = 'auto';
            document.body.style.height = 'auto';
            document.body.style.minHeight = '100vh';
            
            // Remove position fixed de qualquer elemento
            const allElements = document.querySelectorAll('*');
            allElements.forEach(element => {
                const computedStyle = window.getComputedStyle(element);
                if (computedStyle.position === 'fixed' && element.tagName !== 'INPUT' && element.tagName !== 'BUTTON') {
                    element.style.position = 'relative';
                }
                if (computedStyle.overflow === 'hidden' && element !== document.body && element !== document.documentElement) {
                    element.style.overflow = 'visible';
                }
            });
            
            console.log('Scroll forçado habilitado');
        });
        
        // Verificar se o scroll está funcionando
        window.addEventListener('scroll', function() {
            console.log('Scroll detectado:', window.scrollY);
        });
    </script>
</body>
</html>