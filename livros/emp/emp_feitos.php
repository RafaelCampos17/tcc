<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/tcc/config.php';

// Consultar a tabela emprestimo para obter os empréstimos (usando apenas colunas existentes)
$sql = "SELECT e.id, e.data_retirada, e.data_devolucao, e.status,
               a.nome AS aluno_nome, a.serie AS aluno_serie, a.email AS aluno_email,
               l.nome_livro,
               p.nome AS professor_nome
        FROM emprestimo e
        JOIN aluno a ON e.aluno_id = a.id
        JOIN livro l ON e.livro_id = l.id
        JOIN professor p ON e.professor_id = p.id
        ORDER BY e.data_retirada DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao recuperar empréstimos: " . $e->getMessage());
}

// Função para formatar a data
function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}

// Verificar se um empréstimo está atrasado
function estaAtrasado($dataDevolucao, $status) {
    return $status !== 'Devolvido' && strtotime($dataDevolucao) < strtotime('today');
}

// Calcular dias restantes ou atraso
function calcularDiasRestantes($dataDevolucao, $status) {
    if ($status === 'Devolvido') return 'Devolvido';
    
    $hoje = strtotime('today');
    $dataLimite = strtotime($dataDevolucao);
    $diferenca = ($dataLimite - $hoje) / (60 * 60 * 24);
    
    if ($diferenca > 0) {
        return $diferenca . ' dias restantes';
    } elseif ($diferenca == 0) {
        return 'Vence hoje';
    } else {
        return abs($diferenca) . ' dias de atraso';
    }
}

// Estatísticas
$totalEmprestimos = count($emprestimos);
$emprestimosAtivos = 0;
$emprestimosAtrasados = 0;
$emprestimosDevolvidos = 0;

