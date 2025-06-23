-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12/06/2025 às 12:18
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `tcc`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `aluno`
--

CREATE TABLE `aluno` (
  `id` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `serie` varchar(2) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `aluno`
--

INSERT INTO `aluno` (`id`, `nome`, `serie`, `email`) VALUES
(3, 'Natan', '3d', 'NATANAEL@GMAIL.COM'),
(4, 'Ana Souza', '2A', 'ana.souza@email.com'),
(5, 'Carlos Mendes', '3B', 'carlos.mendes@email.com'),
(6, 'Beatriz Lima', '1C', 'beatriz.lima@email.com'),
(7, 'Rafael Oliveira', '2D', 'rafael.oliveira@email.com'),
(8, 'Fernanda Santos', '3A', 'fernanda.santos@email.com'),
(9, 'Gustavo Ferreira', '1B', 'gustavo.ferreira@email.com'),
(10, 'Juliana Costa', '2C', 'juliana.costa@email.com'),
(11, 'Marcelo Almeida', '3D', 'marcelo.almeida@email.com'),
(12, 'Roberta Nunes', '1A', 'roberta.nunes@email.com'),
(13, 'Eduardo Martins', '2B', 'eduardo.martins@email.com'),
(14, 'Sofia Pereira', '3C', 'sofia.pereira@email.com'),
(15, 'Leonardo Ramos', '1D', 'leonardo.ramos@email.com'),
(16, 'Mariana Silva', '2A', 'mariana.silva@email.com'),
(17, 'Bruno Rocha', '3B', 'bruno.rocha@email.com'),
(18, 'Camila Xavier', '1C', 'camila.xavier@email.com'),
(19, 'Fernando Lopes', '2D', 'fernando.lopes@email.com'),
(20, 'Tatiane Moreira', '3A', 'tatiane.moreira@email.com'),
(21, 'Lucas Santos', '1B', 'lucas.santos@email.com'),
(22, 'Larissa Cunha', '2C', 'larissa.cunha@email.com'),
(23, 'Paulo Henrique', '3D', 'paulo.henrique@email.com'),
(24, 'Viviane Oliveira', '1A', 'viviane.oliveira@email.com'),
(25, 'Ricardo Mattos', '2B', 'ricardo.mattos@email.com'),
(26, 'Carolina Borges', '3C', 'carolina.borges@email.com'),
(27, 'Henrique Batista', '1D', 'henrique.batista@email.com'),
(28, 'Isabela Faria', '2A', 'isabela.faria@email.com'),
(29, 'João Victor', '3B', 'joao.victor@email.com'),
(30, 'Natália Cardoso', '1C', 'natalia.cardoso@email.com'),
(31, 'Arthur Gomes', '2D', 'arthur.gomes@email.com'),
(32, 'Débora Vasconcelos', '3A', 'debora.vasconcelos@email.com'),
(33, 'Felipe Moura', '1B', 'felipe.moura@email.com'),
(34, 'Sandra Lima', '2C', 'sandra.lima@email.com'),
(35, 'Daniel Ribeiro', '3D', 'daniel.ribeiro@email.com'),
(36, 'Robson Peixoto', '1A', 'robson.peixoto@email.com'),
(37, 'Elisa Martins', '2B', 'elisa.martins@email.com'),
(38, 'Thiago Carvalho', '3C', 'thiago.carvalho@email.com'),
(39, 'Patrícia Nogueira', '1D', 'patricia.nogueira@email.com'),
(40, 'Gabriel Fernandes', '2A', 'gabriel.fernandes@email.com'),
(41, 'Vanessa Prado', '3B', 'vanessa.prado@email.com'),
(42, 'Andreia Souza', '1C', 'andreia.souza@email.com'),
(43, 'Renato Xavier', '2D', 'renato.xavier@email.com'),
(44, 'Brenda Castro', '3A', 'brenda.castro@email.com'),
(45, 'Vinícius Costa', '1B', 'vinicius.costa@email.com'),
(46, 'Tatiana Mendes', '2C', 'tatiana.mendes@email.com'),
(47, 'Eduardo Pires', '3D', 'eduardo.pires@email.com'),
(48, 'Cristina Rocha', '1A', 'cristina.rocha@email.com'),
(49, 'Fábio Nascimento', '2B', 'fabio.nascimento@email.com'),
(50, 'Mônica Ribeiro', '3C', 'monica.ribeiro@email.com'),
(51, 'Rodrigo Almeida', '1D', 'rodrigo.almeida@email.com'),
(52, 'Juliana Xavier', '2A', 'juliana.xavier@email.com'),
(53, 'Guilherme Ferreira', '3B', 'guilherme.ferreira@email.com'),
(54, 'Bruno Silva', '1C', 'bruno.silva@email.com'),
(55, 'Camila Santos', '2D', 'camila.santos@email.com'),
(56, 'Felipe Oliveira', '3A', 'felipe.oliveira@email.com'),
(57, 'Viviane Gomes', '1B', 'viviane.gomes@email.com'),
(58, 'Rafael Cardoso', '2C', 'rafael.cardoso@email.com'),
(59, 'Ana Paula Nunes', '3D', 'ana.nunes@email.com'),
(60, 'Lucas Medeiros', '1A', 'lucas.medeiros@email.com'),
(61, 'Roberta Mattos', '2B', 'roberta.mattos@email.com'),
(62, 'Pedro Cunha', '3C', 'pedro.cunha@email.com'),
(63, 'Fernanda Batista', '1D', 'fernanda.batista@email.com'),
(64, 'Gabriela Mendes', '2A', 'gabriela.mendes@email.com'),
(65, 'Alexandre Vasconcelos', '3B', 'alexandre.vasconcelos@email.com'),
(66, 'Tatiane Ribeiro', '1C', 'tatiane.ribeiro@email.com'),
(67, 'Thiago Peixoto', '2D', 'thiago.peixoto@email.com'),
(68, 'Brenda Moura', '3A', 'brenda.moura@email.com'),
(69, 'Ricardo Nascimento', '1B', 'ricardo.nascimento@email.com'),
(70, 'André Martins', '2C', 'andre.martins@email.com'),
(71, 'Carolina Souza', '3D', 'carolina.souza@email.com'),
(72, 'Mário Fernandes', '1A', 'mario.fernandes@email.com'),
(73, 'Daniele Borges', '2B', 'daniele.borges@email.com'),
(74, 'Julio Nogueira', '3C', 'julio.nogueira@email.com'),
(75, 'Marcela Costa', '1D', 'marcela.costa@email.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `emprestimo`
--

CREATE TABLE `emprestimo` (
  `id` int(11) NOT NULL,
  `aluno_id` int(20) NOT NULL,
  `professor_id` int(20) NOT NULL,
  `livro_id` int(20) NOT NULL,
  `data_retirada` date NOT NULL,
  `data_devolucao` date NOT NULL,
  `status` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `emprestimo`
--

INSERT INTO `emprestimo` (`id`, `aluno_id`, `professor_id`, `livro_id`, `data_retirada`, `data_devolucao`, `status`) VALUES
(6, 3, 3, 12, '2025-05-23', '2025-05-31', 'Devolvido'),
(7, 9, 1, 12, '2025-06-06', '2025-06-28', ''),
(8, 3, 4, 3, '2025-05-07', '2025-06-05', 'Devolvido');

-- --------------------------------------------------------

--
-- Estrutura para tabela `livro`
--

CREATE TABLE `livro` (
  `id` int(11) NOT NULL,
  `nome_livro` varchar(40) NOT NULL,
  `nome_autor` varchar(40) NOT NULL,
  `isbn` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `livro`
--

INSERT INTO `livro` (`id`, `nome_livro`, `nome_autor`, `isbn`) VALUES
(3, 'Embedded C Programming', 'Ms.G.SUMITHA,  Dr.S.VIJAYAKUMARI SARADHA', '9788119980642'),
(4, 'Embedded C Programming', 'Ms.G.SUMITHA,  Dr.S.VIJAYAKUMARI SARADHA', '9788119980642'),
(5, 'Yakov G. Berkovich; Lev S. Kazarin; Emma', 'Yakov G. Berkovich, Lev S. Kazarin, Emma', '9783110390544'),
(6, 'Vita et miracula S. Antonii, Olisipensis', 'Desconhecido', 'BL:A002677592'),
(7, 'Vita et miracula S. Antonii, Olisipensis', 'Desconhecido', 'BL:A002677592'),
(8, 'Vita et miracula S. Antonii, Olisipensis', 'Desconhecido', 'BL:A002677592'),
(9, 'Vita et miracula S. Antonii, Olisipensis', 'Desconhecido', 'BL:A002677592'),
(10, 'Harry Potter e a Criança Amaldiçoada - P', 'J.K. Rowling, John Tiffany, Jack Thorne', '9781781105337'),
(11, 'Vita et miracula S. Antonii, Olisipensis', 'Desconhecido', 'BL:A002677592'),
(12, 'Capitão América: O Novo Capitão América', 'Mark Gruenwald', '9786525913926');

-- --------------------------------------------------------

--
-- Estrutura para tabela `professor`
--

CREATE TABLE `professor` (
  `id` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `professor`
--

INSERT INTO `professor` (`id`, `nome`, `cpf`, `email`, `senha`) VALUES
(1, 'joão Nelson Barbosa', '86795354358', 'joaobarbosanelson@gmail.com', '$2y$10$5cheHkHEYAk1OeaWibBwyuHo78GxUopUrMI8h0aQu1w62xnYbxVsq'),
(3, 'leo', '55042415882', 'leonardolopesnunes06@gmail.com', '$2y$10$CIhpKt5dWbshNFgdFGp4J.D7xrS4tX.T2avehHjLK2.xNJdyPq/6e'),
(4, 'fernando', '30398565471', 'vvrrtnhtrb@gmail.com', '$2y$10$kV8Z0StMuiKRyS5VCu51T.5Ewwm1T62l5E8W2nRxAjJCAIySxh5X6');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `aluno`
--
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_livro_id` (`livro_id`),
  ADD KEY `fk_professor_id` (`professor_id`),
  ADD KEY `fk_aluno_id` (`aluno_id`);

--
-- Índices de tabela `livro`
--
ALTER TABLE `livro`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `professor`
--
ALTER TABLE `professor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aluno`
--
ALTER TABLE `aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `livro`
--
ALTER TABLE `livro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `professor`
--
ALTER TABLE `professor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD CONSTRAINT `fk_aluno_id` FOREIGN KEY (`aluno_id`) REFERENCES `aluno` (`id`),
  ADD CONSTRAINT `fk_livro_id` FOREIGN KEY (`livro_id`) REFERENCES `livro` (`id`),
  ADD CONSTRAINT `fk_professor_id` FOREIGN KEY (`professor_id`) REFERENCES `professor` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
