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
    exit();
}

// Obter o ID do produto a ser editado
$id = $_GET['id'];

// Consultar o banco de dados para obter os detalhes do produto
$query = $conn->query("SELECT * FROM produtos WHERE id = '$id'");
$resultados = $query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Byte Centre</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
</head>
<div class="fade-in">
<body>
<header class="header">
    
  <a href="produtos.php"><img src="logo.png" alt="logo" width="125"></a>
  <h1 id="usuario">Olá, <?php echo $_SESSION['username']; ?></h1>
  
  <nav>
  <div class="categorias">
    
    <a href="produtos.php" class="botao1 <?php if(!isset($_GET['produtos'])) echo 'active'; ?>">Todos os produtos</a>
    
      <?php
      $query = $conn->query('SELECT * FROM categorias');
      while ($resultados = $query->fetch_assoc()) {
        echo '<a href="produtos.php?categoria=' . $resultados['id'] . '" class="botao1 categoria">' . $resultados['nome'] . '</a>';
      }
      ?>
    </div>
  </nav>
  <nav>
    <a href="contato.html" class="botao1"><i class="fas fa-phone"></i> </a>
    <a href="carrinho.php" class="botao1"><i class="fas fa-shopping-cart"></i> </a>
    <a href="conta.php" class="botao1"><i class="fas fa-user"></i> </a>
  </nav>
</header>
<?php
// Exibir o formulário de edição com os detalhes do produto
echo '<h1>Editar produto</h1>';
echo '<form action="atualizar_produtos.php" method="post" enctype="multipart/form-data">';
echo '<input type="hidden" name="id" value="' . $id . '">';
echo '<label for="nome">Nome:</label>';
echo '<input type="text" name="nome" id="nome" value="' . ($resultados ? $resultados['nome'] : '') . '">';
echo '<br>';
echo '<label for="descricao">Descrição:</label>';
echo '<textarea name="descricao" id="descricao">' . ($resultados ? $resultados['descricao'] : '') . '</textarea>';
echo '<br>';
echo '<label for="preco">Preço:</label>';
echo '<input type="number" step="0.01" name="preco" id="preco" value="' . ($resultados ? $resultados['preco'] : '') . '">';
echo '<br>';
echo '<label for="imagem">Imagem:</label>';
echo '<input type="file" name="imagem" id="imagem">';
echo '<br>';
echo '<input type="submit" name="atualizar" value="Atualizar">';
echo '</form>';

$conn->close();
?>
