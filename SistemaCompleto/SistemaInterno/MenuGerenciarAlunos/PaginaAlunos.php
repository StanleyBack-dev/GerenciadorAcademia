<?php
session_start();

if (!isset($_SESSION['cnpj'])) {
    header("Location: PaginaLoginGerenciamentoAlunos.php");
    exit;
}

// Função para obter o nome da academia com base no CNPJ
function getAcademiaNome($cnpj) {
    $conn = new mysqli("127.0.0.1", "root", "", "Academia");
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("SELECT nome FROM academias WHERE cnpj = ?");
    $stmt->bind_param("s", $cnpj);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        return $row["nome"];
    }
    return "Academia não encontrada";
}

$academia_nome = getAcademiaNome($_SESSION['cnpj']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gerenciamento de Alunos</title>
    <link rel="stylesheet" type="text/css" href="StylePaginaAlunos.css">
</head>
<body>
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="MenuInternoGerenciarAlunos/MenuListaAlunos/PaginaListaAlunos.php">Lista de Alunos</a></li>
            <li><a href="MenuInternoGerenciarAlunos/MenuCadastrarAlunos/PaginaCadastrarAlunos.php">Cadastrar Alunos</a></li>
            <li><a href="MenuInternoGerenciarAlunos/MenuAtualizarAlunos/PaginaAtualizarAlunos.php">Atualizar Dados de Alunos</a></li>
            <li><a href="MenuInternoGerenciarAlunos/MenuDeletarAlunos/PaginaDeletarAlunos.php">Excluir Alunos</a></li>
            <li><a class="exit-button" href="PaginaLoginGerenciamentoAlunos.php?action=logout">Retornar ao Login</a></li>
        </ul>
    </div>

    <div class="content">
        <h1>Gerenciamento de Alunos da Academia: <?php echo $academia_nome; ?></h1>
    </div>
</body>
</html>
