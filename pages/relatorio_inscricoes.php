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

$query = "SELECT u.nome AS usuario_nome, c.titulo AS curso_titulo, e.titulo AS evento_titulo
          FROM inscricoes i
          JOIN usuarios u ON i.user_id = u.id
          JOIN cursos c ON i.curso_id = c.id
          JOIN eventos e ON c.evento_id = e.id
          ORDER BY u.nome ASC";
$stmt = $db->prepare($query);
$stmt->execute();

$inscricoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Inscrições</title>
</head>
<body>

    <h2>Relatório de Inscrições</h2>

    <table border="1">
        <tr>
            <th>Nome do Usuário</th>
            <th>Evento</th>
            <th>Curso</th>
        </tr>
        <?php foreach ($inscricoes as $inscricao): ?>
        <tr>
            <td><?php echo $inscricao['usuario_nome']; ?></td>
            <td><?php echo $inscricao['evento_titulo']; ?></td>
            <td><?php echo $inscricao['curso_titulo']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="index.php">Voltar para a Página Principal</a>

</body>
</html>
