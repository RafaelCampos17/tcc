<?php
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        $stmt = $pdo->prepare("DELETE FROM professor WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        die("Erro ao remover professor: " . $e->getMessage());
    }
}

header("Location: ver_professores.php");
exit;
