<?php
session_start();
include('../includes/conexao.php');
include('../classes/Curso.php');

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$curso = new Curso($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $curso->id = $_POST['curso_id'];

    // Carrega os dados do curso
    $query = "SELECT titulo, descricao, data, horario_inicio, horario_fim FROM cursos WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $curso->id);
    $stmt->execute();

    $curso_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['update_curso'])) {
        $curso->titulo = $_POST['titulo'];
        $curso->descricao = $_POST['descricao'];
        $curso->data = $_POST['data'];
        $curso->horario_inicio = $_POST['horario_inicio'];
        $curso->horario_fim = $_POST['horario_fim'];

        if ($curso->atualizar()) {
            echo "Curso atualizado com sucesso!";
            header("Location: gerenciarEventos.php");
            exit();
        } else {
            echo "Erro ao atualizar o curso.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso</title>
</head>
<body>

    <h2>Editar Curso</h2>

    <form method="post" action="editarCurso.php">
        <input type="hidden" name="curso_id" value="<?php echo $curso->id; ?>">
        Título: <input type="text" name="titulo" value="<?php echo $curso_data['titulo']; ?>" required><br>
        Descrição: <textarea name="descricao" required><?php echo $curso_data['descricao']; ?></textarea><br>
        Data: <input type="date" name="data" value="<?php echo $curso_data['data']; ?>" required><br>
        Horário de Início: <input type="time" name="horario_inicio" value="<?php echo $curso_data['horario_inicio']; ?>" required><br>
        Horário de Fim: <input type="time" name="horario_fim" value="<?php echo $curso_data['horario_fim']; ?>" required><br>
        <button type="submit" name="update_curso">Salvar Alterações</button>
    </form>

    <a href="gerenciarEventos.php">Voltar para Gerenciamento de Eventos</a>

</body>
</html>
