<?php
session_start();
include('../includes/conexao.php');
include('../classes/Usuario.php');

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = new Usuario($db);
    $usuario->email = $_POST['email'];
    $usuario->senha = $_POST['senha'];

    if ($usuario->autenticar()) {
        $_SESSION['user_id'] = $usuario->id;
        $_SESSION['is_admin'] = $usuario->is_admin;  // Certifique-se de que isso esteja configurado corretamente

        header("Location: index.php");
        exit();  // Certifique-se de que o script pare de executar após o redirecionamento
    } else {
        echo "Email ou senha incorretos.";
    }
}
?>
<h1>Seja bem vindo!</h1>
<h2>Faça o login para acessar o sistema</h2>
<form method="post" action="login.php">
    Email: <input type="email" name="email" required><br>
    Senha: <input type="password" name="senha" required><br>
    <input type="submit" value="Entrar">
</form>

<p>Não tem uma conta? <a href="registro.php">Cadastre-se aqui</a></p> <!-- Link para registro -->
