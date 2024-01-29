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

// Obter o ID do cliente a ser editado
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Consultar o cliente no banco de dados
$query = $conn->query("SELECT * FROM usuarios WHERE id = $id");
$cliente = $query->fetch_assoc();

if (!$cliente) {
    // Redirecionar o usuário para a lista de clientes se o ID for inválido
    header('Location: gerenciar_clientes.php');
    exit;
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $admin = isset($_POST['admin']) ? 1 : 0;

    // Atualizar o cliente no banco de dados
    $conn->query("UPDATE usuarios SET username = '$username', password = '$password', admin = $admin WHERE id = $id");

    // Redirecionar o usuário para a lista de clientes
    header('Location: gerenciar_clientes.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
</head>
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
<main>
    <h1>Editar Cliente</h1>
    <form action="editar_cliente.php?id=<?php echo $cliente['id']; ?>" method="post">
        <label for="username">Nome:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($cliente['username']); ?>" required>

        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($cliente['password']); ?>">


        <label for="admin">Administrador:</label>
        <input type="checkbox" id="admin" name="admin" value="1" <?php if ($cliente['admin']) echo 'checked'; ?>>

        <button type="submit">Salvar Alterações</button>
    </form>
</main>
<footer>
<p>&copy; 2022 Loja Informática. Todos os direitos reservados.</p>
</footer>
</body>
</html>