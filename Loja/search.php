<?php
require_once "cookie_consent.php";

    // Conexão com o banco de dados
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "loja";

    $conn = new mysqli('localhost', 'root', '', 'loja');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Verificar se o usuário está logado
    if (isset($_SESSION['username'])) {
        // Verificar se o usuário é um administrador
        $query = $conn->query("SELECT admin FROM usuarios WHERE username = '" . $_SESSION['username'] . "'");
        $result = $query->fetch_assoc();
        $admin = $result['admin'];
        $_SESSION['admin'] = $admin;
    } else {
        // Redirecionar o usuário para a página de login se ele não estiver logado
        header('Location: login.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Byte Centre</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>
<div class="fade-in">
<body>
<header class="header">

  <a href="produtos.php"><img src="logo.png" alt="logo" width="145"></a>
  <h1 id="usuario">Olá, <?php echo $_SESSION['username']; ?></h1>
  <div class="search-box">
  <form action="search.php" method="get">
  <input type="text" id="search" name="search" placeholder="Pesquisar...">
  <button type="submit" id="search-button" ><i class="fas fa-search"></i></button>
</form>
</div>

    <nav>
        <a href="produtos.php" class="botao"><i class="fas fa-home"></i> </a>
        <a href="contato.php" class="botao1"><i class="fas fa-phone"></i> </a>
        <a href="carrinho.php" class="botao1"><i class="fas fa-shopping-cart"></i> </a>
        <a href="conta.php" class="botao1"><i class="fas fa-user"></i> </a>
        <?php if ($admin): ?>
            <a href="gerenciar_clientes.php" class="botao1">Gerenciar clientes</a>
        <?php endif; ?>
    </nav>
</header>
<main>
<nav>
              <?php
// Obter o termo de pesquisa do parâmetro GET
$searchTerm = $_GET['search'] ?? '';

// Realizar a pesquisa no banco de dados
$searchTerm = '%' . $searchTerm . '%';
$searchTermVar = &$searchTerm;
$stmt = $conn->prepare("SELECT * FROM produtos WHERE nome LIKE ?");
$stmt->bind_param("s", $searchTermVar);
$stmt->execute();
$result = $stmt->get_result();

// Exibir os resultados da pesquisa
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="card">';
        echo '<img src="data:image/jpg;base64,' . base64_encode($row['imagem']) . '" alt="Imagem do produto" width="200" height="200">';
        echo '<h3>' . $row['nome'] . '</h3>';
        echo '<p>' . $row['descricao'] . '</p>';
        echo '<h3>' . $row['preco'] . '0 €</h3>';
        echo '<a href="detalhes_produto.php?id=' . $row['id'] . '" class="card-link">Ver detalhes</a>';
        echo '</div>';
    }
} else {
    echo 'Nenhum resultado encontrado.';
}

// Fechar a conexão com o banco de dados
$conn->close();
?>