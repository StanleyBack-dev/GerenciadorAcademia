<?php
session_start();

// Conexão com o banco de dados
$conn = new mysqli("127.0.0.1", "root", "", "Academia");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// CNPJ da academia logada
$cnpj_academia = $_SESSION['cnpj'];

// Consulta para buscar os dados dos alunos relacionados à academia logada
$query = "SELECT alunos.id, alunos.nome, DATE_FORMAT(alunos.data_nascimento, '%Y-%m-%d') AS data_nascimento, alunos.email, alunos.telefone, DATEDIFF(NOW(), alunos.data_cadastro) AS dias_cadastro, alunos.objetivo
FROM alunos
INNER JOIN alunos_academias ON alunos.id = alunos_academias.aluno_id
INNER JOIN academias ON alunos_academias.academia_id = academias.id
WHERE academias.cnpj = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $cnpj_academia);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Alunos</title>
    <link rel="stylesheet" type="text/css" href="StylePaginaListaAlunos.css">
</head>
<body>
    <div class="sidebar">
        <!-- Seu menu aqui -->
    </div>

    <div class="content">
        <h1>Lista de Alunos</h1>
        <table>
            <tr>
                <th>Nome</th>
                <th>Idade</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Dias de Cadastro</th>
                <th>Objetivo</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['nome']; ?></td>
                    <td><?php echo calculateAge($row['data_nascimento']); ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['telefone']; ?></td>
                    <td><?php echo $row['dias_cadastro']; ?></td>
                    <td><?php echo $row['objetivo']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

<?php
// Função para calcular a idade com base na data de nascimento
function calculateAge($birthDate) {
    $today = new DateTime();
    $birthdate = new DateTime($birthDate);
    $interval = $today->diff($birthdate);
    return $interval->y;
}

// Fechar o statement e a conexão com o banco de dados
$stmt->close();
$conn->close();
?>
