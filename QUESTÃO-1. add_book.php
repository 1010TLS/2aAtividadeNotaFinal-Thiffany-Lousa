<?php
require 'database.php';

// ⋆⁺₊⋆ CREATE (INSERIR LIVRO) ⋆⁺₊⋆
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $autor  = trim($_POST['autor'] ?? '');
    $ano    = trim($_POST['ano'] ?? '');

    if ($titulo && $autor && $ano) {
        $stmt = $db->prepare("INSERT INTO livros (titulo, autor, ano) VALUES (:t,:a,:n)");
        $stmt->bindValue(':t', $titulo, SQLITE3_TEXT);
        $stmt->bindValue(':a', $autor, SQLITE3_TEXT);
        $stmt->bindValue(':n', $ano, SQLITE3_INTEGER);
        $stmt->execute();
    }
}

header("Location: index.php");
exit;
