<?php
session_start();
include('../includes/conexao.php');
include('../classes/Curso.php');
include('../classes/Inscricao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$curso = new Curso($db);
$evento_id = $_GET['evento_id'];
$cursos = $curso->listarPorEvento($evento_id);

$inscricao = new Inscricao($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inscricao->user_id = $_SESSION['user_id'];
    $inscricao->curso_id = $_POST['curso_id'];

    if ($inscricao->criar()) {
        echo "Inscrição realizada com sucesso!";
    } else {
        echo "Erro: Conflito de horário ou outra falha ao realizar a inscrição.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Evento - Controle de Eventos</title>
</head>
    <link rel="stylesheet" type="text/css" href="../css/gerenciareventos.css">
<body>

    <h2>Cursos Disponíveis para este Evento</h2>

    <?php if (count($cursos) > 0): ?>
        <?php foreach ($cursos as $curso): ?>
            <div>
                <h3><?php echo $curso['titulo']; ?></h3>
                <p><?php echo $curso['descricao']; ?></p>
                <p>Data: <?php echo $curso['data']; ?> | Horário: <?php echo $curso['horario_inicio']; ?> - <?php echo $curso['horario_fim']; ?></p>
                <form method="post" action="detalhesEvento.php?evento_id=<?php echo $evento_id; ?>">
                    <input type="hidden" name="curso_id" value="<?php echo $curso['id']; ?>">
                    <button type="submit">Inscrever-se</button>
                </form>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhum curso disponível para este evento.</p>
    <?php endif; ?>

    <a href="index.php">Voltar para a Página Principal</a>

</body>
</html>
