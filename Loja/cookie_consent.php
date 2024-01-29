<?php
session_start();

if (!isset($_SESSION["cookie_consent"])) {
    $_SESSION["cookie_consent"] = false;
}

if (!$_SESSION["cookie_consent"]) {
    echo "
    <div id='cookie-consent'>
        <p>Este site usa cookies para garantir a melhor experiência de navegação. Continuando a navegar, você está concordando com nossa <a href='politica-de-cookies.php'>política de cookies</a>.</p>
        <button id='accept-cookies'>Aceitar cookies</button>
    </div>
    ";
}
?>