<!DOCTYPE html>
<html>
<head>
    <title>Excluir Aluno</title>
    <link rel="stylesheet" type="text/css" href="StylePaginaDeletarAlunos.css">
</head>
<body>
    <div class="content">
        <div class="search-section">
            <h1>Buscar Aluno</h1>
            <form method="post" action="">
                <label for="aluno_nome">Selecione um Aluno:</label>
                <select name="aluno_nome" required>
                    <option value="" disabled selected>Escolha um aluno</option>
                    <?php
                    session_start();
                    $conn = new mysqli("127.0.0.1", "root", "", "Academia");
                    if ($conn->connect_error) {
                        die("Conexão falhou: " . $conn->connect_error);
                    }
                    $select_all_query = "SELECT a.nome FROM alunos a
                                         INNER JOIN alunos_academias aa ON a.id = aa.aluno_id
                                         INNER JOIN academias ac ON aa.academia_id = ac.id
                                         WHERE ac.cnpj = ?";
                    $stmt = $conn->prepare($select_all_query);
                    $stmt->bind_param("s", $_SESSION["cnpj"]);
                    $stmt->execute();
                    $result_all = $stmt->get_result();
                    if ($result_all->num_rows > 0) {
                        while ($row_all = $result_all->fetch_assoc()) {
                            echo '<option value="' . $row_all['nome'] . '">' . $row_all['nome'] . '</option>';
                        }
                    }
                    $conn->close();
                    ?>
                </select>
                <br><input type="submit" value="Buscar Aluno">
            </form>
        </div>
        
        <div class="update-section">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['aluno_nome'])) {
                $aluno_nome = $_POST['aluno_nome'];
                $conn = new mysqli("127.0.0.1", "root", "", "Academia");
                if ($conn->connect_error) {
                    die("Conexão falhou: " . $conn->connect_error);
                }
                $select_query = "SELECT a.id, a.nome, a.email, a.telefone, a.data_nascimento, a.data_cadastro, a.objetivo FROM alunos a
                                 INNER JOIN alunos_academias aa ON a.id = aa.aluno_id
                                 INNER JOIN academias ac ON aa.academia_id = ac.id
                                 WHERE a.nome = ? AND ac.cnpj = ?";
                $stmt = $conn->prepare($select_query);
                $stmt->bind_param("ss", $aluno_nome, $_SESSION["cnpj"]);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $aluno_id = $row['id'];
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="aluno_id" value="' . $aluno_id . '">';
                    echo '<input type="hidden" name="aluno_nome" value="' . $aluno_nome . '"><br>';
                    echo '<label for="aluno_nome">Nome do Aluno Selecionado: ' . $aluno_nome . '</label><br><br>';
                    echo '<li><strong>Dados do Aluno:</li></strong><br>';
                    echo '<p><strong>Email:</strong> ' . $row['email'] . '</p>';
                    echo '<p><strong>Data de Nascimento:</strong> ' . $row['data_nascimento'] . '</p>';
                    echo '<p><strong>Telefone:</strong> ' . $row['telefone'] . '</p>';
                    echo '<p><strong>Data de Cadastro:</strong> ' . $row['data_cadastro'] . '</p>';
                    echo '<p><strong>Objetivo:</strong> ' . $row['objetivo'] . '</p>';
                    echo '<br><p><strong>Deseja realmente excluir esse aluno?</strong></p>';
                    echo '<input type="submit" name="confirmar" value="Confirmar Exclusão">';
                    echo '<a class="exit-button" href="/ProjetoAcademia/SistemaCompleto/SistemaInterno/MenuGerenciarAlunos/PaginaAlunos.php">Retornar ao Menu</a>';
                    echo '</form>';
                } else {
                    echo "Nenhum aluno encontrado com esse nome ou o aluno não pertence à academia logada.";
                }
                $conn->close();
            }
            
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['aluno_id']) && isset($_POST['confirmar'])) {
                $aluno_id = $_POST['aluno_id'];
                $conn = new mysqli("127.0.0.1", "root", "", "Academia");
                if ($conn->connect_error) {
                    die("Conexão falhou: " . $conn->connect_error);
                }
                $delete_query = "DELETE FROM alunos WHERE id=$aluno_id";
                if ($conn->query($delete_query) === TRUE) {
                    echo '<p class="success-message"><br>Aluno excluído com sucesso!</p>';
                } else {
                    echo '<p class="error-message">Erro ao excluir o aluno: ' . $conn->error . '</p>';
                }
                $conn->close();
            }
            ?>
        </div>
    </div>
</body>
</html>
