<?php
$arquivo = "perguntas.txt";


if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $linhas = file_exists($arquivo) ? file($arquivo) : [];
    if (isset($linhas[$id])) {
        unset($linhas[$id]);
        file_put_contents($arquivo, implode("", $linhas));
    }
    header("Location: index.php");
    exit;
}

if (isset($_POST['btn_salvar'])) {
    $pergunta = str_replace("|", "-", trim($_POST['pergunta']));
    $tipo = $_POST['tipo'];
    if ($tipo == "Multipla Escolha") {
        $opcoes = [$_POST['o1'], $_POST['o2'], $_POST['o3'], $_POST['o4']];
        if (in_array("", $opcoes)) {
            echo "<script>alert('Preencha todas as opções, flor!');</script>";
        } else {
            $res = implode(", ", array_map(fn($o) => str_replace("|", "-", trim($o)), $opcoes));
        }
    } else {
        $res = str_replace("|", "-", trim($_POST['resposta_texto']));
    }

    if (!empty($pergunta) && !empty($res)) {
        $linhaFormatada = $pergunta . " | " . $tipo . " | " . $res . PHP_EOL;
        if ($_POST['id_editar'] !== "") {
            $linhas = file_exists($arquivo) ? file($arquivo) : [];
            $linhas[$_POST['id_editar']] = $linhaFormatada;
            file_put_contents($arquivo, implode("", $linhas));
        } else {
            file_put_contents($arquivo, $linhaFormatada, FILE_APPEND);
        }
    }
    header("Location: index.php");
    exit;
}

