<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Dados do formulário
    $nome = $_POST["nome"];
    $data_nascimento = $_POST["data_nascimento"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $objetivo = $_POST["objetivo"];
    
    // CNPJ da academia para relacionar o aluno
    $cnpj_academia = $_POST["cnpj_academia"];

    // Conexão com o banco de dados
    $conn = new mysqli("127.0.0.1", "root", "", "Academia");

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Prepared statement para inserir os dados
    $stmt = $conn->prepare("INSERT INTO alunos (nome, data_nascimento, email, telefone, objetivo, data_cadastro) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $nome, $data_nascimento, $email, $telefone, $objetivo);

    if ($stmt->execute()) {
        $aluno_id = $stmt->insert_id;

        // Obter o ID da academia usando o CNPJ
        $stmt = $conn->prepare("SELECT id FROM academias WHERE cnpj = ?");
        $stmt->bind_param("s", $cnpj_academia);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $academia_id = $row["id"];

            // Inserir o relacionamento na tabela alunos_academias
            $stmt = $conn->prepare("INSERT INTO alunos_academias (aluno_id, academia_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $aluno_id, $academia_id);
            $stmt->execute();

            $confirmation_message = "Aluno cadastrado com sucesso!";
        } else {
            $error_message = "Nenhuma academia encontrada com o CNPJ fornecido.";
        }
    } else {
        $error_message = "Erro ao cadastrar aluno: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastrar Aluno</title>
    <link rel="stylesheet" type="text/css" href="StylePaginaCadastrarAlunos.css">
</head>
<body>
    <div class="sidebar">
        <!-- Seu menu aqui -->
    </div>

    <div class="content">
        <h1>Cadastrar Aluno</h1>
        <form method="post" action="">
            <label>Nome:</label>
            <input type="text" name="nome" required><br>

            <label>Data de Nascimento:</label>
            <input type="date" name="data_nascimento" required><br>

            <label>Email:</label>
            <input type="email" name="email" required><br>

            <label>Telefone:</label>
            <input type="tel" name="telefone" required><br>

            <label>Objetivo:</label>
            <input type="text" name="objetivo" required><br>
            
            <!-- Campo para CNPJ da academia -->
            <label>CNPJ da Academia:</label>
            <input type="text" name="cnpj_academia" required><br>

            <br><input type="submit" value="Cadastrar">
        </form>
        <a class="exit-button" href="/ProjetoAcademia/SistemaCompleto/SistemaInterno/MenuGerenciarAlunos/PaginaAlunos.php">Retornar ao Menu</a>

        <?php
        if (isset($confirmation_message)) {
            echo '<br><div class="confirmation-message">' . $confirmation_message . '</div>';
        }

        if (isset($error_message)) {
            echo '<div class="error-message">' . $error_message . '</div>';
        }
        ?>
    </div>
</body>
</html>
