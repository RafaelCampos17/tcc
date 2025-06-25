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
    <title>Galácticos | Professores</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="ver_galaticos_professores.css">
</head>
<body>
    <div class="menu-container">
        <header class="header">
            <div class="logo">
                <i class="fas fa-chalkboard-teacher"></i>
                <h1>Lista de Professores</h1>
            </div>
            <div class="btn-container">
                <a href="cadastro_professor.php" class="btn">
                    <i class="fas fa-plus"></i> Novo Professor
                </a>
            </div>
        </header>

        <main>
            <?php if (!empty($professores)): ?>
                <div class="table-wrapper">
                    <table class="galactic-table">
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
                                        <a href="editar_professor.php?id=<?= $professor['id'] ?>" class="btn btn-edit" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="post" action="remover_professor.php" onsubmit="return confirm('Tem certeza que deseja remover este professor?');" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $professor['id'] ?>">
                                            <button type="submit" class="btn btn-delete" title="Remover">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-message">
                    <i class="fas fa-info-circle"></i> Nenhum professor cadastrado.
                </div>
            <?php endif; ?>
        </main>

        <footer>
            <div class="btn-container">
                <a href="telaprof.html" class="btn voltar">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </footer>
    </div>
</body>
</html>