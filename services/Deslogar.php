<?php
session_start();

// Destrói todas as variáveis de sessão
session_unset();

// Destroi a sessão
session_destroy();

// Redireciona para a página de login
header("Location: ../pages/login.php");
exit();
?>
