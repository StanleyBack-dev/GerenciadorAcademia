<?php
// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aluno_id = $_POST['aluno_id'];
    $novo_nome = $_POST['novo_nome'];
    $novo_email = $_POST['novo_email'];
    $novo_telefone = $_POST['novo_telefone'];

    // Conexão com o banco de dados
    $conn = new mysqli("127.0.0.1", "root", "", "Academia");

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Constrói a query base
    $update_query = "UPDATE alunos SET";

    // Verifica quais campos devem ser atualizados e adiciona à query
    if (!empty($novo_nome)) {
        $update_query .= " nome='$novo_nome',";
    }

    if (!empty($novo_email)) {
        $update_query .= " email='$novo_email',";
    }

    if (!empty($novo_telefone)) {
        $update_query .= " telefone='$novo_telefone',";
    }

    // Remove a vírgula extra no final da query
    $update_query = rtrim($update_query, ',');

    // Adiciona a cláusula WHERE para atualizar apenas o aluno específico
    $update_query .= " WHERE id=$aluno_id";

    if ($conn->query($update_query) === TRUE) {
        echo "Dados atualizados com sucesso!";
    } else {
        echo "Erro ao atualizar os dados: " . $conn->error;
    }

    $conn->close();
}
?>
