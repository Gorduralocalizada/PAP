<?php
session_start();

$_SESSION["cookie_consent"] = true;

setcookie("cookie_consent", "true", time() + 60 * 60 * 24 * 365);