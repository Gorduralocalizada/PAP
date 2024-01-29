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
if (isset($_POST['adicionar'])) {
    $id = $_POST['id'];

    // consultar o banco de dados para obter os detalhes do produto
    $query = $conn->query("SELECT * FROM produtos WHERE id = '$id'");
    $resultados = $query->fetch_assoc();

    // verificar se o produto já está no carrinho
    if (isset($_SESSION['carrinho']) && array_key_exists($id, $_SESSION['carrinho'])) {
        // se o produto já estiver no carrinho, incremente a quantidade
        $_SESSION['carrinho'][$id]['quantidade'] += 1;
    } else {
        // se o produto não estiver no carrinho, adicione-o com a quantidade 1
        $_SESSION['carrinho'][$id] = array(
            'quantidade' => 1,
            'nome' => $resultados['nome'],
            'preco' => $resultados['preco']
        );
    }
}
if (isset($_GET['remover'])) {
    $id = $_GET['remover'];
    if (isset($_SESSION['carrinho'][$id])) {
        unset($_SESSION['carrinho'][$id]);
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Byte Centre</title>
    <link rel="stylesheet" href="style7.css">
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
    <a href="produtos.php" class="botao"><i class="fas fa-home"></i> </a>
    <a href="contato.php" class="botao1"><i class="fas fa-phone"></i> </a>
    <a href="carrinho.php" class="botao1"><i class="fas fa-shopping-cart"></i> </a>
    <a href="conta.php" class="botao1"><i class="fas fa-user"></i> </a>
  </nav>
</header>
    <main>
        <section class="produtos">
            <h1 style="text-align:center;">O meu carrinho</h1>
            <?php
            if(isset($_SESSION['carrinho'])){
                $total = 0;
                echo '<table>';
                echo '<tr><th>Produto</th><th>Quantidade</th><th>Preço</th><th>Remover</th></tr>';

                foreach($_SESSION['carrinho'] as $id => $quantidade){
                    $sql = "SELECT nome, preco FROM produtos WHERE id = ".$id;
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $nome = $row['nome'];
                            $preco = $row['preco'];
                            $total += $quantidade['quantidade'] * $preco;
                            echo '<tr><td>'.$nome.'</td><td>'.$quantidade['quantidade'].'</td><td>'.number_format($preco, 2, ',', '.').'</td><td><a href="?remover='.$id.'">Remover</a></td></tr>';
                        }
                    }
                }
                echo '</table>';
                echo '<h2>Total: '.number_format($total, 2, ',', '.').'</h2>';
                
            } else {
                echo '<h2>Não há produtos no carrinho.</h2>';
            }
            ?>
            <div class="pagamento">
            <a href="pagamento.php" class="botao-pagamento">Finalizar Compra</a>
</div>
        </section>
    </main>
</body>
</html>