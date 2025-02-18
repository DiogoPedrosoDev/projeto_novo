<?php
session_start();
include('../includes/conexao.php');
include('../classes/Evento.php');

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$evento = new Evento($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $evento->id = $_POST['evento_id'];

    // Carrega os dados do evento
    $query = "SELECT titulo, descricao, data, horario_inicio, horario_fim FROM eventos WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $evento->id);
    $stmt->execute();

    $evento_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['update_evento'])) {
        $evento->titulo = $_POST['titulo'];
        $evento->descricao = $_POST['descricao'];
        $evento->data = $_POST['data'];
        $evento->horario_inicio = $_POST['horario_inicio'];
        $evento->horario_fim = $_POST['horario_fim'];

        if ($evento->atualizar()) {
            echo "Evento atualizado com sucesso!";
            header("Location: gerenciarEventos.php");
            exit();
        } else {
            echo "Erro ao atualizar o evento.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento</title>
</head>
<link rel="stylesheet" type="text/css" href="../css/gerenciareventos.css">
<body>

    <h2>Editar Evento</h2>

    <form method="post" action="editarEvento.php">
        <input type="hidden" name="evento_id" value="<?php echo $evento->id; ?>">
        Título: <input type="text" name="titulo" value="<?php echo $evento_data['titulo']; ?>" required><br>
        Descrição: <textarea name="descricao" required><?php echo $evento_data['descricao']; ?></textarea><br>
        Data: <input type="date" name="data" value="<?php echo $evento_data['data']; ?>" required><br>
        Horário de Início: <input type="time" name="horario_inicio" value="<?php echo $evento_data['horario_inicio']; ?>" required><br>
        Horário de Fim: <input type="time" name="horario_fim" value="<?php echo $evento_data['horario_fim']; ?>" required><br>
        <button type="submit" name="update_evento">Salvar Alterações</button>
    </form>

    <a href="gerenciarEventos.php">Voltar para Gerenciamento de Eventos</a>

</body>
</html>
