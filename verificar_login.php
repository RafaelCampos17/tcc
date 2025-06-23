<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tcc/config.php';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter dados do formulário
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    
    // Verificar se os campos estão preenchidos
    if (empty($email) || empty($senha)) {
        header('Location: index.html?error=empty');
        exit;
    }
    
    try {
        // Consultar o professor pelo email
        $sql = "SELECT id, nome, email, senha FROM professor WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        $professor = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verificar se o professor existe e a senha está correta usando password_verify
        if ($professor && password_verify($senha, $professor['senha'])) {
            // Iniciar sessão
            session_start();
            $_SESSION['professor_id'] = $professor['id'];
            $_SESSION['professor_nome'] = $professor['nome'];
            $_SESSION['professor_email'] = $professor['email'];
            
            // Redirecionar para a página principal
            header('Location: menu//menu.html');
            exit;
        } else {
            // Credenciais inválidas
            header('Location: index.html?error=invalid');
            exit;
        }
    } catch (PDOException $e) {
        // Erro no banco de dados
        error_log("Erro de login: " . $e->getMessage());
        header('Location: index.html?error=database');
        exit;
    }
}