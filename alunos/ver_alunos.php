<?php
require '../config.php';

try {
    $sql = "SELECT id, nome, serie, email FROM aluno ORDER BY nome ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar alunos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alunos</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="cosmic_ui.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>
                <i class="fas fa-graduation-cap"></i>
                Lista de Alunos
            </h1>
            <p>Gerencie todos os alunos cadastrados no sistema</p>
        </div>

        <div class="stats-bar">
            <div class="stats-info">
                <i class="fas fa-users"></i>
                <span>Total de alunos: <strong><?= count($alunos) ?></strong></span>
            </div>
            <div class="search-container">
                <input type="text" class="search-input" id="searchInput" placeholder="Buscar aluno...">
                <i class="fas fa-search search-icon"></i>
            </div>
        </div>

        <div class="content">
            <?php if (!empty($alunos)): ?>
                <div class="table-container">
                    <table id="studentsTable">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Série</th>
                                <th>Email</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alunos as $aluno): ?>
                                <tr>
                                    <td>
                                        <div class="student-info">
                                            <div class="student-avatar">
                                                <?= strtoupper(substr($aluno['nome'], 0, 1)) ?>
                                            </div>
                                            <div class="student-details">
                                                <h4><?= htmlspecialchars($aluno['nome']) ?></h4>
                                                <div class="student-id">ID: <?= htmlspecialchars($aluno['id']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="serie-badge"><?= htmlspecialchars($aluno['serie']) ?></span>
                                    </td>
                                    <td class="email-cell"><?= htmlspecialchars($aluno['email']) ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="editar_aluno.php?id=<?= $aluno['id'] ?>" class="btn btn-edit">
                                                <i class="fas fa-edit"></i>
                                                Editar
                                            </a>
                                            <form action="remover_aluno.php" method="POST" style="display:inline;" onsubmit="return confirm('⚠️ Tem certeza que deseja remover o aluno <?= htmlspecialchars($aluno['nome']) ?>?\n\nEsta ação não pode ser desfeita!');">
                                            <input type="hidden" name="id" value="<?= $aluno['id'] ?>">
                                                <button type="submit" class="btn btn-delete">
                                                    <i class="fas fa-trash"></i>
                                                    Remover
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-user-graduate"></i>
                    <h3>Nenhum aluno cadastrado</h3>
                    <p>Comece adicionando o primeiro aluno ao sistema</p>
                </div>
            <?php endif; ?>

            <div id="noResults" class="no-results" style="display: none;">
                <i class="fas fa-search"></i>
                <h3>Nenhum resultado encontrado</h3>
                <p>Tente buscar com outros termos</p>
            </div>
        </div>

        <div class="footer-actions">
            <a href="telaaluno.html" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Voltar ao Menu
            </a>
            <a href="cadastro_aluno.php" class="btn-add">
                <i class="fas fa-plus"></i>
                Adicionar Aluno
            </a>
        </div>
    </div>

    <script>
        // Funcionalidade de busca em tempo real
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('studentsTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            const noResults = document.getElementById('noResults');
            let visibleRows = 0;

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const name = row.cells[0].textContent.toLowerCase();
                const serie = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();

                if (name.includes(searchTerm) || serie.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            }

            // Mostrar/ocultar mensagem de "nenhum resultado"
            if (visibleRows === 0 && searchTerm !== '') {
                noResults.style.display = 'block';
                table.style.display = 'none';
            } else {
                noResults.style.display = 'none';
                table.style.display = 'table';
            }
        });

        // Animação suave para os botões de ação
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.05)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Confirmação personalizada para exclusão
        document.querySelectorAll('form[action="remover_aluno.php"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const studentName = this.closest('tr').querySelector('.student-details h4').textContent;
                
                if (confirm(`⚠️ ATENÇÃO!\n\nVocê está prestes a remover o aluno:\n"${studentName}"\n\nEsta ação é IRREVERSÍVEL e todos os dados relacionados serão perdidos.\n\nTem certeza que deseja continuar?`)) {
                    this.submit();
                }
            });
        });

        // Efeito de loading ao clicar nos links
        document.querySelectorAll('a[href]').forEach(link => {
            link.addEventListener('click', function() {
                this.style.opacity = '0.8';
                this.style.transform = 'scale(0.98)';
            });
        });

        // Contador animado para o total de alunos
        function animateCounter() {
            const totalAlunos = <?= count($alunos) ?>;
            const counterElement = document.querySelector('.stats-info strong');
            let current = 0;
            const increment = totalAlunos / 30;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= totalAlunos) {
                    counterElement.textContent = totalAlunos;
                    clearInterval(timer);
                } else {
                    counterElement.textContent = Math.floor(current);
                }
            }, 50);
        }

        // Iniciar animação quando a página carregar
        window.addEventListener('load', animateCounter);

        // Atalhos de teclado
        document.addEventListener('keydown', function(e) {
            // Ctrl + F para focar na busca
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                document.getElementById('searchInput').focus();
            }
            
            // Escape para limpar a busca
            if (e.key === 'Escape') {
                const searchInput = document.getElementById('searchInput');
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
            }
        });

        // Tooltip para botões
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                if (this.classList.contains('btn-edit')) {
                    this.title = 'Editar informações do aluno';
                } else if (this.classList.contains('btn-delete')) {
                    this.title = 'Remover aluno do sistema';
                }
            });
        });

        // Animação de entrada para as linhas da tabela
        function animateTableRows() {
            const rows = document.querySelectorAll('#studentsTable tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }

        // Iniciar animação das linhas
        window.addEventListener('load', animateTableRows);

        // Efeito hover nas linhas da tabela
        document.querySelectorAll('#studentsTable tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
                this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.1)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
                this.style.boxShadow = 'none';
            });
        });
    </script>
</body>
</html>