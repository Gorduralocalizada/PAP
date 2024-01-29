<?php
session_start();

// Conexão com o banco de dados
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "loja";

$conn = new mysqli('localhost', 'root', '', 'loja');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verificar se o usuário está logado e se é um administrador
if (!isset($_SESSION['username']) || !$_SESSION['admin']) {
    // Redirecionar o usuário para a página de login se ele não estiver logado ou não for um administrador
    header('Location: login.php');
    exit;
}

// Obter o ID do cliente a ser excluído
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Excluir o cliente do banco de dados
$conn->query("DELETE FROM usuarios WHERE id = $id");

// Redirecionar o usuário para a página de gerenciamento de clientes
header('Location: gerenciar_clientes.php');
exit;