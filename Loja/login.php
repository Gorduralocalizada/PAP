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

    $sql = "SELECT id FROM usuarios WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    //Set parameters and execute
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt->execute();

    //Get the result
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        //Login successful
        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $username;

        //Redirect to produtos.php
        header("Location: produtos.php");
    } else {
        //Login failed
        echo "Usuário ou senha incorretos!";
    }

    $stmt->close();
    $conn->close();
?>