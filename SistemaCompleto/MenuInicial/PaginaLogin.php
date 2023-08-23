<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['username']);
    session_destroy(); // Opcional: Destruir a sessão por completo
    header("Location: ../MenuInicial/PaginaLogin.php");
    exit;
}

$loginError = false; // Flag para controlar a exibição da mensagem de erro

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Conexão com o banco de dados
    $conn = new mysqli("127.0.0.1", "root", "", "Academia");

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE nome = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashedPasswordFromDatabase = $row["senha"];

        // Verifica a senha usando password_verify
        if (password_verify($password, $hashedPasswordFromDatabase)) {
            $_SESSION['username'] = $username; // Armazena o nome de usuário na sessão
            header("Location: ../SistemaInterno/PaginaSisteMenuIni.php"); // Redirecionar para a página após o login bem-sucedido
            exit();
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
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="StyleLogin.css">
</head>
<body>
    <h2>Faça seu Login</h2>
    
    <?php if ($loginError): ?>
        <p style="color: red;">Nome de usuário ou senha incorretos. Tente novamente.</p>
    <?php endif; ?>
    
    <form method="post" action="">
        <label>Nome de Usuário:</label>
        <input type="text" name="username" required><br>
        
        <label>Senha:</label>
        <input type="password" name="password" required><br>
        
        <input type="submit" value="Entrar">
    </form>
    
    <p><a href="recuperarsenha.php">Esqueceu a senha?</a></p>
</body>
</html>
