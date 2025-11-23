<?php
$db = new SQLite3('livraria.db');

$db->exec("
    CREATE TABLE IF NOT EXISTS livros (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        titulo TEXT NOT NULL,
        autor TEXT NOT NULL,
        ano INTEGER NOT NULL
    )
");

function h($v) {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}

$action = $_GET['action'] ?? 'list';

if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $autor  = trim($_POST['autor'] ?? '');
    $ano    = trim($_POST['ano'] ?? '');

    if ($titulo && $autor && $ano) {
        $stmt = $db->prepare("INSERT INTO livros (titulo, autor, ano) VALUES (:t, :a, :n)");
        $stmt->bindValue(':t', $titulo, SQLITE3_TEXT);
        $stmt->bindValue(':a', $autor, SQLITE3_TEXT);
        $stmt->bindValue(':n', $ano, SQLITE3_INTEGER);
        $stmt->execute();
    }

    header("Location: index.php");
    exit;
}

if ($action === 'update' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titulo = trim($_POST['titulo']);
        $autor  = trim($_POST['autor']);
        $ano    = trim($_POST['ano']);

        $stmt = $db->prepare("UPDATE livros SET titulo = :t, autor = :a, ano = :n WHERE id = :id");
        $stmt->bindValue(':t', $titulo, SQLITE3_TEXT);
        $stmt->bindValue(':a', $autor, SQLITE3_TEXT);
        $stmt->bindValue(':n', $ano, SQLITE3_INTEGER);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();

        header("Location: index.php");
        exit;
    }

    $stmt = $db->prepare("SELECT * FROM livros WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $livro = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $db->prepare("DELETE FROM livros WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Banco de Dados Livraria</title>
<style>
body {
    background: #e8f1ff;
    font-family: "Poppins", sans-serif;
    margin: 0;
    padding: 0;
    color: #3d4a63;
    display: flex;
    justify-content: center;
}

.container {
    width: 80%;
    max-width: 900px;
    margin: 40px auto;
    background: #ffffff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 0 10px #c6d6ff;
}

.titulo { text-align: center; font-size: 32px; color: #3674e0; margin-bottom: 25px; }
.btn { padding: 10px 18px; border-radius: 10px; text-decoration: none; color: #fff; display: inline-block; }
.btn-azul { background: #5b8dff; }
.btn-cinza { background: #9baac4; }
.btn-del  { background: #ff6b6b; }
.btn-edit { background: #00b894; }

.form { width: 70%; margin: 0 auto; }
.form label { display: block; margin-bottom: 15px; font-weight: 500; }
.form input { width: 100%; padding: 12px; border: 2px solid #b9ccff; border-radius: 10px; margin-top: 5px; font-size: 15px; }

table { width: 100%; border-collapse: collapse; text-align: center; margin-top: 20px; }
table th, table td { padding: 12px; border-bottom: 1px solid #aac5ff; }
table th { background: #dae6ff; font-size: 15px; }
td a { margin: 0 3px; }

.excluir-id-box { margin-top: 30px; padding: 15px; background: #f5f9ff; border-radius: 10px; border: 2px solid #c9d8ff; }
</style>
<script>
function confirmarExclusao() {
    return confirm("Excluir este livro?");
}
</script>
</head>
<body>
<div class="container">

<?php if ($action === 'list'): ?>
    <h1 class="titulo">Livraria</h1>
    <a class="btn btn-azul" href="?action=create">+ Novo Livro</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Ano</th>
            <th>Ações</th>
        </tr>
        <?php
        $result = $db->query("SELECT * FROM livros ORDER BY titulo ASC");
        while ($livro = $result->fetchArray(SQLITE3_ASSOC)):
        ?>
        <tr>
            <td><?= h($livro['id']) ?></td>
            <td><?= h($livro['titulo']) ?></td>
            <td><?= h($livro['autor']) ?></td>
            <td><?= h($livro['ano']) ?></td>
            <td>
                <a class="btn btn-edit" href="?action=update&id=<?= $livro['id'] ?>">Editar</a>
                <a class="btn btn-del" onclick="return confirmarExclusao();" href="?action=delete&id=<?= $livro['id'] ?>">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="excluir-id-box">
        <h3>Excluir livro pelo ID</h3>
        <form method="GET">
            <input type="hidden" name="action" value="delete">
            <label>ID do Livro:
                <input type="number" name="id" required>
            </label>
            <button class="btn btn-del" onclick="return confirmarExclusao();">Excluir por ID</button>
        </form>
    </div>

<?php elseif ($action === 'create'): ?>
    <h1 class="titulo">Novo Livro</h1>
    <form method="POST" action="?action=save" class="form">
        <label>Título <input type="text" name="titulo" required></label>
        <label>Autor <input type="text" name="autor" required></label>
        <label>Ano de Publicação <input type="number" name="ano" required></label>
        <button class="btn btn-azul">Salvar</button>
        <a class="btn btn-cinza" href="?">Cancelar</a>
    </form>

<?php elseif ($action === 'update'): ?>
    <h1 class="titulo">Editar Livro</h1>
    <form method="POST" action="?action=update&id=<?= $livro['id'] ?>" class="form">
        <label>Título <input type="text" name="titulo" value="<?= h($livro['titulo']) ?>" required></label>
        <label>Autor <input type="text" name="autor" value="<?= h($livro['autor']) ?>" required></label>
        <label>Ano de Publicação <input type="number" name="ano" value="<?= h($livro['ano']) ?>" required></label>
        <button class="btn btn-azul">Salvar Alterações</button>
        <a class="btn btn-cinza" href="?">Cancelar</a>
    </form>
<?php endif; ?>

</div>
</body>
</html>
