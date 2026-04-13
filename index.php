<?php
$arquivo = "perguntas.txt";

if (isset($_GET['excluir'])) {
 $id = $_GET['excluir'];
 $linhas = file($arquivo);
 if (isset($linhas[$id])) {
 unset($linhas[$id]);
 file_put_contents($arquivo, implode("", $linhas));
 }
 header("Location: index.php");
 exit;
}

if (isset($_POST['btn_salvar'])) {
 $pergunta = $_POST['pergunta'];
 $tipo = $_POST['tipo'];
 
 if ($tipo == "Multipla Escolha") {
 $res = $_POST['o1'].", ".$_POST['o2'].", ".$_POST['o3'].", ".$_POST['o4'];
 } else {
 $res = $_POST['resposta_texto'];
 }
 
 $linhaFormatada = $pergunta . " | " . $tipo . " | " . $res . PHP_EOL;

 if ($_POST['id_editar'] !== "") {
 $linhas = file($arquivo);
 $linhas[$_POST['id_editar']] = $linhaFormatada;
 file_put_contents($arquivo, implode("", $linhas));
 } else {
 file_put_contents($arquivo, $linhaFormatada, FILE_APPEND);
 }
 header("Location: index.php");
 exit;
}

$p_edit = ""; $t_edit = ""; $r_edit = ""; $id_edit = ""; $opts = ["","","",""];
if (isset($_GET['editar'])) {
 $id_edit = $_GET['editar'];
 $linhas = file($arquivo);
 if (isset($linhas[$id_edit])) {
 $colunas = explode(" | ", trim($linhas[$id_edit]));
 $p_edit = $colunas[0];
 $t_edit = $colunas[1];
 $r_edit = $colunas[2];
 if($t_edit == "Multipla Escolha") {
 $opts = explode(", ", $r_edit);
 }
 }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
</head>

 <div class="nav">
 <a href="index.php">GERENCIAR PERGUNTAS</a>
 <a href="usuarios.php" style="background: #28a745; padding: 5px 10px; border-radius: 3px;">CADASTRAR USUÁRIOS</a>
 </div>

 <div class="box">
 <h2><?php echo ($id_edit !== "") ? "Alterar Pergunta #$id_edit" : "Criar Pergunta"; ?></h2>
 
 <form method="POST">
 <input type="hidden" name="id_editar" value="<?php echo $id_edit; ?>">

 <label>Texto da Pergunta:</label>
 <input type="text" name="pergunta" value="<?php echo $p_edit; ?>" required>

 <label>Tipo de Pergunta:</label>
 <select name="tipo" id="tipo" onchange="alternarCampos()">
 <option value="Multipla Escolha" <?php if($t_edit=="Multipla Escolha") echo "selected"; ?>>Múltipla Escolha</option>
 <option value="Texto Livre" <?php if($t_edit=="Texto Livre") echo "selected"; ?>>Texto Livre</option>
 </select>

 <div id="area-multipla">
 <label>Opções (mínimo 4):</label>
 <input type="text" name="o1" placeholder="Opção 1" value="<?php echo $opts[0] ?? ''; ?>">
 <input type="text" name="o2" placeholder="Opção 2" value="<?php echo $opts[1] ?? ''; ?>">
 <input type="text" name="o3" placeholder="Opção 3" value="<?php echo $opts[2] ?? ''; ?>">
 <input type="text" name="o4" placeholder="Opção 4" value="<?php echo $opts[3] ?? ''; ?>">
 </div>

 <div id="area-texto">
 <label>Resposta de Texto:</label>
 <input type="text" name="resposta_texto" value="<?php echo ($t_edit == "Texto Livre") ? $r_edit : ""; ?>">
 </div>

 <button type="submit" name="btn_salvar" class="btn">SALVAR PERGUNTA</button>
 </form>

 <hr>

 <h3>Perguntas Cadastradas</h3>
 <?php
 if (file_exists($arquivo)) {
 $linhas = file($arquivo);
 foreach ($linhas as $index => $linha) {
 if (trim($linha) == "") continue;
 $dados = explode(" | ", $linha);
 echo "<div class='card'>";
 echo "<strong>$dados[0]</strong> <br>";
 echo "<small>Tipo: $dados[1]</small><br>";
 echo "<em>Respostas: $dados[2]</em><br><br>";
 echo "<a href='?editar=$index'>[Alterar]</a> ";
 echo "<a href='?excluir=$index' style='color:red;'>[Excluir]</a>";
 echo "</div>";
 }
 }
 ?>
 </div>

</body>
</html>
