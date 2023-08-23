<!DOCTYPE html>
<html>
<head>
    <title>Atualização de Senha</title>
    <link rel="stylesheet" type="text/css" href="StyleAtualizarSenha.css">
</head>
<body>
    <div class="container">
        <div class="title-container">
            <h1>Atualização de Senha</h1>
        </div>
        <div class="info-message">
            <!-- Coloque aqui a mensagem de informação que deseja exibir -->
            . Senha deve conter pelo menos 8 caracteres<br>. Incluindo uma letra maiúscula<br>. Um número<br>. Um caractere especial.<br><br>
        </div>
        
        <!-- Formulário de atualização de senha -->
        <form method="post" action="">
            <label>Nome de Usuário:</label>
            <input type="text" name="nome_usuario" required><br> <!-- Adicione este campo -->
            
            <label>Nova Senha:</label>
            <input type="password" name="nova_senha" required><br>

            <label>Confirmar Nova Senha:</label>
            <input type="password" name="confirmar_nova_senha" required><br>
            
            <input type="submit" value="Atualizar Senha">
        </form>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nomeUsuario = $_POST["nome_usuario"]; // Obtém o nome de usuário digitado
            $novaSenha = $_POST["nova_senha"];
            $confirmarNovaSenha = $_POST["confirmar_nova_senha"];
        
            if ($novaSenha === $confirmarNovaSenha) {
                // Conexão com o banco de dados
                $conn = new mysqli("127.0.0.1", "root", "", "Academia");
        
                if ($conn->connect_error) {
                    die("Erro de conexão: " . $conn->connect_error);
                }
        
                // Gera o hash seguro da nova senha
                $hashNovaSenha = password_hash($novaSenha, PASSWORD_BCRYPT);
        
                // Preparação da instrução SQL usando placeholders
                $query = "UPDATE usuarios SET senha = ? WHERE nome = ?";
                $stmt = $conn->prepare($query);
        
                // Substituição dos placeholders pelos valores
                $stmt->bind_param("ss", $hashNovaSenha, $nomeUsuario);
        
                if ($stmt->execute()) {
                    // Redirecionamento para a página de login
                    header("Location: PaginaLogin.php");
                    exit;
                } else {
                    echo "<div class='error-message'>Erro ao atualizar a senha. Tente novamente.</div>";
                }
        
                $stmt->close();
                $conn->close();
            } else {
                echo "<div class='error-message'>As senhas não coincidem. Digite novamente.</div>";
            }
        }        
        ?>
    </div>
</body>
</html>