$p_edit = ""; $t_edit = ""; $r_edit = ""; $id_edit = ""; $opts = ["","","",""];
if (isset($_GET['editar'])) {
    $id_edit = $_GET['editar'];
    $linhas = file_exists($arquivo) ? file($arquivo) : [];
    if (isset($linhas[$id_edit])) {
        $colunas = array_pad(explode(" | ", trim($linhas[$id_edit])), 3, "");
        $p_edit = $colunas[0]; $t_edit = $colunas[1]; $r_edit = $colunas[2];
        if ($t_edit == "Multipla Escolha") { $opts = explode(", ", $r_edit); }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faça sua pergunta! 💖</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --kawaii-pink: #ff85a1;
            --kawaii-light-pink: #ffe5ec;
            --kawaii-purple: #cda4ff;
            --kawaii-light-purple: #f3e8ff;
            --text-dark: #6a4c93;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.4);
        }


        ::-webkit-scrollbar { width: 12px; }
        ::-webkit-scrollbar-track { background: var(--kawaii-light-pink); }
        ::-webkit-scrollbar-thumb { 
            background: var(--kawaii-pink); 
            border-radius: 20px; 
            border: 3px solid var(--kawaii-light-pink);
        }

        body {
            font-family: 'Nunito', sans-serif;
            color: var(--text-dark);
            margin: 0;
            min-height: 100vh;

            background: linear-gradient(-45deg, #ffc3a0, #ffafbd, #cda4ff, #e2e2e2);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }


        .nav {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 15px 30px;
            margin-top: 20px;
            border-radius: 50px;
            display: flex;
            gap: 15px;
            box-shadow: 0 8px 32px rgba(255, 133, 161, 0.2);
            border: 1px solid var(--glass-border);
            animation: floatDown 0.8s ease-out;
        }

        .nav a {
            color: var(--kawaii-pink);
            text-decoration: none;
            font-weight: 800;
            padding: 12px 25px;
            border-radius: 50px;
            background: white;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .nav a:hover {
            background: var(--kawaii-pink);
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(255, 133, 161, 0.4);
        }

        .nav a.users { color: var(--kawaii-purple); }
        .nav a.users:hover { background: var(--kawaii-purple); color: white; box-shadow: 0 8px 20px rgba(205, 164, 255, 0.4); }

       
        .box {
            width: 100%;
            max-width: 750px;
            margin: 30px 20px;
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 40px;
            border-radius: 40px;
            box-shadow: 0 10px 40px rgba(106, 76, 147, 0.15);
            border: 2px solid var(--glass-border);
            position: relative;
            animation: floatUp 0.8s ease-out;
        }

        h2, h3 {
            text-align: center;
            font-weight: 900;
            color: var(--text-dark);
            letter-spacing: -0.5px;
            margin-bottom: 30px;
        }

        h2 { font-size: 2.2rem; }
        
        h2 span { 
            display: inline-block; 
            animation: bounce 2s infinite; 
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

   
        label {
            font-weight: 800;
            margin-left: 15px;
            font-size: 0.95rem;
            color: var(--kawaii-pink);
            display: inline-block;
            margin-bottom: 5px;
        }

        input, select {
            width: 100%;
            padding: 16px 20px;
            margin-bottom: 25px;
            border: 2px solid transparent;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--text-dark);
            font-family: 'Nunito', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.02);
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--kawaii-purple);
            background: white;
            box-shadow: 0 0 15px rgba(205, 164, 255, 0.5);
            transform: scale(1.01);
        }

        input::placeholder { color: #babbc0; font-weight: 400; }

  
        .btn-heart {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, var(--kawaii-pink), #ff6b8b);
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 1.2rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(255, 133, 161, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            animation: heartbeat 2.5s infinite;
        }

        .btn-heart:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 15px 25px rgba(255, 133, 161, 0.5);
            animation: none; 
        }

        @keyframes heartbeat {
            0% { transform: scale(1); }
            10% { transform: scale(1.02); }
            20% { transform: scale(1); }
            30% { transform: scale(1.02); }
            40% { transform: scale(1); }
            100% { transform: scale(1); }
        }

      
        .area-container {
            overflow: hidden;
            transition: max-height 0.5s cubic-bezier(0, 1, 0, 1);
            max-height: 0;
        }
        .area-container.show {
            max-height: 500px; 
            transition: max-height 0.5s ease-in-out;
        }

        hr { 
            border: 0; 
            height: 3px; 
            background: linear-gradient(90deg, transparent, var(--kawaii-pink), transparent); 
            margin: 40px 0; 
            opacity: 0.5;
        }


        .card {
            background: white;
            padding: 25px;
            border-radius: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            border: 2px solid var(--kawaii-light-purple);
            position: relative;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: popIn 0.5s ease backwards;
        }


        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }

        .card:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 15px 30px rgba(205, 164, 255, 0.2);
            border-color: var(--kawaii-purple);
        }

        .card strong {
            color: var(--text-dark);
            font-size: 1.2rem;
            font-weight: 800;
        }

        .badge {
            display: inline-block;
            background: var(--kawaii-light-pink);
            color: var(--kawaii-pink);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 800;
            margin: 10px 0 15px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-actions {
            margin-top: 20px;
            display: flex;
            gap: 12px;
        }

        .card-actions a {
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 800;
            padding: 8px 20px;
            border-radius: 12px;
            transition: 0.3s;
        }

        .btn-edit { 
            background: var(--kawaii-light-purple); 
            color: var(--kawaii-purple); 
        }
        .btn-edit:hover { background: var(--kawaii-purple); color: white; }

        .btn-del { 
            background: #ffe5e5; 
            color: #ff5252; 
        }
        .btn-del:hover { background: #ff5252; color: white; }

        @keyframes floatDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes floatUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes popIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>

    <script>
        function alternarCampos() {
            let tipo = document.getElementById("tipo").value;
            let areaM = document.getElementById("area-multipla");
            let areaT = document.getElementById("area-texto");

            if (tipo === "Multipla Escolha") {
                areaM.classList.add("show");
                areaT.classList.remove("show");
            } else {
                areaM.classList.remove("show");
                areaT.classList.add("show");
            }
        }

        document.addEventListener("DOMContentLoaded", alternarCampos);
    </script>
</head>

<body>

<nav class="nav">
    <a href="index.php"> Gerenciar </a>
    <a href="usuarios.php" class="users">Usuários</a>
</nav>

<div class="box">
    <h2>
        <?php echo ($id_edit !== "") ? "<span>🪄</span> Editando a Pergunta #$id_edit" : "<span>🌸</span> Criar Nova Pergunta"; ?>
    </h2>

    <form method="POST">
        <input type="hidden" name="id_editar" value="<?php echo $id_edit; ?>">

        <label>Qual a sua pergunta?</label>
        <input type="text" name="pergunta" value="<?php echo $p_edit; ?>" placeholder="Ex: Qual sua cor favorita?" required>

        <label>Como vamos responder?</label>
        <select name="tipo" id="tipo" onchange="alternarCampos()">
            <option value="Multipla Escolha" <?php if($t_edit=="Multipla Escolha") echo "selected"; ?>> Múltipla Escolha</option>
            <option value="Texto Livre" <?php if($t_edit=="Texto Livre") echo "selected"; ?>> Texto Livre</option>
        </select>

        <div id="area-multipla" class="area-container">
            <label>Opções de escolha (Preencha as 4):</label>
            <input type="text" name="o1" placeholder="Opção 1 " value="<?php echo $opts[0] ?? ''; ?>">
            <input type="text" name="o2" placeholder="Opção 2 " value="<?php echo $opts[1] ?? ''; ?>">
            <input type="text" name="o3" placeholder="Opção 3 " value="<?php echo $opts[2] ?? ''; ?>">
            <input type="text" name="o4" placeholder="Opção 4 " value="<?php echo $opts[3] ?? ''; ?>">
        </div>

        <div id="area-texto" class="area-container">
            <label>Sua resposta :</label>
            <input type="text" name="resposta_texto" placeholder="Deixe sua pergunta aqui..." value="<?php echo ($t_edit == "Texto Livre") ? $r_edit : ""; ?>">
        </div>

        <button type="submit" name="btn_salvar" class="btn-heart">
             Salvar pergunta
        </button>
    </form>

    <hr>

    <h3> Perguntas salvas</h3>

    <?php
    $linhas = file_exists($arquivo) ? file($arquivo) : [];
    
    if (empty($linhas)) {
        echo "<p style='text-align: center; color: var(--kawaii-purple); font-weight: bold;'>Crie sua primeira pergunta acima. </p>";
    }

    foreach ($linhas as $index => $linha) {
        if (trim($linha) == "") continue;
        $dados = array_pad(explode(" | ", $linha), 3, "");

        $tipoBadge = ($dados[1] == "Multipla Escolha") ? " Múltipla Escolha" : " Texto Livre";

        echo "<div class='card'>";
        echo "<strong>#{$index} • $dados[0]</strong><br>";
        echo "<span class='badge'>$tipoBadge</span><br>";
        echo "<div style='color:#7b7b7b; font-weight: 600; line-height: 1.5;'>" . str_replace(",", " <span style='color: var(--kawaii-pink);'>✦</span> ", $dados[2]) . "</div>";
        echo "<div class='card-actions'>";
        echo "<a href='?editar=$index' class='btn-edit'>Editar 🪄</a>";
        echo "<a href='?excluir=$index' class='btn-del' onclick=\"return confirm('Tem certeza que quer apagar essa pergunta?')\">Excluir ✖</a>";
        echo "</div>";
        echo "</div>";
    }
    ?>
</div>

</body>
</html>
