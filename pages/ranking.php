<?php
session_start();
include('../includes/conexao.php');
include('../classes/Usuario.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$usuario = new Usuario($db);
$ranking = $usuario->obterRanking();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking de Participação</title>
</head>
<body>

    <h2>Ranking de Participação dos Alunos</h2>

    <table border="1">
        <tr>
            <th>Posição</th>
            <th>Nome do Aluno</th>
            <th>Pontos</th>
        </tr>
        <?php 
        $posicao = 1;
        foreach ($ranking as $usuario): ?>
        <tr>
            <td><?php echo $posicao++; ?></td>
            <td><?php echo $usuario['nome']; ?></td>
            <td><?php echo $usuario['pontos']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="index.php">Voltar para a Página Principal</a>

</body>
</html>
