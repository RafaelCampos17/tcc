<?php 
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        // Excluir empréstimos do aluno primeiro
        $stmtEmprestimos = $pdo->prepare("DELETE FROM emprestimo WHERE aluno_id = :id");
        $stmtEmprestimos->bindValue(':id', $id, PDO::PARAM_INT);
        $stmtEmprestimos->execute();

        // Depois excluir o aluno
        $stmtAluno = $pdo->prepare("DELETE FROM aluno WHERE id = :id");
        $stmtAluno->bindValue(':id', $id, PDO::PARAM_INT);
        $stmtAluno->execute();
        
    } catch (PDOException $e) {
        die("Erro ao remover aluno: " . $e->getMessage());
    }
}

header("Location: ver_alunos.php");
exit;
