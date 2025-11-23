<?php
require 'database.php';

// ⋆⁺₊⋆ DELETE (REMOVER LIVRO) ⋆⁺₊⋆
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $db->prepare("DELETE FROM livros WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();
}

header("Location: index.php");
exit;
