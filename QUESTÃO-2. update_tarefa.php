<?php
require_once 'database.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $db->prepare("UPDATE tarefas SET concluida = 1 WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();
}

header("Location: index.php");
exit;
