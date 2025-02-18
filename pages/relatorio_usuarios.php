<?php
session_start();
include('../includes/conexao.php');

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$query = "SELECT id, nome, email, matricula, pontos, is_admin FROM usuarios ORDER BY nome ASC";
$stmt = $db->prepare($query);
$stmt->execute();

$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Usuários Cadastrados</title>
</head>
<link rel="stylesheet" type="text/css" href="../css/relatoriousuarios.css">

<body>

    <h2>Relatório de Usuários Cadastrados</h2>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Matrícula</th>
            <th>Pontos</th>
            <th>Tipo de Usuário</th>
        </tr>
        <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?php echo $usuario['id']; ?></td>
            <td><?php echo $usuario['nome']; ?></td>
            <td><?php echo $usuario['email']; ?></td>
            <td><?php echo $usuario['matricula']; ?></td>
            <td><?php echo $usuario['pontos']; ?></td>
            <td><?php echo $usuario['is_admin'] ? 'Administrador' : 'Usuário'; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="index.php">Voltar para a Página Principal</a>

</body>
</html>
