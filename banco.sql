-- =============================================
-- Stubinski Joias — Script de criação do banco
-- Execute este arquivo no phpMyAdmin (aba SQL)
-- =============================================

CREATE DATABASE IF NOT EXISTS stubinski_joias
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE stubinski_joias;

-- Tabela de administradores
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(50) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela de produtos
CREATE TABLE IF NOT EXISTS produtos (
  id_produto INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  categoria ENUM('anel','colar','brinco','pulseira','personalizado') NOT NULL,
  preco DECIMAL(10,2) NOT NULL,
  descricao TEXT,
  imagem VARCHAR(255),
  destaque ENUM('sim','nao') DEFAULT 'nao',
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Inserir admin padrão (senha: admin123)
-- Hash gerado com password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO admins (usuario, senha) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE usuario = usuario;

-- Observação: a senha acima é um hash de exemplo.
-- Use o arquivo gerar_senha.php para criar o hash da sua senha real
-- e depois atualize com:
-- UPDATE admins SET senha = 'SEU_HASH_AQUI' WHERE usuario = 'admin';
