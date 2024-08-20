<?php
session_start();
include('../includes/conexao.php');
include('../classes/Evento.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$evento = new Evento($db);
$eventos = $evento->listarTodos();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal - Controle de Eventos</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <!-- Opção para redirecionar para Gerenciar Eventos, visível apenas para administradores -->
    <?php if ($_SESSION['is_admin']): ?>
        <div style="text-align: right;">
            <a href="gerenciarEventos.php" style="font-size: 16px; color: #333; text-decoration: none;">
                <i class="fas fa-cogs"></i> Gerenciar Eventos
            </a>
        </div>
    <?php endif; ?>

    <h2>Eventos Disponíveis</h2>

    <?php if (count($eventos) > 0): ?>
        <?php foreach ($eventos as $evento): ?>
            <div>
                <h3><?php echo $evento['titulo']; ?></h3>
                <p><?php echo $evento['descricao']; ?></p>
                <p>Data: <?php echo $evento['data']; ?> | Horário: <?php echo $evento['horario_inicio']; ?> - <?php echo $evento['horario_fim']; ?></p>
                <a href="detalhesEvento.php?evento_id=<?php echo $evento['id']; ?>">Ver Cursos</a>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhum evento disponível no momento.</p>
    <?php endif; ?>

    <section class="relatorios">
    <?php if ($_SESSION['is_admin']): ?>
        <h2>Relatórios</h2>
        <ul>
            <li><a href="relatorio_usuarios.php">Relatório de Usuários Cadastrados</a></li>
            <li><a href="relatorio_eventos_cursos.php">Relatório de Eventos e Cursos Cadastrados</a></li>
            <li><a href="relatorio_inscricoes.php">Relatório de Inscrições</a></li>
        </ul>
    <?php endif; ?>
    </section>
    
<section class="ranking">
<h2>Ranking de Participação</h2>
<ul>
    <li><a href="ranking.php">Ver Ranking de Participação dos Alunos</a></li>
</ul>
</section>


<form action="../services/Deslogar.php" method="post">
    <button type="submit">Logout</button>
</form>

<a href="editarUsuario.php">Editar Perfil</a>

</body>
</html>

</body>
</html>
