<?php
session_start();

include('../../config/conexao.php');  // Ajuste o caminho para conexão
include('../../config/base.php');      // Ajuste o caminho para constantes base

if (!isset($_SESSION['login']['auth'])) {
    header("Location: " . BASE_ADMIN . 'login.php');
    exit();
}

if (!isset($_POST['id']) || empty($_POST['id'])) {
    $_SESSION['mensagem'] = "ID do usuário não informado.";
    header("Location: ../usuarios/listar-usuarios.php");
    exit();
}

$id = (int)$_POST['id'];

// Verifica se o usuário existe
$sql = "SELECT * FROM usuarios WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    $_SESSION['mensagem'] = "Usuário não encontrado.";
    header("Location: ../usuarios/listar-usuarios.php");
    exit();
}

// Executa a exclusão
$sqlDelete = "DELETE FROM usuarios WHERE id = :id";
$stmtDelete = $pdo->prepare($sqlDelete);

if ($stmtDelete->execute([':id' => $id])) {
    $_SESSION['mensagem'] = "Usuário excluído com sucesso!";
} else {
    $_SESSION['mensagem'] = "Erro ao excluir o usuário.";
}

header("Location: ../usuarios/listar-usuarios.php");
exit();
