<?php
$db = new SQLite3('tarefas.db');

$db->exec("
    CREATE TABLE IF NOT EXISTS tarefas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        descricao TEXT NOT NULL,
        vencimento DATE NOT NULL,
        concluida INTEGER DEFAULT 0
    )
");

function h($v) {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}
?>
