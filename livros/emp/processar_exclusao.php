<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tcc/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['emprestimo_id'])) {
        echo json_encode(['success' => false, 'message' => 'ID do empréstimo não fornecido']);
        exit;
    }
    
    $emprestimo_id = intval($data['emprestimo_id']);
    
    try {
        // Excluir o empréstimo
        $sql = "DELETE FROM emprestimo WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$emprestimo_id])) {
            echo json_encode(['success' => true, 'message' => 'Empréstimo excluído com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao excluir empréstimo']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}
?>
