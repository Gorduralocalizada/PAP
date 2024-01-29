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

// Obtém o termo de pesquisa da variável global $_GET
$pesquisa = $_GET['pesquisa'] ?? '';

// Faz a consulta no banco de dados com o termo de pesquisa
$query = $conn->query("SELECT * FROM produtos WHERE nome LIKE '%$pesquisa%' OR descricao LIKE '%$pesquisa%'");

// Exibe os resultados da pesquisa
while ($resultados = $query->fetch_assoc()) {
    echo '<div class="card">';
    echo '<img src="data:image/jpg;base64,' . base64_encode($resultados['imagem']) . '" alt="Imagem do produto">';
    echo '<h3>' . $resultados['nome'] . '</h3>';
    echo '<p>' . $resultados['descricao'] . '</p>';
    echo '<h3>' . $resultados['preco'] . '0 €</h3>';
    echo '<a href="detalhes_produto.php?id=' . $resultados['id'] . '" class="card-link">Ver detalhes</a>';
    echo '</div>';
}
$pesquisa = mysqli_real_escape_string($conn, $_GET['pesquisa'] ?? '');
$conn->close();
?>