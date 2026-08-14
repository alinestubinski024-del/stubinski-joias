<?php
// Configurações de acesso ao banco de dados
$host   = 'localhost';
$dbname = 'stubinski_joias';
$user   = 'root';   // usuário padrão do XAMPP
$pass   = '';       // senha padrão do XAMPP é vazia

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
