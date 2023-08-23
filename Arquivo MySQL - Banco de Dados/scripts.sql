-- Criação do banco de dados "ACADEMIA"
CREATE DATABASE Academia;

-- Utilização do banco de dados criado
USE Academia;

-- Criação da tabela "usuarios"
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    data_nascimento DATE,
    senha VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    pergunta_seguranca VARCHAR(255),
    resposta_seguranca VARCHAR(255),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criação da tabela "ALUNOS"
CREATE TABLE alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    data_nascimento DATE,
    email VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    objetivo VARCHAR(100)
);

-- Criação da tabela "ACADEMIAS"
CREATE TABLE academias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    cnpj VARCHAR(20) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(50) NOT NULL,
    cep VARCHAR(10) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    pergunta_seguranca VARCHAR(255),
    resposta_seguranca VARCHAR(255)
);

-- Criação da tabela "ALUNOS_ACADEMIAS"
CREATE TABLE alunos_academias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    academia_id INT NOT NULL,
    FOREIGN KEY (aluno_id) REFERENCES alunos(id),
    FOREIGN KEY (academia_id) REFERENCES academias(id)
);