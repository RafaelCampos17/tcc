<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tcc/config.php';  // Ajuste o caminho conforme necessário

// Recupera alunos com sala, livros e professores do banco de dados
$alunos = $pdo->query("SELECT id, nome, serie FROM aluno ORDER BY serie, nome")->fetchAll(PDO::FETCH_ASSOC);
$professores = $pdo->query("SELECT id, nome FROM professor ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$livros = $pdo->query("SELECT id, nome_livro FROM livro ORDER BY nome_livro")->fetchAll(PDO::FETCH_ASSOC);

// Verifique se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os dados do formulário
    $aluno_id = $_POST['aluno_id'];
    $livro_id = $_POST['livro_id'];
    $professor_id = $_POST['professor_id'];
    $data_retirada = $_POST['data_retirada'];
    $data_devolucao = $_POST['data_devolucao'];

    try {
        // Inserir o empréstimo na tabela de empréstimos
        $sql = "INSERT INTO emprestimo (aluno_id, livro_id, professor_id, data_retirada, data_devolucao) 
                VALUES (:aluno_id, :livro_id, :professor_id, :data_retirada, :data_devolucao)";
        $stmt = $pdo->prepare($sql);
        
        // Vincula os parâmetros ao SQL
        $stmt->bindParam(':aluno_id', $aluno_id, PDO::PARAM_INT);
        $stmt->bindParam(':livro_id', $livro_id, PDO::PARAM_INT);
        $stmt->bindParam(':professor_id', $professor_id, PDO::PARAM_INT);
        $stmt->bindParam(':data_retirada', $data_retirada);
        $stmt->bindParam(':data_devolucao', $data_devolucao);

        // Executa a consulta
        $stmt->execute();

        // Mensagem de sucesso
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('Empréstimo registrado com sucesso!', 'success');
            });
        </script>";

    } catch (PDOException $e) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('Erro ao registrar o empréstimo: " . $e->getMessage() . "', 'error');
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Empréstimo</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4facfe;
            --secondary-color: #00f2fe;
            --success-color: #4caf50;
            --danger-color: #f44336;
            --warning-color: #ff9800;
            --dark-color: #333;
            --light-gray: #f8f9fa;
            --border-color: #e0e0e0;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 8px 25px rgba(0, 0, 0, 0.15);
            --border-radius: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            max-width: 700px;
            width: 100%;
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        h1 {
            text-align: center;
            color: var(--dark-color);
            margin-bottom: 30px;
            font-weight: 700;
            font-size: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        label {
            display: block;
            font-size: 16px;
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Estilos para o select customizado */
        .custom-select {
            position: relative;
            width: 100%;
        }

        .select-input {
            width: 100%;
            padding: 15px 50px 15px 20px;
            font-size: 16px;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Roboto', sans-serif;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .select-input:focus, .select-input.active {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
        }

        .select-arrow {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dark-color);
            transition: transform 0.3s ease;
            pointer-events: none;
        }

        .select-input.active .select-arrow {
            transform: translateY(-50%) rotate(180deg);
        }

        .select-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid var(--primary-color);
            border-top: none;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .select-dropdown.show {
            display: block;
        }

        .select-option {
            padding: 12px 20px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .select-option:hover {
            background-color: var(--light-gray);
        }

        .select-option.selected {
            background-color: var(--primary-color);
            color: white;
        }

        .student-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .student-name {
            font-weight: 500;
        }

        .student-class {
            background: var(--warning-color);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .search-input {
            width: 100%;
            padding: 10px 15px;
            border: none;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
            outline: none;
            background: var(--light-gray);
        }

        .search-input:focus {
            border-bottom-color: var(--primary-color);
        }

        /* Select normal para outros campos */
        select, input[type="date"] {
            width: 100%;
            padding: 15px 20px;
            font-size: 16px;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            background: white;
            transition: all 0.3s ease;
            font-family: 'Roboto', sans-serif;
        }

        select:focus, input[type="date"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
            transform: translateY(-2px);
        }

        select:hover, input[type="date"]:hover {
            border-color: var(--primary-color);
        }

        .btn {
            width: 100%;
            padding: 16px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--success-color) 0%, #45a049 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            background: linear-gradient(135deg, #45a049 0%, var(--success-color) 100%);
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--danger-color) 0%, #d32f2f 100%);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            background: linear-gradient(135deg, #d32f2f 0%, var(--danger-color) 100%);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .icon {
            color: var(--primary-color);
        }

        .required::after {
            content: " *";
            color: var(--danger-color);
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: var(--border-radius);
            color: white;
            font-weight: 500;
            z-index: 9999;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            background: var(--success-color);
        }

        .notification.error {
            background: var(--danger-color);
        }

        /* Animações */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                padding: 25px;
                margin: 10px;
            }

            h1 {
                font-size: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .select-input, select, input[type="date"] {
                padding: 12px 15px;
                font-size: 14px;
            }

            .btn {
                padding: 14px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-book-reader icon"></i>Registrar Empréstimo</h1>
        
        <form action="" method="POST" id="emprestimoForm">
            <div class="form-group">
                <label for="aluno_id" class="required">
                    <i class="fas fa-user-graduate icon"></i>
                    Aluno:
                </label>
                <div class="custom-select">
                    <div class="select-input" id="alunoSelect">
                        <span id="selectedStudent">Selecione o aluno</span>
                        <i class="fas fa-chevron-down select-arrow"></i>
                    </div>
                    <div class="select-dropdown" id="alunoDropdown">
                        <input type="text" class="search-input" placeholder="Pesquisar aluno..." id="searchStudent">
                        <div id="studentOptions">
                            <?php foreach ($alunos as $aluno): ?>
                                <div class="select-option" data-value="<?= $aluno['id'] ?>">
                                    <div class="student-info">
                                        <span class="student-name"><?= htmlspecialchars($aluno['nome']) ?></span>
                                        <span class="student-class"><?= htmlspecialchars($aluno['serie']) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <input type="hidden" name="aluno_id" id="aluno_id" required>
                </div>
            </div>

            <div class="form-group">
                <label for="livro_id" class="required">
                    <i class="fas fa-book icon"></i>
                    Livro:
                </label>
                <select id="livro_id" name="livro_id" required>
                    <option value="">Selecione o livro</option>
                    <?php foreach ($livros as $livro): ?>
                        <option value="<?= $livro['id'] ?>"><?= htmlspecialchars($livro['nome_livro']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="professor_id" class="required">
                    <i class="fas fa-chalkboard-teacher icon"></i>
                    Professor:
                </label>
                <select id="professor_id" name="professor_id" required>
                    <option value="">Selecione o professor</option>
                    <?php foreach ($professores as $professor): ?>
                        <option value="<?= $professor['id'] ?>"><?= htmlspecialchars($professor['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="data_retirada" class="required">
                        <i class="fas fa-calendar-plus icon"></i>
                        Data de Retirada:
                    </label>
                    <input type="date" id="data_retirada" name="data_retirada" required>
                </div>

                <div class="form-group">
                    <label for="data_devolucao" class="required">
                        <i class="fas fa-calendar-check icon"></i>
                        Data de Devolução:
                    </label>
                    <input type="date" id="data_devolucao" name="data_devolucao" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Registrar Empréstimo
            </button>
        </form>
        
        <button onclick="window.location.href='tela_emp.html'" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Voltar
        </button>
    </div>

    <script>
        // Função para mostrar notificações
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                ${message}
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => notification.classList.add('show'), 100);
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => document.body.removeChild(notification), 300);
            }, 3000);
        }

        // Custom Select para Alunos
        document.addEventListener('DOMContentLoaded', function() {
            const alunoSelect = document.getElementById('alunoSelect');
            const alunoDropdown = document.getElementById('alunoDropdown');
            const selectedStudent = document.getElementById('selectedStudent');
            const alunoIdInput = document.getElementById('aluno_id');
            const searchInput = document.getElementById('searchStudent');
            const studentOptions = document.getElementById('studentOptions');
            const allOptions = studentOptions.querySelectorAll('.select-option');

            // Toggle dropdown
            alunoSelect.addEventListener('click', function() {
                alunoSelect.classList.toggle('active');
                alunoDropdown.classList.toggle('show');
                if (alunoDropdown.classList.contains('show')) {
                    searchInput.focus();
                }
            });

            // Fechar dropdown ao clicar fora
            document.addEventListener('click', function(e) {
                if (!alunoSelect.contains(e.target) && !alunoDropdown.contains(e.target)) {
                    alunoSelect.classList.remove('active');
                    alunoDropdown.classList.remove('show');
                }
            });

            // Selecionar opção
            allOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.dataset.value;
                    const studentName = this.querySelector('.student-name').textContent;
                    const studentClass = this.querySelector('.student-class').textContent;
                    
                    selectedStudent.innerHTML = `
                        <div class="student-info">
                            <span class="student-name">${studentName}</span>
                            <span class="student-class">${studentClass}</span>
                        </div>
                    `;
                    
                    alunoIdInput.value = value;
                    
                    // Remove seleção anterior
                    allOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    
                    alunoSelect.classList.remove('active');
                    alunoDropdown.classList.remove('show');
                });
            });

            // Pesquisa de alunos
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                allOptions.forEach(option => {
                    const studentName = option.querySelector('.student-name').textContent.toLowerCase();
                    const studentClass = option.querySelector('.student-class').textContent.toLowerCase();
                    
                    if (studentName.includes(searchTerm) || studentClass.includes(searchTerm)) {
                        option.style.display = 'flex';
                    } else {
                        option.style.display = 'none';
                    }
                });
            });

            // Limpar pesquisa quando abrir dropdown
            alunoSelect.addEventListener('click', function() {
                searchInput.value = '';
                allOptions.forEach(option => {
                    option.style.display = 'flex';
                });
            });

            // Definir data mínima como hoje
            const hoje = new Date().toISOString().split('T')[0];
            document.getElementById('data_retirada').setAttribute('min', hoje);
            document.getElementById('data_devolucao').setAttribute('min', hoje);
            
            // Quando a data de retirada mudar, ajustar a data mínima de devolução
            document.getElementById('data_retirada').addEventListener('change', function() {
                const dataRetirada = this.value;
                document.getElementById('data_devolucao').setAttribute('min', dataRetirada);
                
                // Se a data de devolução for anterior à de retirada, limpar
                const dataDevolucao = document.getElementById('data_devolucao').value;
                if (dataDevolucao && dataDevolucao < dataRetirada) {
                    document.getElementById('data_devolucao').value = '';
                }
            });

            // Validação do formulário
            document.getElementById('emprestimoForm').addEventListener('submit', function(e) {
                const alunoId = document.getElementById('aluno_id').value;
                const livroId = document.getElementById('livro_id').value;
                const professorId = document.getElementById('professor_id').value;
                const dataRetirada = document.getElementById('data_retirada').value;
                const dataDevolucao = document.getElementById('data_devolucao').value;

                if (!alunoId) {
                    e.preventDefault();
                    showNotification('Por favor, selecione um aluno!', 'error');
                    return;
                }

                if (!livroId) {
                    e.preventDefault();
                    showNotification('Por favor, selecione um livro!', 'error');
                    return;
                }

                if (!professorId) {
                    e.preventDefault();
                    showNotification('Por favor, selecione um professor!', 'error');
                    return;
                }

                if (!dataRetirada || !dataDevolucao) {
                    e.preventDefault();
                    showNotification('Por favor, preencha as datas!', 'error');
                    return;
                }

                if (dataDevolucao < dataRetirada) {
                    e.preventDefault();
                    showNotification('A data de devolução não pode ser anterior à data de retirada!', 'error');
                    return;
                }

                // Se chegou até aqui, o formulário é válido
                showNotification('Processando empréstimo...', 'success');
            });

            // Navegação por teclado no dropdown
            searchInput.addEventListener('keydown', function(e) {
                const visibleOptions = Array.from(allOptions).filter(option => 
                    option.style.display !== 'none'
                );
                
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    const currentSelected = document.querySelector('.select-option.highlighted');
                    let nextIndex = 0;
                    
                    if (currentSelected) {
                        currentSelected.classList.remove('highlighted');
                        const currentIndex = visibleOptions.indexOf(currentSelected);
                        nextIndex = (currentIndex + 1) % visibleOptions.length;
                    }
                    
                    visibleOptions[nextIndex].classList.add('highlighted');
                    visibleOptions[nextIndex].scrollIntoView({ block: 'nearest' });
                }
                
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    const currentSelected = document.querySelector('.select-option.highlighted');
                    let prevIndex = visibleOptions.length - 1;
                    
                    if (currentSelected) {
                        currentSelected.classList.remove('highlighted');
                        const currentIndex = visibleOptions.indexOf(currentSelected);
                        prevIndex = currentIndex > 0 ? currentIndex - 1 : visibleOptions.length - 1;
                    }
                    
                    visibleOptions[prevIndex].classList.add('highlighted');
                    visibleOptions[prevIndex].scrollIntoView({ block: 'nearest' });
                }
                
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const highlighted = document.querySelector('.select-option.highlighted');
                    if (highlighted) {
                        highlighted.click();
                    }
                }
                
                if (e.key === 'Escape') {
                    alunoSelect.classList.remove('active');
                    alunoDropdown.classList.remove('show');
                }
            });

            // Remover highlight quando mouse entra em uma opção
            allOptions.forEach(option => {
                option.addEventListener('mouseenter', function() {
                    allOptions.forEach(opt => opt.classList.remove('highlighted'));
                });
            });
        });
    </script>
</body>
</html>

