<!DOCTYPE html>
<html>
<head>
    <title>Recuperação de Senha</title>
    <link rel="stylesheet" type="text/css" href="StyleRecuperarSenha.css">
</head>
<body>
    <div class="container">
        <div class="title-container">
            <h1>Recuperação de Senha</h1>
        </div>
        
        <!-- Formulário de recuperação de senha -->
        <form method="post" action="">
            <label>Nome de Usuário:</label>
            <input type="text" name="nome_usuario" required><br>

            <label>Data de Nascimento:</label>
            <input type="date" name="data_nascimento" required><br>

            <label>Pergunta de Segurança:</label>
            <select name="pergunta_seguranca" required>
                <option value="" disabled selected>Selecione uma pergunta...</option>
                <option value="Qual é o nome da sua mãe?">Qual é o nome da sua mãe?</option>
                <option value="Qual é o nome do seu animal de estimação?">Qual é o nome do seu animal de estimação?</option>
                <option value="Qual é a sua cidade natal?">Qual é a sua cidade natal?</option>
                <!-- Adicione mais opções de perguntas aqui -->
            </select><br>

            <label>Resposta de Segurança:</label>
            <input type="text" name="resposta_seguranca" required><br>
            
            <input type="submit" value="Recuperar Senha">
        </form>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nomeUsuario = $_POST["nome_usuario"];
            $dataNascimento = $_POST["data_nascimento"];
            $perguntaSeguranca = $_POST["pergunta_seguranca"];
            $respostaSeguranca = $_POST["resposta_seguranca"];

            // Conexão com o banco de dados
            $conn = new mysqli("127.0.0.1", "root", "", "Academia");

            if ($conn->connect_error) {
                die("Erro de conexão: " . $conn->connect_error);
            }

            $query = "SELECT * FROM usuarios WHERE nome = '$nomeUsuario' AND data_nascimento = '$dataNascimento' AND pergunta_seguranca = '$perguntaSeguranca' AND resposta_seguranca = '$respostaSeguranca'";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                // Redirecionamento para a página de atualização de senha
                header("Location: PaginaAtualizarSenha.php?nome_usuario=$nomeUsuario");
                exit;
            } else {
                echo "<div class='error-message'>Dados incorretos. Verifique suas respostas de segurança.</div>";
            }

            $conn->close();
        }
        ?>
    </div>
</body>
</html>
