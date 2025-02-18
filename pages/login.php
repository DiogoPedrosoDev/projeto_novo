<?php
session_start();
include('../includes/conexao.php');
include('../classes/Usuario.php');

$database = new Database();
$db = $database->getConnection();

$erro = "";  // Variável para armazenar a mensagem de erro

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = new Usuario($db);
    $usuario->email = $_POST['email'];
    $usuario->senha = $_POST['senha'];

    if ($usuario->autenticar()) {
        $_SESSION['user_id'] = $usuario->id;
        $_SESSION['is_admin'] = $usuario->is_admin;

        header("Location: index.php");
        exit();
    } else {
        $erro = "Email ou senha incorretos.";
    }
}
?>
<h1>Seja bem vindo!</h1>
<div class="container">
    <h2>Faça o login para acessar o sistema</h2>
    <link rel="stylesheet" type="text/css" href="../css/login.css">

    <?php if ($erro): ?>
        <div class="erro">
            <?php echo $erro; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="login.php">
        <div class="form-group">
            Email: <input type="email" name="email" required><br>
            Senha: <input type="password" name="senha" required><br>
        </div>
        <input type="submit" value="Entrar">
    </form>

    <p>Não tem uma conta? <a href="registro.php">Cadastre-se aqui</a></p>
</div>