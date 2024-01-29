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

    <div class="categorias">
        
    <button class="botao-categorias categorias-button"><i class="fa-solid fa-bars"></i></i></button>
        <ul class="menu-categorias" >
            <li><a href="produtos.php" class="botao1 <?php if(!isset($_GET['produtos'])) echo 'active'; ?>">Todos os produtos</a></li>
            <?php
            $query = $conn->query('SELECT * FROM categorias');
            while ($resultados = $query->fetch_assoc()){
                echo '<div class="categoria"><a href="produtos.php?categoria=' . $resultados['id'] . '" class="botao1">' . $resultados['nome'] . '</a></div>';
            }
            ?>
        </ul>
    </div>
</nav>

    <section class="produtos">
        <div class="container">
            <?php
            $query = $conn->query('SELECT * FROM produtos');
            $categoria = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;
            $query = $conn->query("SELECT * FROM produtos p JOIN produtos_categorias pc ON p.id = pc.produto_id JOIN categorias c ON pc.categoria_id = c.id WHERE ($categoria = 0 OR c.id = $categoria)");

            while($resultados=$query->fetch_assoc()) {
                echo '<div class="card">';
                echo '<img src="data:image/jpg;base64,'.base64_encode($resultados['imagem']).'" alt="Imagem do produto">';
                echo '<h3>'.$resultados['nome'].'</h3>';
                echo '<p>'.$resultados['descricao'].'</p>';
                echo '<h3>'.$resultados['preco'].'0 €</h3>';
                echo '<a href="detalhes_produto.php?id='.$resultados['id'].'" class="card-link">Ver detalhes</a>';
                echo '</div>';
            }
            ?>
        </div>
    </section>
</main>
<footer>
    <p>&copy; 2022 Loja Informática. Todos os direitos reservados.</p>
</footer>
</div>
<script>
$(document).ready(function(){
  var menu = $(".menu-categorias");
  $(".botao-categorias").click(function(){
    if (menu.is(":visible")) {
      menu.slideUp(500, function(){
        setTimeout(function(){
          menu.hide();
        }, 500);
      });
    } else {
      menu.slideDown(500);
    }
  });
});
</script>
<script>
$(document).ready(function() {
    $("#accept-cookies").click(function() {
        $.ajax({
            url: "set-cookie-consent.php",
            type: "POST",
            success: function() {
                $("#cookie-consent").hide();
            }
        });
    });
});
</script>
</body>
</html>