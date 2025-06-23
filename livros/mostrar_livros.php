<?php
require '../config.php';

try {
    $sql = "SELECT id, nome_livro, nome_autor, isbn FROM livro";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar livros: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Livros</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 700px;
            width: 100%;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background: #4facfe;
            color: white;
        }
        .voltar {
            background: #f44336;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
        }
        .voltar:hover {
            background: #d32f2f;
        }
        .remover-btn {
            background: #e53935;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .remover-btn:hover {
            background: #b71c1c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Lista de Livros</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Autor</th>
                <th>ISBN</th>
                <th>Ação</th>
            </tr>
            <?php if (!empty($livros)): ?>
                <?php foreach ($livros as $livro): ?>
                    <tr>
                        <td><?= htmlspecialchars($livro['id']) ?></td>
                        <td><?= htmlspecialchars($livro['nome_livro']) ?></td>
                        <td><?= htmlspecialchars($livro['nome_autor']) ?></td>
                        <td><?= htmlspecialchars($livro['isbn']) ?></td>
                        <td>
                            <form method="POST" action="remover_livro.php" onsubmit="return confirm('Tem certeza que deseja remover este livro?');">
                                <input type="hidden" name="id" value="<?= $livro['id'] ?>">
                                <button type="submit" class="remover-btn">🗑️</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Nenhum livro cadastrado.</td>
                </tr>
            <?php endif; ?>
        </table>
        <button onclick="window.location.href='telalivros.html'" class="voltar">🔙 Voltar</button>
    </div>
</body>
</html>
