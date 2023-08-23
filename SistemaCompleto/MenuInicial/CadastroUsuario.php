<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" type="text/css" href="StyleCadastroUsuario.css">
</head>
<body>
    <div class="container">
        <div class="title-container">
            <h1>Faça seu Cadastro</h1>
        </div>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nome = $_POST["nome"];
            $dataNascimento = $_POST["data_nascimento"];
            $email = $_POST["email"];
            $senha = $_POST["senha"];
            $confirmacaoSenha = $_POST["confirmacao_senha"];
            $perguntaSeguranca = $_POST["pergunta_seguranca"];
            $respostaSeguranca = $_POST["resposta_seguranca"];

            // Gera um hash seguro da senha
             $hashedPassword = password_hash($senha, PASSWORD_DEFAULT);

            $senhaValida = true;

            // Conexão com o banco de dados
            $conn = new mysqli("127.0.0.1", "root", "", "Academia");

            if ($conn->connect_error) {
                die("Erro de conexão: " . $conn->connect_error);
            }

            $query = "SELECT * FROM usuarios WHERE nome = '$nome'";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                echo "<div class='error-message'>Nome de usuário já existe. Por favor, escolha outro nome.</div>";
            } else {
                if (strlen($senha) < 8 || !preg_match('/[A-Z]/', $senha) || !preg_match('/\d/', $senha) || !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $senha) || $senha !== $confirmacaoSenha) {
                    $senhaValida = false;
                    echo "<div class='error-message'>A senha não atende aos requisitos<br> ou as senhas não coincidem. Tente novamente.</div>";
                } else {
                    // Insere os dados na tabela
                    $insertQuery = "INSERT INTO usuarios (nome, data_nascimento, senha, email, pergunta_seguranca, resposta_seguranca, data_cadastro) VALUES ('$nome', '$dataNascimento', '$hashedPassword', '$email', '$perguntaSeguranca', '$respostaSeguranca', NOW())";
                    if ($conn->query($insertQuery) === TRUE) {
                        echo "Usuário cadastrado com sucesso!";
                        // Redirecionamento para a página de login
                        header("Location: PaginaLogin.php");
                        exit; // Encerra a execução deste script
                    } else {
                        echo "Erro ao cadastrar o usuário: " . $conn->error;
                    }
                }
            }

            $conn->close();
        }
        ?>

        <div class="academy-name-big">
            <span class="style">Style</span>
            <span class="fit">Fit</span>
        </div>

        <form method="post" action="">
            <label>Nome:</label>
            <input type="text" name="nome" required><br>

            <label>Data de Nascimento:</label>
            <input type="date" name="data_nascimento" required><br>

            <label>Email:</label>
            <input type="email" name="email" required><br>

            <label for="senha">Senha (mínimo 8 caracteres, incluindo uma letra maiúscula, um número e um caractere especial):</label>
            <input type="password" name="senha" required><br>

            <label>Confirmação de Senha:</label>
            <input type="password" name="confirmacao_senha" required><br>

            <label for="pergunta_seguranca">Escolha uma Pergunta de Segurança:</label>
            <select name="pergunta_seguranca" id="pergunta_seguranca" required>
                <option value="" disabled selected>Selecione uma pergunta...</option>
                <option value="Qual é o nome da sua mãe?">Qual é o nome da sua mãe?</option>
                <option value="Qual é o nome do seu animal de estimação?">Qual é o nome do seu animal de estimação?</option>
                <option value="Qual é a sua cidade natal?">Qual é a sua cidade natal?</option>
                <!-- Adicione mais opções de perguntas aqui -->
            </select><br>

            <label>Resposta de Segurança:</label>
            <input type="text" name="resposta_seguranca" required><br>
            
            <input type="submit" value="Cadastrar">
        </form>
    </div>
</body>
</html>
