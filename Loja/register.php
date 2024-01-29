<?php
session_start();

// Conexão com o banco de dados
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "loja";

// Verificar conexão
$conn = new mysqli('localhost', 'root', '', 'loja');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT id FROM usuarios WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_POST["username"]);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['mensagem'] = "Usuário já existe!";
    header("Location: register.html");
    exit();
}
// Inserir o usuário no banco de dados
$sql = "INSERT INTO usuarios (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $_POST["username"], $_POST["password"]);
// Criptografar a senha
$password = password_hash($password, PASSWORD_DEFAULT);

if ($stmt->execute()) {
    $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
    header("Location: login.html");
} else {
    $_SESSION['mensagem'] = "Erro no cadastro!";
    header("Location: register.html");
}

$stmt->close();
$conn->close
?>