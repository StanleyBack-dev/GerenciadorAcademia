<?php
session_start();

if (!isset($_SESSION['username']) && !isset($_SESSION['cnpj'])) {
    header("Location: ../MenuInicial/PaginaLogin.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gerenciador de Academia</title>
    <link rel="stylesheet" type="text/css" href="StyleSisteMenuIni.css">
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
</head>
<body>
    <!-- Menu lateral -->
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="#">Página Inicial</a></li>
            <li><a href="../SistemaInterno/MenuPerfil/PaginaPerfil.php">Perfil</a></li>
            <li><a href="#">Treinos</a></li>
            <li><a href="../SistemaInterno/MenuGerenciarAlunos/PaginaLoginGerenciamentoAlunos.php">Gerenciar Alunos</a></li>
            <li><a href="../SistemaInterno/MenuCadastrarAcademias/PaginaCadastrarAcademias.php">Cadastrar Academias</a></li>
            <li><a href="#">Relatórios</a></li>
            <li><a class="exit-button" href="../MenuInicial/PaginaLogin.php?action=logout">Sair</a></li>
        </ul>
    </div>

    <!-- Conteúdo principal -->
    <div class="content">
        <h1>Style Fit - Gerenciador de Academia</h1>
        
    </div>
</body>
</html>
