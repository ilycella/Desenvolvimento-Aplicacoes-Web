<?php
$arquivoUsuarios = "usuarios.txt";

if (isset($_GET['excluir'])) {
$id = $_GET['excluir'];
$linhas = file($arquivoUsuarios);
unset($linhas[$id]);
file_put_contents($arquivoUsuarios, implode("", $linhas));
header("Location: usuarios.php");
}

if (isset($_POST['btn_salvar_user'])) {
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha']; 
$linhaUser = $nome . " | " . $email . " | " . $senha . PHP_EOL;

if ($_POST['id_editar'] != "") {
$linhas = file($arquivoUsuarios);
$linhas[$_POST['id_editar']] = $linhaUser;
file_put_contents($arquivoUsuarios, implode("", $linhas));
} else {
file_put_contents($arquivoUsuarios, $linhaUser, FILE_APPEND);
}
header("Location: usuarios.php");
}

$n_edit = ""; $e_edit = ""; $s_edit = ""; $id_edit = "";
if (isset($_GET['editar'])) {
$id_edit = $_GET['editar'];
$linhas = file($arquivoUsuarios);
$colunas = explode(" | ", $linhas[$id_edit]);
$n_edit = $colunas[0];
$e_edit = $colunas[1];
$s_edit = trim($colunas[2]);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
</head>
<body>

<div class="nav">
 <a href="index.php">GERENCIAR PERGUNTAS</a>
 <a href="usuarios.php" style="background: #28a745; padding: 5px 10px; border-radius: 3px;">CADASTRAR USUÁRIOS</a>
</div>

<div class="container">
<h2><?php echo ($id_edit !== "") ? "Editar Usuário" : "Novo Usuário"; ?></h2>

<form method="POST">
<input type="hidden" name="id_editar" value="<?php echo $id_edit; ?>">

<label>Nome Completo:</label>
<input type="text" name="nome" value="<?php echo $n_edit; ?>" required>

<label>E-mail:</label>
<input type="email" name="email" value="<?php echo $e_edit; ?>" required>

<label>Senha:</label>
<input type="password" name="senha" value="<?php echo $s_edit; ?>" required>

<button type="submit" name="btn_salvar_user" class="btn">SALVAR USUÁRIO</button>
</form>

<hr>

<h3>Usuários Cadastrados:</h3>
<?php
if (file_exists($arquivoUsuarios)) {
$linhas = file($arquivoUsuarios);
foreach ($linhas as $num => $linha) {
$u = explode(" | ", $linha);
echo "<div class='item-user'>";
echo "<strong>$u[0]</strong> ($u[1])<br>";
echo "<a href='?editar=$num'>Editar</a> | <a href='?excluir=$num' style='color:red'>Excluir</a>";
echo "</div>";
}
} else {
echo "Nenhum usuário cadastrado.";
}
?>
</div>

</body>
</html>
