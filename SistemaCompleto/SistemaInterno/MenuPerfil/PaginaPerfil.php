<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["username"])) {
    header("Location: PaginaLogin.php");
    exit();
}

// Conexão com o banco de dados
$conn = new mysqli("127.0.0.1", "root", "", "Academia");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Busca as informações do usuário logado no banco de dados
$nomeUsuario = $_SESSION["username"];
$query = "SELECT * FROM usuarios WHERE nome = '$nomeUsuario'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $emailUsuario = $row["email"];
    $dataNascimento = $row["data_nascimento"];
    $dataCadastro = $row["data_cadastro"];

    // Calcula a idade a partir da data de nascimento
    $dataNascimento = new DateTime($dataNascimento);
    $hoje = new DateTime();
    $intervaloIdade = $hoje->diff($dataNascimento);
    $idade = $intervaloIdade->y;

    // Calcula o intervalo de dias desde o cadastro
    $dataCadastro = new DateTime($dataCadastro);
    $intervaloDias = $hoje->diff($dataCadastro);
    $diasCadastro = $intervaloDias->days;

    // Exibe as informações na página
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Perfil do Usuário</title>
        <link rel='stylesheet' type='text/css' href='StylePaginaPerfil.css'>
    </head>
    <body>
        <!-- Conteúdo principal -->
        <div class='content'>
            <h1>Style Fit - Perfil do Usuário</h1>
            <div class='profile-sidebar'>
                <h2>Seu Perfil</h2>
                <p>Nome: $nomeUsuario</p>
                <p>Email: $emailUsuario</p>
                <p>Data de Nascimento: " . $dataNascimento->format('d/m/Y') .  "</p>
                <p>Idade: $idade anos</p>
                <p>Data de Cadastro: " . $dataCadastro->format('d/m/Y') . "</p>
                <p>Dias desde o Cadastro: $diasCadastro dias</p>
            </div>
        </div>
    </body>
    </html>";
} else {
    echo "<p>Dados do usuário não encontrados.</p>";
}

$conn->close();
?>
