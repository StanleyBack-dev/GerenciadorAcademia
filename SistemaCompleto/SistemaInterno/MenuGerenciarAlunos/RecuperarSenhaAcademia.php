<!DOCTYPE html>
<html>
<head>
    <title>Recuperação de Senha</title>
    <link rel="stylesheet" type="text/css" href="StyleRecuperarSenhaAcademia.css">
</head>
<body>
    <div class="content">
        <h1>Recuperação de Senha</h1>
        <?php
        // Inclua a função generateRandomPassword() aqui
        function generateRandomPassword() {
            $length = 8;
            $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $password = "";

            for ($i = 0; $i < $length; $i++) {
                $randomIndex = rand(0, strlen($characters) - 1);
                $password .= $characters[$randomIndex];
            }

            return $password;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $cnpj = $_POST["cnpj"];
            $nome = $_POST["nome"];
            $cep = $_POST["cep"];
            $pergunta_seguranca = $_POST["pergunta_seguranca"];
            $resposta_seguranca = $_POST["resposta_seguranca"];

            // Conexão com o banco de dados
            $conn = new mysqli("127.0.0.1", "root", "", "Academia");

            if ($conn->connect_error) {
                die("Erro de conexão: " . $conn->connect_error);
            }

            // Consulta SQL para verificar as informações de recuperação de senha
            $query = "SELECT id, cnpj, nome, cep, pergunta_seguranca, resposta_seguranca FROM academias WHERE cnpj = ? AND nome = ? AND cep = ? AND pergunta_seguranca = ? AND resposta_seguranca = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssss", $cnpj, $nome, $cep, $pergunta_seguranca, $resposta_seguranca);

            // Executar a consulta
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Gerar nova senha
                $nova_senha = generateRandomPassword();

                // Atualizar senha no banco de dados
                $update_query = "UPDATE academias SET senha = ? WHERE cnpj = ?";
                $stmt_update = $conn->prepare($update_query);
                $stmt_update->bind_param("ss", $nova_senha, $cnpj);
                $stmt_update->execute();
                $stmt_update->close();

                echo "<p class='success-message'>Sua nova senha foi gerada: $nova_senha</p>";
            } else {
                echo "<p class='error-message'>Dados inválidos. Por favor, verifique suas informações e tente novamente.</p>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>
        <form method="post" action="">
            <label for="cnpj">CNPJ da Academia:</label>
            <input type="text" name="cnpj" required><br>

            <label for="nome">Nome da Academia:</label>
            <input type="text" name="nome" required><br>

            <label for="cep">CEP:</label>
            <input type="text" name="cep" required><br>

            <label for="pergunta_seguranca">Pergunta de Segurança:</label>
            <select name="pergunta_seguranca" required>
                <option value="" disabled selected>Selecione uma pergunta</option>
                <option value="Qual é o nome do seu primeiro animal de estimação?">Qual é o nome do seu primeiro animal de estimação?</option>
                <option value="Qual é a sua comida favorita?">Qual é a sua comida favorita?</option>
                <option value="Qual é o nome da sua mãe?">Qual é o nome da sua mãe?</option>
            </select><br>

            <label for="resposta_seguranca">Resposta de Segurança:</label>
            <input type="text" name="resposta_seguranca" required><br>

            <input type="submit" value="Recuperar Senha">
        </form>
    </div>
    <a class="exit-button" href="PaginaLoginGerenciamentoAlunos.php">Voltar ao Login</a>
</body>
</html>
