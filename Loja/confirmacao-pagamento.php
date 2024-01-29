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
<html lang="pt"
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Byte Centre - Confirmação de Pagamento</title>
    <link rel="stylesheet" href="style7.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
</head>
<div class="fade-in">
<body>
    <header class="header">
        <a href="produtos.php"><img src="logo.png" alt="logo" width="125"></a>
        <h1 id="usuario">Olá, <?php echo $_SESSION['username']; ?></h1>
        
        <nav>
            <a href="produtos.php" class="botao"><i class="fas fa-home"></i> </a>
            <a href="contato.php" class="botao1"><i class="fas fa-phone"></i> </a>
            <a href="carrinho.php" class="botao1"><i class="fas fa-shopping-cart"></i> </a>
            <a href="conta.php" class="botao1"><i class="fas fa-user"></i> </a>
        </nav>
    </header>
    <main>
        <section class="pagamento"
            <h1 style="text-align:center;">Compra Confirmada</h1>
            <p style="text-align:center;">Obrigado por sua compra! Seu pagamento foi processado com sucesso. Volte sempre!</p>
            <a href="carrinho.php" class="botao-pagamento">Continuar Comprando</a>
        </section>
    </main>
</body>
</html>