<?php
    session_start();
    //Conexão com o banco de dados
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
    <?php if ($admin): ?>
    <a href="gerenciar_clientes.php" class="botao1">Gerenciar clientes</a>
  <?php endif; ?>
  </nav>
    </header>
    
                
    <?php
 $id = $_GET['id'];

 // consultar o banco de dados para obter os detalhes do produto
 $query = $conn->query("SELECT * FROM produtos WHERE id = '$id'");
 $resultados = $query->fetch_assoc();

 // exibir os detalhes do produto
 echo '<h1>'.$resultados['nome'].'</h1>';
 echo '<img src="data:image/jpg;base64,'.base64_encode($resultados['imagem']).'" width="250" alt="Imagem do produto"> ';
 echo '<p>'.$resultados['descricao'].'</p>';
 echo '<h3>'.$resultados['preco'].' €</h3>';

 // adicionar o botão de adicionar ao carrinho
 
 echo '<form action="carrinho.php" method="post">';
 echo '<input type="hidden" name="id" value="'.$id.'">';
 echo '<input type="submit" name="adicionar" value="Adicionar ao carrinho">';
 if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
  echo '<a href="editar_produtos.php?id=' . $id . '" >Editar produto</a>';
}
 echo '</form>';

 $conn->close();
?>
</body>
</html>
   
