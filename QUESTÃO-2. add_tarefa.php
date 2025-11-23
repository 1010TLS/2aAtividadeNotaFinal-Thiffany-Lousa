<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = trim($_POST['descricao'] ?? '');
    $vencimento = trim($_POST['vencimento'] ?? '');

    if ($descricao && $vencimento) {
        $stmt = $db->prepare("INSERT INTO tarefas (descricao, vencimento) VALUES (:d, :v)");
        $stmt->bindValue(':d', $descricao, SQLITE3_TEXT);
        $stmt->bindValue(':v', $vencimento, SQLITE3_TEXT);
        $stmt->execute();
    }
}

header("Location: index.php?added=1");
exit;
?>
