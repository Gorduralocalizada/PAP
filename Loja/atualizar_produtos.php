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

    // Verificar se o usuário é um administrador
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
        // Redirecionar o usuário para a página de login se ele não for um administrador
        header('Location: index.php');
        exit();
    }

    // Obter os dados do formulário de edição de produto
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
    $preco = isset($_POST['preco']) ? (float)$_POST['preco'] : 0;
    $imagem = isset($_FILES['imagem']) ? $_FILES['imagem'] : null;

    // Verificar se o arquivo de imagem foi enviado
    if ($imagem && $imagem['error'] == UPLOAD_ERR_OK) {
        // Ler o conteúdo do arquivo de imagem
        $fp = fopen($imagem['tmp_name'], 'r');
        $imagem_data = fread($fp, $imagem['size']);
        fclose($fp);

        // Codificar a imagem em base64
        $imagem_base64 = 'data:' . $imagem['type'] . ';base64,' . base64_encode($imagem_data);

        // Atualizar o produto no banco de dados
        $query = $conn->query("UPDATE produtos SET nome = '$nome', descricao = '$descricao', preco = $preco, imagem = '$imagem_base64' WHERE id = $id");

        // Verificar se a atualização foi bem-sucedida
        if ($query) {
            // Redirecionar o usuário para a página de listagem de produtos
            header('Location: produtos.php');
            exit();
        } else {
            // Exibir uma mensagem de erro
            echo 'Erro ao atualizar o produto.';
        }
    } else {
        // Atualizar o produto no banco de dados sem a imagem
        $query = $conn->query("UPDATE produtos SET nome = '$nome', descricao = '$descricao', preco = $preco WHERE id = $id");

        // Verificar se a atualização foi bem-sucedida
        if ($query) {
            // Redirecionar o usuário para a página de listagem de produtos
            header('Location: produtos.php');
            exit();
        } else {
            // Exibir uma mensagem de erro
            echo 'Erro ao atualizar o produto.';
        }
    }
?>