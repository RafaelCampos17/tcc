<?php
require '../config.php';

try {
    $sql = "SELECT id, nome, cpf, email FROM professor";
    $stmt = $pdo->query($sql);
    $professores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Professores</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="ver_professore.css">
</head>
<body>
    <div class="container">
        <div class="header-actions">
            <h1><i class="fas fa-chalkboard-teacher"></i> Lista de Professores</h1>
            <a href="cadastro_professor.php" class="add-professor">
                <i class="fas fa-plus icon"></i> Novo Professor
            </a>
        </div>
        
        <?php if (!empty($professores)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($professores as $professor): ?>
                        <tr>
                            <td><?= htmlspecialchars($professor['id']) ?></td>
                            <td><?= htmlspecialchars($professor['nome']) ?></td>
                            <td><?= htmlspecialchars($professor['cpf']) ?></td>
                            <td><?= htmlspecialchars($professor['email']) ?></td>
                            <td class="actions">
                                <a href="editar_professor.php?id=<?= $professor['id'] ?>" class="btn btn-edit">
                                    <i class="fas fa-edit icon"></i> Editar
                                </a>
                                <form method="post" action="remover_professor.php" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja remover este professor?');">
                                    <input type="hidden" name="id" value="<?= $professor['id'] ?>">
                                    <button type="submit" class="btn btn-delete">
                                        <i class="fas fa-trash-alt icon"></i> Remover
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-message">
                <i class="fas fa-info-circle"></i> Nenhum professor cadastrado.
            </div>
        <?php endif; ?>
        
        <button onclick="window.location.href='telaprof.html'" class="btn btn-back">
            <i class="fas fa-arrow-left icon"></i> Voltar
        </button>
    </div>
</body>
</html>
