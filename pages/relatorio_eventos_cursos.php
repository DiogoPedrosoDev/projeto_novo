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

// Consulta todos os eventos e seus cursos
$query_eventos = "SELECT * FROM eventos ORDER BY data ASC";
$stmt_eventos = $db->prepare($query_eventos);
$stmt_eventos->execute();
$eventos = $stmt_eventos->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Eventos e Cursos</title>
</head>
<link rel="stylesheet" type="text/css" href="../css/relatorioeventcursos.css">
<body>

    <h2>Relatório de Eventos e Cursos</h2>

    <?php if (count($eventos) > 0): ?>
        <?php foreach ($eventos as $evento): ?>
            <div>
                <h3><?php echo $evento['titulo']; ?></h3>
                <p><?php echo $evento['descricao']; ?></p>
                <p>Data: <?php echo $evento['data']; ?> | Horário: <?php echo $evento['horario_inicio']; ?> - <?php echo $evento['horario_fim']; ?></p>
                
                <?php
                // Consulta os cursos associados ao evento
                $query_cursos = "SELECT * FROM cursos WHERE evento_id = ?";
                $stmt_cursos = $db->prepare($query_cursos);
                $stmt_cursos->bindParam(1, $evento['id']);
                $stmt_cursos->execute();
                $cursos = $stmt_cursos->fetchAll(PDO::FETCH_ASSOC);
                ?>
                
                <?php if (count($cursos) > 0): ?>
                    <ul>
                        <?php foreach ($cursos as $curso): ?>
                            <li>
                                <?php echo $curso['titulo']; ?> - <?php echo $curso['descricao']; ?>
                                (<?php echo $curso['data']; ?> | <?php echo $curso['horario_inicio']; ?> - <?php echo $curso['horario_fim']; ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Nenhum curso disponível para este evento.</p>
                <?php endif; ?>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhum evento encontrado.</p>
    <?php endif; ?>

    <a href="index.php">Voltar para a Página Principal</a>

</body>
</html>
