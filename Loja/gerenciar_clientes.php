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

// Consultar os clientes no banco de dados
$query = $conn->query("SELECT * FROM usuarios");
$clientes = [];
while ($resultado = $query->fetch_assoc()) {
    $clientes[] = $resultado;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Clientes</title>
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
</header>
<main>
    <h1>Gerenciar Clientes</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Password</th>
                <th>Administrador</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cliente['id']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['username']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['password']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['admin'] ? 'Sim' : 'Não'); ?></td>
                    <td>
                        <a href="editar_cliente.php?id=<?php echo htmlspecialchars($cliente['id']); ?>" class="botao1">Editar</a>
                        <a href="excluir_cliente.php?id=<?php echo htmlspecialchars($cliente['id']); ?>" class="botao1" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<footer>
<p>&copy; 2022 Loja Informática. Todos os direitos reservados.</p>
</footer>
</div>
<script src="sidebar.js"></script>
</body>
</html>