foreach ($emprestimos as $emprestimo) {
    if ($emprestimo['status'] === 'Devolvido') {
        $emprestimosDevolvidos++;
    } elseif (estaAtrasado($emprestimo['data_devolucao'], $emprestimo['status'])) {
        $emprestimosAtrasados++;
    } else {
        $emprestimosAtivos++;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Empréstimos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1d4ed8;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --info-color: #0891b2;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-600: #475569;
            --gray-700: #334155;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: var(--dark-color);
            line-height: 1.6;
        }
        
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .header {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color), var(--success-color));
        }
        
        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }
        
        .header p {
            color: var(--gray-600);
            font-size: 1.1rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }
        
        .stat-card.total::before { background: var(--info-color); }
        .stat-card.active::before { background: var(--success-color); }
        .stat-card.late::before { background: var(--danger-color); }
        .stat-card.returned::before { background: var(--gray-600); }
        
        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        
        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }
        
        .stat-card.total .stat-icon { background: var(--info-color); }
        .stat-card.active .stat-icon { background: var(--success-color); }
        .stat-card.late .stat-icon { background: var(--danger-color); }
        .stat-card.returned .stat-icon { background: var(--gray-600); }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
        }
        
        .stat-label {
            color: var(--gray-600);
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .controls {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            justify-content: space-between;
        }
        
        .search-container {
            position: relative;
            flex: 1;
            min-width: 300px;
            max-width: 400px;
        }
        
        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 2px solid var(--gray-200);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgb(37 99 235 / 0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-600);
        }
        
        .filter-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 0.5rem 1rem;
            border: 2px solid var(--gray-200);
            background: white;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .filter-btn:hover, .filter-btn.active {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
        }
        
        .emprestimos-container {
            background: white;
            border-radius: 1rem;
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        
        .emprestimos-grid {
            display: grid;
            gap: 1rem;
            padding: 1.5rem;
        }
        
        .emprestimo-card {
            border: 1px solid var(--gray-200);
            border-radius: 0.75rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            background: var(--light-color);
        }
        
        .emprestimo-card:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow);
            transform: translateY(-2px);
        }
        
        .emprestimo-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .emprestimo-id {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .emprestimo-status {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-ativo {
            background: rgb(34 197 94 / 0.1);
            color: var(--success-color);
        }
        
        .status-atrasado {
            background: rgb(220 38 38 / 0.1);
            color: var(--danger-color);
        }
        
        .status-devolvido {
            background: rgb(107 114 128 / 0.1);
            color: var(--gray-600);
        }
        
        .emprestimo-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .info-section h3 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: var(--gray-600);
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .info-value {
            color: var(--dark-color);
            font-size: 0.875rem;
            font-weight: 600;
            text-align: right;
        }
        
        .dias-info {
            margin-top: 1rem;
            padding: 0.75rem;
            border-radius: 0.5rem;
            text-align: center;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .dias-restantes {
            background: rgb(34 197 94 / 0.1);
            color: var(--success-color);
        }
        
        .dias-atraso {
            background: rgb(220 38 38 / 0.1);
            color: var(--danger-color);
        }
        
        .dias-devolvido {
            background: rgb(107 114 128 / 0.1);
            color: var(--gray-600);
        }
        
        .dias-vence-hoje {
            background: rgb(217 119 6 / 0.1);
            color: var(--warning-color);
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            justify-content: flex-end;
        }
        
        .btn-action {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-devolver {
            background: var(--success-color);
            color: white;
        }
        
        .btn-devolver:hover {
            background: #047857;
            transform: translateY(-1px);
        }
        
        .btn-devolver:disabled {
            background: var(--gray-300);
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-excluir {
            background: var(--danger-color);
            color: white;
        }
        
        .btn-excluir:hover {
            background: #b91c1c;
            transform: translateY(-1px);
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gray-600);
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: var(--gray-300);
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--gray-700);
        }
        
        .empty-state p {
            font-size: 1rem;
            margin-bottom: 2rem;
        }
        
        .back-button {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 3.5rem;
            height: 3.5rem;
            font-size: 1.25rem;
            cursor: pointer;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .back-button:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 2rem;
            border-radius: 1rem;
            width: 90%;
            max-width: 500px;
            box-shadow: var(--shadow-xl);
            position: relative;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .close {
            color: var(--gray-600);
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .close:hover {
            color: var(--danger-color);
        }
        
        .modal-body {
            margin-bottom: 2rem;
            color: var(--gray-700);
            line-height: 1.6;
        }
        
        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }
        
        .btn-modal {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-confirm {
            background: var(--success-color);
            color: white;
        }
        
        .btn-confirm:hover {
            background: #047857;
        }
        
        .btn-cancel {
            background: var(--gray-200);
            color: var(--gray-700);
        }
        
        .btn-cancel:hover {
            background: var(--gray-300);
        }
        
        /* Notification Styles */
        .notification {
            position: fixed;
            top: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            color: white;
            font-weight: 500;
            z-index: 1001;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
        
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            
            .header h1 {
                font-size: 2rem;
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .controls {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-container {
                min-width: auto;
                max-width: none;
            }
            
            .filter-buttons {
                justify-content: center;
            }
            
            .emprestimo-content {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .emprestimo-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .action-buttons {
                justify-content: center;
            }
            
            .back-button {
                bottom: 1rem;
                right: 1rem;
            }
            
            .modal-content {
                margin: 10% auto;
                width: 95%;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Header -->
        <div class="header">
            <h1>
                <i class="fas fa-book-reader"></i>
                Gerenciamento de Empréstimos
            </h1>
            <p>Controle completo dos empréstimos de livros da biblioteca</p>
        </div>

        <!-- Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-header">
                    <div>
                        <div class="stat-number"><?php echo $totalEmprestimos; ?></div>
                        <div class="stat-label">Total de Empréstimos</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card active">
                <div class="stat-header">
                    <div>
                        <div class="stat-number"><?php echo $emprestimosAtivos; ?></div>
                        <div class="stat-label">Empréstimos Ativos</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card late">
                <div class="stat-header">
                    <div>
                        <div class="stat-number"><?php echo $emprestimosAtrasados; ?></div>
                        <div class="stat-label">Empréstimos Atrasados</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card returned">
                <div class="stat-header">
                    <div>
                        <div class="stat-number"><?php echo $emprestimosDevolvidos; ?></div>
                        <div class="stat-label">Livros Devolvidos</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controles -->
        <div class="controls">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Pesquisar por aluno, livro ou professor...">
            </div>
            
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">Todos</button>
                <button class="filter-btn" data-filter="ativo">Ativos</button>
                <button class="filter-btn" data-filter="atrasado">Atrasados</button>
                <button class="filter-btn" data-filter="devolvido">Devolvidos</button>
            </div>
        </div>

        <!-- Lista de Empréstimos -->
        <div class="emprestimos-container">
            <?php if ($emprestimos && count($emprestimos) > 0): ?>
                <div class="emprestimos-grid" id="emprestimosGrid">
                    <?php foreach ($emprestimos as $emprestimo): ?>
                        <?php
                        $statusClass = '';
                        $diasInfo = calcularDiasRestantes($emprestimo['data_devolucao'], $emprestimo['status']);
                        $diasClass = '';
                        
                        // Determinar classe do status
                        if ($emprestimo['status'] === 'Devolvido') {
                            $statusClass = 'status-devolvido';
                            $diasClass = 'dias-devolvido';
                        } elseif (estaAtrasado($emprestimo['data_devolucao'], $emprestimo['status'])) {
                            $statusClass = 'status-atrasado';
                            $diasClass = 'dias-atraso';
                        } else {
                            $statusClass = 'status-ativo';
                            if (strpos($diasInfo, 'Vence hoje') !== false) {
                                $diasClass = 'dias-vence-hoje';
                            } else {
                                $diasClass = 'dias-restantes';
                            }
                        }
                        ?>
                        <div class="emprestimo-card" data-status="<?php echo strtolower($emprestimo['status'] ?? 'ativo'); ?>" data-id="<?php echo $emprestimo['id']; ?>">
                            <div class="emprestimo-header">
                                <div class="emprestimo-id">ID: <?php echo $emprestimo['id']; ?></div>
                                <div class="emprestimo-status <?php echo $statusClass; ?>">
                                    <?php echo $emprestimo['status'] ?? 'Ativo'; ?>
                                </div>
                            </div>
                            
                            <div class="emprestimo-content">
                                <div class="info-section">
                                    <h3><i class="fas fa-user-graduate"></i> Informações do Aluno</h3>
                                    <div class="info-item">
                                        <span class="info-label">Nome:</span>
                                        <span class="info-value"><?php echo htmlspecialchars($emprestimo['aluno_nome']); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Série:</span>
                                        <span class="info-value"><?php echo htmlspecialchars($emprestimo['aluno_serie']); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Email:</span>
                                        <span class="info-value"><?php echo htmlspecialchars($emprestimo['aluno_email']); ?></span>
                                    </div>
                                </div>
                                
                                <div class="info-section">
                                    <h3><i class="fas fa-book"></i> Informações do Empréstimo</h3>
                                    <div class="info-item">
                                        <span class="info-label">Livro:</span>
                                        <span class="info-value"><?php echo htmlspecialchars($emprestimo['nome_livro']); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Professor:</span>
                                        <span class="info-value"><?php echo htmlspecialchars($emprestimo['professor_nome']); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Data Retirada:</span>
                                        <span class="info-value"><?php echo formatarData($emprestimo['data_retirada']); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Data Devolução:</span>
                                        <span class="info-value"><?php echo formatarData($emprestimo['data_devolucao']); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="dias-info <?php echo $diasClass; ?>">
                                <?php echo $diasInfo; ?>
                            </div>
                            
                            <!-- Botões de Ação -->
                            <div class="action-buttons">
                                <?php if ($emprestimo['status'] !== 'Devolvido'): ?>
                                    <button class="btn-action btn-devolver" onclick="confirmarDevolucao(<?php echo $emprestimo['id']; ?>, '<?php echo htmlspecialchars($emprestimo['nome_livro']); ?>', '<?php echo htmlspecialchars($emprestimo['aluno_nome']); ?>')">
                                        <i class="fas fa-check"></i>
                                        Marcar como Devolvido
                                    </button>
                                <?php endif; ?>
                                <button class="btn-action btn-excluir" onclick="confirmarExclusao(<?php echo $emprestimo['id']; ?>, '<?php echo htmlspecialchars($emprestimo['nome_livro']); ?>', '<?php echo htmlspecialchars($emprestimo['aluno_nome']); ?>')">
                                    <i class="fas fa-trash"></i>
                                    Excluir
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h3>Nenhum empréstimo encontrado</h3>
                    <p>Não há empréstimos registrados no sistema ainda.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Botão Voltar -->
        <button class="back-button" onclick="window.history.back()" title="Voltar">
            <i class="fas fa-arrow-left"></i>
        </button>
    </div>

    <!-- Modal de Confirmação de Devolução -->
    <div id="modalDevolucao" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Confirmar Devolução</h2>
                <span class="close" onclick="fecharModal('modalDevolucao')">&times;</span>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja marcar este empréstimo como devolvido?</p>
                <div style="margin-top: 1rem; padding: 1rem; background: var(--gray-100); border-radius: 0.5rem;">
                    <strong>Livro:</strong> <span id="livroNomeDevolucao"></span><br>
                    <strong>Aluno:</strong> <span id="alunoNomeDevolucao"></span>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-modal btn-cancel" onclick="fecharModal('modalDevolucao')">Cancelar</button>
                <button class="btn-modal btn-confirm" onclick="processarDevolucao()">Confirmar Devolução</button>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="modalExclusao" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Confirmar Exclusão</h2>
                <span class="close" onclick="fecharModal('modalExclusao')">&times;</span>
            </div>
            <div class="modal-body">
                <p><strong>⚠️ Atenção:</strong> Esta ação não pode ser desfeita!</p>
                <p>Tem certeza que deseja excluir este empréstimo?</p>
                <div style="margin-top: 1rem; padding: 1rem; background: var(--gray-100); border-radius: 0.5rem;">
                    <strong>Livro:</strong> <span id="livroNomeExclusao"></span><br>
                    <strong>Aluno:</strong> <span id="alunoNomeExclusao"></span>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-modal btn-cancel" onclick="fecharModal('modalExclusao')">Cancelar</button>
                <button class="btn-modal btn-confirm" style="background: var(--danger-color);" onclick="processarExclusao()">Excluir Empréstimo</button>
            </div>
        </div>
    </div>

    <script>
        // Variáveis globais
        let emprestimoIdAtual = null;
        const searchInput = document.getElementById('searchInput');
        const emprestimosGrid = document.getElementById('emprestimosGrid');
        const allItems = document.querySelectorAll('.emprestimo-card');
        const filterButtons = document.querySelectorAll('.filter-btn');
        
        let currentFilter = 'all';
        let filteredItems = Array.from(allItems);

        // Função de pesquisa e filtro
        function filterEmprestimos() {
            const searchTerm = searchInput.value.toLowerCase();
            
            filteredItems = Array.from(allItems).filter(card => {
                const text = card.textContent.toLowerCase();
                const matchesSearch = text.includes(searchTerm);
                
                let matchesFilter = true;
                if (currentFilter !== 'all') {
                    const status = card.dataset.status;
                    if (currentFilter === 'ativo') {
                        matchesFilter = status !== 'devolvido' && !card.querySelector('.status-atrasado');
                    } else if (currentFilter === 'atrasado') {
                        matchesFilter = card.querySelector('.status-atrasado') !== null;
                    } else if (currentFilter === 'devolvido') {
                        matchesFilter = status === 'devolvido';
                    }
                }
                
                return matchesSearch && matchesFilter;
            });
            
            // Mostrar/ocultar cards
            allItems.forEach(card => {
                if (filteredItems.includes(card)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Mostrar mensagem se não houver resultados
            if (filteredItems.length === 0) {
                if (!document.querySelector('.no-results')) {
                    const noResults = document.createElement('div');
                    noResults.className = 'empty-state no-results';
                    noResults.innerHTML = `
                        <i class="fas fa-search"></i>
                        <h3>Nenhum resultado encontrado</h3>
                        <p>Tente ajustar os filtros ou termo de pesquisa.</p>
                    `;
                    emprestimosGrid.appendChild(noResults);
                }
            } else {
                const noResults = document.querySelector('.no-results');
                if (noResults) {
                    noResults.remove();
                }
            }
        }

        // Event listeners para pesquisa e filtros
        searchInput.addEventListener('input', filterEmprestimos);

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                currentFilter = button.dataset.filter;
                filterEmprestimos();
            });
        });

        // Funções do Modal
        function abrirModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function fecharModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            emprestimoIdAtual = null;
        }

        // Fechar modal clicando fora dele
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }

        // Função para confirmar devolução
        function confirmarDevolucao(id, livro, aluno) {
            emprestimoIdAtual = id;
            document.getElementById('livroNomeDevolucao').textContent = livro;
            document.getElementById('alunoNomeDevolucao').textContent = aluno;
            abrirModal('modalDevolucao');
        }

        // Função para confirmar exclusão
        function confirmarExclusao(id, livro, aluno) {
            emprestimoIdAtual = id;
            document.getElementById('livroNomeExclusao').textContent = livro;
            document.getElementById('alunoNomeExclusao').textContent = aluno;
            abrirModal('modalExclusao');
        }

        // Função para processar devolução
        async function processarDevolucao() {
            if (!emprestimoIdAtual) return;

            try {
                const response = await fetch('processar_devolucao.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        emprestimo_id: emprestimoIdAtual
                    })
                });

                const result = await response.json();

                if (result.success) {
                    mostrarNotificacao('Livro devolvido com sucesso!', 'success');
                    fecharModal('modalDevolucao');
                    
                    // Atualizar o card na interface
                    const card = document.querySelector(`[data-id="${emprestimoIdAtual}"]`);
                    if (card) {
                        // Atualizar status
                        const statusElement = card.querySelector('.emprestimo-status');
                        statusElement.textContent = 'Devolvido';
                        statusElement.className = 'emprestimo-status status-devolvido';
                        
                        // Atualizar dias info
                        const diasInfo = card.querySelector('.dias-info');
                        diasInfo.textContent = 'Devolvido';
                        diasInfo.className = 'dias-info dias-devolvido';
                        
                        // Remover botão de devolução
                        const btnDevolver = card.querySelector('.btn-devolver');
                        if (btnDevolver) {
                            btnDevolver.remove();
                        }
                        
                        // Atualizar data-status
                        card.dataset.status = 'devolvido';
                    }
                    
                    // Atualizar estatísticas (recarregar página após 2 segundos)
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    mostrarNotificacao(result.message || 'Erro ao processar devolução', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                mostrarNotificacao('Erro de conexão', 'error');
            }
        }

        // Função para processar exclusão
        async function processarExclusao() {
            if (!emprestimoIdAtual) return;

            try {
                const response = await fetch('processar_exclusao.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        emprestimo_id: emprestimoIdAtual
                    })
                });

                const result = await response.json();

                if (result.success) {
                    mostrarNotificacao('Empréstimo excluído com sucesso!', 'success');
                    fecharModal('modalExclusao');
                    
                    // Remover o card da interface
                    const card = document.querySelector(`[data-id="${emprestimoIdAtual}"]`);
                    if (card) {
                        card.style.animation = 'fadeOut 0.3s ease';
                        setTimeout(() => {
                            card.remove();
                            card.remove();
                            // Recarregar página para atualizar estatísticas
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }, 300);
                    }
                } else {
                    mostrarNotificacao(result.message || 'Erro ao excluir empréstimo', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                mostrarNotificacao('Erro de conexão', 'error');
            }
        }

        // Função para mostrar notificações
        function mostrarNotificacao(mensagem, tipo) {
            const notification = document.createElement('div');
            notification.className = `notification ${tipo}`;
            
            const icon = tipo === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
            notification.innerHTML = `
                <i class="${icon}"></i>
                ${mensagem}
            `;
            
            document.body.appendChild(notification);
            
            // Mostrar notificação
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            // Remover notificação após 4 segundos
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 4000);
        }

        // Animação de entrada dos cards
        function animateCards() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            });

            allItems.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });
        }

        // Verificação de empréstimos atrasados
        function checkOverdueLoans() {
            const overdueCount = document.querySelectorAll('.status-atrasado').length;
            if (overdueCount > 0) {
                console.log(`⚠️ Atenção: ${overdueCount} empréstimo(s) em atraso!`);
                
                // Mostrar notificação de empréstimos atrasados
                setTimeout(() => {
                    mostrarNotificacao(`⚠️ ${overdueCount} empréstimo(s) em atraso!`, 'error');
                }, 2000);
            }
        }

        // Adicionar estilos de animação
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeOut {
                from {
                    opacity: 1;
                    transform: scale(1);
                }
                to {
                    opacity: 0;
                    transform: scale(0.95);
                }
            }
            
            .emprestimo-card {
                transition: all 0.3s ease;
            }
            
            .emprestimo-card:hover {
                cursor: default;
            }
            
            /* Melhorar responsividade */
            @media (max-width: 640px) {
                .emprestimo-content {
                    grid-template-columns: 1fr;
                }
                
                .info-item {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 0.25rem;
                }
                
                .info-value {
                    text-align: left;
                    font-weight: 500;
                }
                
                .action-buttons {
                    flex-direction: column;
                }
                
                .btn-action {
                    justify-content: center;
                }
            }
            
            /* Estados de loading */
            .btn-action:disabled {
                opacity: 0.6;
                cursor: not-allowed;
                pointer-events: none;
            }
            
            .loading {
                position: relative;
            }
            
            .loading::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 1rem;
                height: 1rem;
                margin: -0.5rem 0 0 -0.5rem;
                border: 2px solid transparent;
                border-top: 2px solid currentColor;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);

        // Inicializar quando a página carregar
        window.addEventListener('load', () => {
            animateCards();
            checkOverdueLoans();
        });

        // Atalhos de teclado
        document.addEventListener('keydown', (e) => {
            // ESC para fechar modais
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    if (modal.style.display === 'block') {
                        modal.style.display = 'none';
                    }
                });
            }
            
            // Ctrl+F para focar na pesquisa
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                searchInput.focus();
            }
        });

        // Auto-refresh a cada 5 minutos (opcional)
        setInterval(() => {
            if (document.visibilityState === 'visible') {
                // Verificar se há novos empréstimos atrasados
                checkOverdueLoans();
            }
        }, 300000); // 5 minutos
    </script>
</body>
</html>



