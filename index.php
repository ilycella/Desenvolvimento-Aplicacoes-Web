<?php
$arquivo = "perguntas.txt";

// 7. EXCLUIR Pergunta e respostas
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

// 1, 2, 3 e 4. CRIAR E ALTERAR (Múltipla e Texto)
if (isset($_POST['btn_salvar'])) {
 $pergunta = $_POST['pergunta'];
 $tipo = $_POST['tipo'];
 
 if ($tipo == "Multipla Escolha") {
 // Garante que as 4 opções sejam salvas
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

// 6. LISTAR UMA Pergunta (para edição)
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
 <meta charset="UTF-8">
 <title>Sistema Sr. Water Falls</title>
 <style>
 body { font-family: sans-serif; background: #f0f0f0; padding: 20px; }
 .box { background: white; max-width: 600px; margin: auto; padding: 20px; border-radius: 8px; border: 1px solid #ccc; }
 .nav { background: #333; padding: 10px; margin-bottom: 20px; text-align: center; border-radius: 5px; }
 .nav a { color: white; text-decoration: none; font-weight: bold; margin: 0 10px; }
 input, select { width: 100%; padding: 8px; margin: 10px 0; display: block; box-sizing: border-box; }
 .btn { background: #0056b3; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; }
 .card { border: 1px solid #ddd; padding: 10px; margin-top: 10px; border-left: 5px solid #0056b3; }
 .opcoes-multipla { background: #f9f9f9; padding: 10px; border: 1px dashed #ccc; }
 </style>
 <script>
 function alternarCampos() {
 var tipo = document.getElementById("tipo").value;
 document.getElementById("area-multipla").style.display = (tipo === "Multipla Escolha") ? "block" : "none";
 document.getElementById("area-texto").style.display = (tipo === "Texto Livre") ? "block" : "none";
 }
 </script>
</head>
<body onload="alternarCampos()">

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
