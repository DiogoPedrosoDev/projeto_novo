<?php
session_start();
include('../includes/conexao.php');
include('../classes/Evento.php');
include('../classes/Curso.php');

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$evento = new Evento($db);
$cursos = new Curso($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create_evento'])) {
        $evento->titulo = $_POST['titulo'];
        $evento->descricao = $_POST['descricao'];
        $evento->data = $_POST['data'];
        $evento->horario_inicio = $_POST['horario_inicio'];
        $evento->horario_fim = $_POST['horario_fim'];

        if ($evento->criar()) {
            echo "Evento criado com sucesso!";
        } else {
            echo "Erro ao criar o evento.";
        }
    }

    if (isset($_POST['create_curso'])) {
        $cursos->titulo = $_POST['curso_titulo'];
        $cursos->descricao = $_POST['curso_descricao'];
        $cursos->data = $_POST['curso_data'];
        $cursos->horario_inicio = $_POST['curso_horario_inicio'];
        $cursos->horario_fim = $_POST['curso_horario_fim'];
        $cursos->evento_id = $_POST['evento_id'];

        if ($cursos->criar()) {
            echo "Curso criado com sucesso!";
        } else {
            echo "Erro ao criar o curso.";
        }
    }

    if (isset($_POST['delete_evento'])) {
        $evento->id = $_POST['evento_id'];
        if ($evento->deletar()) {
            echo "Evento excluído com sucesso!";
        } else {
            echo "Erro ao excluir o evento.";
        }
    }

    if (isset($_POST['delete_curso'])) {
        $cursos->id = $_POST['curso_id'];
        if ($cursos->deletar()) {
            echo "Curso excluído com sucesso!";
        } else {
            echo "Erro ao excluir o curso.";
        }
    }
}

$eventos = $evento->listarTodos();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Eventos e Cursos</title>
</head>
<body>

    <h2>Gerenciar Eventos</h2>

    <!-- Formulário para criar novo evento -->
    <h3>Criar Novo Evento</h3>
    <form method="post" action="gerenciarEventos.php">
        Título: <input type="text" name="titulo" required><br>
        Descrição: <textarea name="descricao" required></textarea><br>
        Data: <input type="date" name="data" required><br>
        Horário de Início: <input type="time" name="horario_inicio" required><br>
        Horário de Fim: <input type="time" name="horario_fim" required><br>
        <button type="submit" name="create_evento">Criar Evento</button>
    </form>
    <hr>

    <?php if (count($eventos) > 0): ?>
        <?php foreach ($eventos as $evento): ?>
            <div>
                <h3><?php echo $evento['titulo']; ?></h3>
                <p><?php echo $evento['descricao']; ?></p>
                <p>Data: <?php echo $evento['data']; ?> | Horário: <?php echo $evento['horario_inicio']; ?> - <?php echo $evento['horario_fim']; ?></p>
                
                <!-- Botões para editar e excluir o evento -->
                <form method="post" action="editarEvento.php" style="display:inline;">
                    <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                    <button type="submit" name="edit_evento">Editar Evento</button>
                </form>
                <form method="post" action="gerenciarEventos.php" onsubmit="return confirm('Tem certeza que deseja excluir este evento e todos os seus cursos?');" style="display:inline;">
                    <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                    <button type="submit" name="delete_evento">Excluir Evento</button>
                </form>

                <h4>Cursos:</h4>
                <?php
                $cursos_associados = $cursos->listarPorEvento($evento['id']);
                if (count($cursos_associados) > 0): ?>
                    <ul>
                        <?php foreach ($cursos_associados as  $curso): ?>
                            <li>
                                <?php echo $curso['titulo']; ?> - <?php echo $curso['descricao']; ?> 
                                (<?php echo $curso['data']; ?> | <?php echo $curso['horario_inicio']; ?> - <?php echo $curso['horario_fim']; ?>)

                                <!-- Botões para editar e excluir o curso -->
                                <form method="post" action="editarCurso.php" style="display:inline;">
                                    <input type="hidden" name="curso_id" value="<?php echo $curso['id']; ?>">
                                    <button type="submit" name="edit_curso">Editar Curso</button>
                                </form>
                                <form method="post" action="gerenciarEventos.php" onsubmit="return confirm('Tem certeza que deseja excluir este curso?');" style="display:inline;">
                                    <input type="hidden" name="curso_id" value="<?php echo $curso['id']; ?>">
                                    <button type="submit" name="delete_curso">Excluir Curso</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Nenhum curso disponível para este evento.</p>
                <?php endif; ?>

                <!-- Formulário para criar novo curso para o evento -->
                <h4>Criar Novo Curso para este Evento</h4>
                <form method="post" action="gerenciarEventos.php">
                    Título: <input type="text" name="curso_titulo" required><br>
                    Descrição: <textarea name="curso_descricao" required></textarea><br>
                    Data: <input type="date" name="curso_data" required><br>
                    Horário de Início: <input type="time" name="curso_horario_inicio" required><br>
                    Horário de Fim: <input type="time" name="curso_horario_fim" required><br>
                    <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                    <button type="submit" name="create_curso">Criar Curso</button>
                </form>

            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhum evento disponível para gerenciamento.</p>
    <?php endif; ?>

    <a href="index.php">Voltar para a Página Principal</a>

</body>
</html>
