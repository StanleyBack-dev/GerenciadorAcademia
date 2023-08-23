<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['cnpj']);
    header("Location: PaginaLoginGerenciamentoAlunos.php");
    exit;
}

$loginError = false; // Flag para controlar a exibição da mensagem de erro

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cnpj = $_POST["cnpj"];
    $senha = $_POST["senha"];

    // Conexão com o banco de dados
    $conn = new mysqli("127.0.0.1", "root", "", "Academia");

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Preparar a consulta SQL e evitar SQL injection
    $query = "SELECT id, cnpj, senha FROM academias WHERE cnpj = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $cnpj);

    // Executar a consulta
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $cnpj, $hashedPassword);
        $stmt->fetch();

        // Verificar a senha usando password_verify
        if (password_verify($senha, $hashedPassword)) {
            // Login bem-sucedido
            $_SESSION['cnpj'] = $cnpj;
            header("Location: PaginaAlunos.php");
            exit;
        } else {
            $loginError = true; // Define a flag para exibir a mensagem de erro
        }
    } else {
        $loginError = true; // Define a flag para exibir a mensagem de erro
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Academia</title>
    <link rel="stylesheet" type="text/css" href="StylePaginaLoginGerenAlunos.css">
</head>
<body>
    <div class="content">
        <h1>Faça seu Login</h1>
        <?php if ($loginError): ?>
            <p class="error-message">CNPJ ou senha incorretos. Por favor, tente novamente.</p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="cnpj">CNPJ da Academia:</label>
            <input type="text" name="cnpj" required><br>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" required><br>

            <input type="submit" value="Entrar">
        </form>
        <p><a href="RecuperarSenhaAcademia.php">Esqueceu sua senha?</a></p>
    </div>
    <a class="exit-button" href="../PaginaSisteMenuIni.php">Retornar ao Menu Inicial</a>
</body>
</html>
