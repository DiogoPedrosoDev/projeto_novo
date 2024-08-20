<?php
include('../includes/conexao.php');
include('../classes/Usuario.php');

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = new Usuario($db);
    $usuario->nome = $_POST['nome'];
    $usuario->email = $_POST['email'];
    $usuario->matricula = $_POST['matricula'];
    $usuario->senha = $_POST['senha'];

    if ($usuario->criar()) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar o usuário.";
    }
}
?>

<form method="post" action="registro.php">
    Nome: <input type="text" name="nome" required><br>
    Email: <input type="email" name="email" required><br>
    Matrícula: <input type="text" name="matricula" required><br>
    Senha: <input type="password" name="senha" required><br>
    <input type="submit" value="Registrar">
</form>
