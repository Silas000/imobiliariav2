-- Script SQL para criação do banco de dados e tabelas
-- Sistema Imobiliária v2.0
-- Database: imobiliaria2

CREATE DATABASE IF NOT EXISTS imobiliaria2 CHARACTER SET utf8 COLLATE utf8_general_ci;
USE imobiliaria2;

-- Tabela de usuários (administradores)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabela de casas/imóveis
CREATE TABLE IF NOT EXISTS casas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    whatsapp VARCHAR(50) NOT NULL,
    preco VARCHAR(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabela de imagens das casas
CREATE TABLE IF NOT EXISTS imagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    casa_id INT NOT NULL,
    foto VARCHAR(255) NOT NULL,
    FOREIGN KEY (casa_id) REFERENCES casas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
