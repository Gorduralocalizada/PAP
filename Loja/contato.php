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
    
  <a href="produtos.php"><img src="logo.png" alt="logo" width="145"></a>
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
    <a href="produtos.php" class="botao"><i class="fas fa-home"></i> </a>
    <a href="contato.html" class="botao1"><i class="fas fa-phone"></i> </a>
    <a href="carrinho.php" class="botao1"><i class="fas fa-shopping-cart"></i> </a>
    <a href="conta.php" class="botao1"><i class="fas fa-user"></i> </a>
  </nav>
</header>
    <main>
        <h2>Informação de contato</h2>
        <p>Email: al.13732@aefontespmelo.pt</p>
        <p>Telefone: +351 912550314</p>
        <p>Morada: Rua silva porto</p>
        <h2>Fale connosco</h2>
        <form>
            <input type="text" name="name" placeholder="Nome" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="subject" placeholder="Assunto" required>
            <textarea name="message" rows="5" placeholder="Mensagem" required></textarea>
            <input type="submit" value="Submit">
        </form>
    </main>
    <footer>
        <p>&copy; 2022 Loja Informática. Todos os direitos reservados.</p>
    </footer>
</div>
</body>
</html>