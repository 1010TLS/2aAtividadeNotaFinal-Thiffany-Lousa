<?php
require_once 'database.php';

// Listar tarefas
$result = $db->query("SELECT * FROM tarefas ORDER BY vencimento ASC");
$tarefas_nao_concluidas = [];
$tarefas_concluidas = [];

while ($t = $result->fetchArray(SQLITE3_ASSOC)) {
    if ($t['concluida']) $tarefas_concluidas[] = $t;
    else $tarefas_nao_concluidas[] = $t;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Gerenciador de Tarefas</title>
<style>
body {
    background: #fff0f5; /* rosa pastel */
    font-family: "Poppins", sans-serif;
    margin: 0;
    padding: 0;
    color: #5b4b5a;
    display: flex;
    justify-content: center;
}

/* Container geral */
.container {
    width: 90%;
    max-width: 1200px;
    display: flex;
    gap: 20px;
    margin: 20px auto;
}

/* Lista de tarefas */
.lista-tarefas {
    flex: 2;
    background: #fffbea; /* amarelo pastel */
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 5px 15px rgba(255, 182, 193, 0.3);
}

/* Formulário */
.form-container {
    flex: 1;
    background: #ffe0f0; /* rosa pastel mais claro */
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 5px 15px rgba(255, 182, 193, 0.2);
    height: fit-content;
}

/* Títulos */
.titulo { text-align: center; font-size: 32px; color: #ff6f91; margin-bottom: 20px; }
h2 { color: #ff6f91; margin-top: 20px; }

/* Botões */
.btn {
    padding: 8px 14px;
    border-radius: 12px;
    text-decoration: none;
    color: #fff;
    display: inline-block;
    margin: 2px 0;
    font-size: 14px;
    font-weight: 500;
    transition: 0.3s;
}
.btn-add { background: #ffd3b6; color: #5b4b5a; }
.btn-add:hover { background: #ffb347; color: #fff; }
.btn-concluir { background: #a8e6cf; color: #5b4b5a; }
.btn-concluir:hover { background: #4caf50; color: #fff; }
.btn-del { background: #ffaaa5; color: #5b4b5a; }
.btn-del:hover { background: #f44336; color: #fff; }

/* Tabelas */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    border-radius: 12px;
    overflow: hidden;
}
th, td {
    padding: 12px;
    border-bottom: 1px solid #ffe0e0;
    text-align: left;
}
th { background: #fff1f2; }

/* Form */
input[type="text"], input[type="date"] {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 12px;
    border: 1px solid #ffd3b6;
    font-size: 14px;
    background: #fffaf0;
}
form label { display: block; margin-bottom: 15px; font-weight: 500; }

/* GIF */
#gif {
    text-align: center;
    margin-top: 20px;
    display: none;
    animation: fadein 0.5s, fadeout 0.5s 2.5s;
}
@keyframes fadein { from {opacity:0;} to {opacity:1;} }
@keyframes fadeout { from {opacity:1;} to {opacity:0;} }
</style>
<script>
function confirmarExclusao() { return confirm("Excluir esta tarefa?"); }
</script>
</head>
<body>

<div class="container">

    <!-- Esquerda -->
    <div class="lista-tarefas">
        <h1 class="titulo">Tarefas</h1>

        <h2>Pendentes</h2>
        <?php if ($tarefas_nao_concluidas): ?>
        <table>
            <tr><th>Descrição</th><th>Vencimento</th><th>Ações</th></tr>
            <?php foreach ($tarefas_nao_concluidas as $t): ?>
            <tr>
                <td><?= h($t['descricao']) ?></td>
                <td><?= h($t['vencimento']) ?></td>
                <td>
                    <a class="btn btn-concluir" href="update_tarefa.php?id=<?= $t['id'] ?>">Concluir</a>
                    <a class="btn btn-del" href="delete_tarefa.php?id=<?= $t['id'] ?>" onclick="return confirmarExclusao();">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <p>Nenhuma tarefa pendente.</p>
        <?php endif; ?>

        <h2>Concluídas</h2>
        <?php if ($tarefas_concluidas): ?>
        <table>
            <tr><th>Descrição</th><th>Vencimento</th><th>Ações</th></tr>
            <?php foreach ($tarefas_concluidas as $t): ?>
            <tr>
                <td><?= h($t['descricao']) ?></td>
                <td><?= h($t['vencimento']) ?></td>
                <td>
                    <a class="btn btn-del" href="delete_tarefa.php?id=<?= $t['id'] ?>" onclick="return confirmarExclusao();">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <p>Nenhuma tarefa concluída.</p>
        <?php endif; ?>
    </div>

    <!-- Direita -->
    <div class="form-container">
        <h2>Adicionar Nova Tarefa</h2>
        <form method="POST" action="add_tarefa.php">
            <label>Descrição da Tarefa
                <input type="text" name="descricao" required>
            </label>
            <label>Data de Vencimento
                <input type="date" name="vencimento" required>
            </label>
            <button type="submit" class="btn btn-add">Adicionar</button>
        </form>

        <?php if (isset($_GET['added'])): ?>
        <div id="gif-sucesso">
            <img src="https://media.tenor.com/Gz408T11T8gAAAAi/wiggle-cat-wiggle.gif" 
                 alt="Tarefa adicionada!" style="max-width:180px;">
        </div>
        <script>
            document.getElementById('gif-sucesso').style.display = 'block';
            setTimeout(function() {
                var gif = document.getElementById('gif-sucesso');
                if(gif) gif.style.display = 'none';
            }, 3000);
        </script>
        <?php endif; ?>

    </div>

</div>

</body>
</html>
