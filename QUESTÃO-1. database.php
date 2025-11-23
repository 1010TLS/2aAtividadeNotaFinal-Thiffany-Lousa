<?php
// ⋆⁺₊⋆ ━━━━⊱༒︎ DATABASE / CONEXÃO SQLITE ༒︎⊰━━━━ ⋆⁺₊⋆
$db = new SQLite3('livraria.db');

$db->exec("
    CREATE TABLE IF NOT EXISTS livros (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        titulo TEXT NOT NULL,
        autor TEXT NOT NULL,
        ano INTEGER NOT NULL
    )
");

// ⋆⁺₊⋆ ━━━━⊱༒︎ FUNÇÃO DE ESCAPE ༒︎⊰━━━━ ⋆⁺₊⋆
function h($v) {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}
?>
