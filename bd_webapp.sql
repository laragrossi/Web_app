-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13/11/2025 às 18:30
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
-- Banco de dados: `bd_webapp`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `eventos`
--

CREATE TABLE `eventos` (
  `id_evento` int(11) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `descricao` varchar(500) NOT NULL,
  `local` varchar(200) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `capacidade` decimal(6,0) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `eventos`
--

INSERT INTO `eventos` (`id_evento`, `nome`, `descricao`, `local`, `data`, `hora`, `capacidade`, `imagem`) VALUES
(1, 'esposição de artes (teste)', 'esposição de artes', 'Rua: joão batista', '2025-11-21', '14:30:00', 100, '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `login_usuario` varchar(30) NOT NULL,
  `id_evento` int(11) NOT NULL,
  `data_reserva` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_reserva` enum('ativa','cancelada') DEFAULT 'ativa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `login` varchar(30) NOT NULL,
  `senha` varchar(128) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `tipo` char(1) NOT NULL,
  `quant_acesso` int(11) NOT NULL,
  `status` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`login`, `senha`, `nome`, `tipo`, `quant_acesso`, `status`) VALUES
('giovancomumnso@gmail.com', '$2y$10$xTJ23AAlmqLM/rBs8UHNGepmm/uR3Ccc7dDFv//LeF5Ojd/yG3UIe', 'giovana', 'A', 1, 'A'),
('jamillota@gmail.com', '$2y$10$A47nnDoBxsDMCsoEochVFeA60t2mloBiyWmrkA6TsuQnw.LDqNLDS', 'jamilly', 'U', 0, 'A'),
('jamilly@gmail.com', '$2y$10$tsfSPmPabY6YXtwhtqm5POUFJVRmdLq1I.Er6SZMa3jJ4vQz2adB.', 'jamilly', 'A', 3, 'B'),
('jamillybrito847@gmail.com', '$2y$10$WNdmFef.6qXZ5YlO3zf4tODN4GPepDVh4hmNx/DEhzQs0KKZqo1U.', 'jamilly', 'U', 0, 'A'),
('jamillybrito@gmail.com', '$2y$10$UYw8Q/tiiydhlxxm8bZmZeAGmD35HDh6rBgYQf/oxMO8g9kAmXqV2', 'jamilly', 'A', 0, 'A'),
('lara@gmail.com', '$2y$10$Ctj9PQ.208G.Sm4cryccDusY.xLQ21idS/6fX5pvY5pv87.o9CLA6', 'lara', 'A', 1, 'A'),
('milly@gmail.com', '$2y$10$7aHYSHT0kv0Vn8V6kYjtsOjouXQkMl4yzwhebJz2rOPwCE3bQRcpO', 'milly', 'A', 3, 'B'),
('natan@gmail.com', '$2y$10$invDFV7veKjxRAZT98KsLeV9KjIGgpf3dA/SSYyes9ubjsWACCpL2', 'natan', 'A', 3, 'B');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
