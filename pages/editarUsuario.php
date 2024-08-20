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
$usuario->id = $_SESSION['user_id'];

// Carrega os dados do usuário
$query = "SELECT nome, email FROM usuarios WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $usuario->id);
$stmt->execute();

$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario->nome = $_POST['nome'];
    $usuario->email = $_POST['email'];

    // Atualiza a senha apenas se o campo não estiver vazio
    if (!empty($_POST['senha'])) {
        $usuario->senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    } else {
        $usuario->senha = $user_data['senha']; // Mantém a senha atual se o campo estiver vazio
    }

    if ($usuario->atualizar()) {
        echo "Perfil atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar o perfil.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
</head>
<body>

    <h2>Editar Perfil</h2>

    <form method="post" action="editarUsuario.php">
        Nome: <input type="text" name="nome" value="<?php echo $user_data['nome']; ?>" required><br>
        Email: <input type="email" name="email" value="<?php echo $user_data['email']; ?>" required><br>
        Senha: <input type="password" name="senha" placeholder="Deixe em branco para manter a senha atual"><br>
        <button type="submit">Salvar Alterações</button>
    </form>

    <a href="index.php">Voltar para a Página Principal</a>

</body>
</html>
