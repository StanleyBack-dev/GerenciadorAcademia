<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Academias</title>
    <link rel="stylesheet" type="text/css" href="StylePaginaCadastrarAcademias.css">
</head>
<body>
    <div class="content">
        <h1>Cadastro de Academias</h1>
        <?php
        $nome = $cnpj = $cidade = $estado = $cep = $senha = $pergunta_seguranca = $resposta_seguranca = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Coletar dados do formulário
            $nome = $_POST["nome"];
            $cnpj = $_POST["cnpj"];
            $cidade = $_POST["cidade"];
            $estado = $_POST["estado"];
            $cep = $_POST["cep"];
            $pergunta_seguranca = $_POST["pergunta_seguranca"];
            $resposta_seguranca = $_POST["resposta_seguranca"];

            // Conexão com o banco de dados (ajuste as credenciais)
            $conn = new mysqli("127.0.0.1", "root", "", "Academia");

            if ($conn->connect_error) {
                die("Erro de conexão: " . $conn->connect_error);
            }

            // Verificar se já existe academia com o mesmo CNPJ
            $check_query = "SELECT id FROM academias WHERE cnpj = ?";
            $stmt_check = $conn->prepare($check_query);
            $stmt_check->bind_param("s", $cnpj);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                echo "<p class='error-message'>Uma academia com este CNPJ já está cadastrada.</p>";
            } else {
                // Gerar senha aleatória
                $senha = generateRandomPassword();

            // Inserir dados no banco de dados
            $insert_query = "INSERT INTO academias (nome, cnpj, cidade, estado, cep, senha, pergunta_seguranca, resposta_seguranca) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);

            // Gere a senha aleatória
            $senha = generateRandomPassword();

            // Gere o hash da senha
            $senhaHash = generatePasswordHash($senha);

            $stmt->bind_param("ssssssss", $nome, $cnpj, $cidade, $estado, $cep, $senhaHash, $pergunta_seguranca, $resposta_seguranca);

                
                if ($stmt->execute()) {
                    echo "<p class='success-message'>Cadastro realizado com sucesso!</p>";
                    echo "<p class='success-message'>Sua senha de acesso é: $senha</p>";
                } else {
                    echo "<p class='error-message'>Erro ao cadastrar: " . $stmt->error . "</p>";
                }

                $stmt->close();
            }

            $stmt_check->close();
            $conn->close();
        }

            // Função para gerar senha aleatória
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

            // Função para gerar hash da senha
            function generatePasswordHash($password) {
            return password_hash($password, PASSWORD_DEFAULT);
            }
        ?>

        <form method="post" action="">
            <label for="nome">Nome da Academia:</label>
            <input type="text" name="nome" required><br>

            <label for="cnpj">CNPJ da Academia:</label>
            <input type="text" name="cnpj" required><br>

            <label for="cidade">Cidade:</label>
            <input type="text" name="cidade" required><br>

            <label for="estado">Estado:</label>
            <select name="estado" required>
            <option value="" disabled selected>Selecione o estado</option>
            <option value="AC">Acre (AC)</option>
            <option value="AL">Alagoas (AL)</option>
            <option value="AP">Amapá (AP)</option>
            <option value="AM">Amazonas (AM)</option>
            <option value="BA">Bahia (BA)</option>
            <option value="CE">Ceará (CE)</option>
            <option value="DF">Distrito Federal (DF)</option>
            <option value="ES">Espírito Santo (ES)</option>
            <option value="GO">Goiás (GO)</option>
            <option value="MA">Maranhão (MA)</option>
            <option value="MT">Mato Grosso (MT)</option>
            <option value="MS">Mato Grosso do Sul (MS)</option>
            <option value="MG">Minas Gerais (MG)</option>
            <option value="PA">Pará (PA)</option>
            <option value="PB">Paraíba (PB)</option>
            <option value="PR">Paraná (PR)</option>
            <option value="PE">Pernambuco (PE)</option>
            <option value="PI">Piauí (PI)</option>
            <option value="RJ">Rio de Janeiro (RJ)</option>
            <option value="RN">Rio Grande do Norte (RN)</option>
            <option value="RS">Rio Grande do Sul (RS)</option>
            <option value="RO">Rondônia (RO)</option>
            <option value="RR">Roraima (RR)</option>
            <option value="SC">Santa Catarina (SC)</option>
            <option value="SP">São Paulo (SP)</option>
            <option value="SE">Sergipe (SE)</option>
            <option value="TO">Tocantins (TO)</option>
            </select><br>

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

            <input type="submit" value="Cadastrar">
        </form>
        <a class="exit-button" href="../PaginaSisteMenuIni.php">Retornar ao Menu Inicial</a>
    </div>
</body>
</html>
