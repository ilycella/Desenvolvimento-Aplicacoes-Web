<?php
$arquivoUsuarios = "usuarios.txt";


if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $linhas = file_exists($arquivoUsuarios) ? file($arquivoUsuarios) : [];

    if (isset($linhas[$id])) {
        unset($linhas[$id]);
        file_put_contents($arquivoUsuarios, implode("", $linhas));
    }

    header("Location: usuarios.php");
    exit;
}

if (isset($_POST['btn_salvar_user'])) {

    $nome = str_replace("|", "-", trim($_POST['nome']));
    $email = str_replace("|", "-", trim($_POST['email']));
    $senha = trim($_POST['senha']);


    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    if (!empty($nome) && !empty($email) && !empty($senha)) {

        $linhaUser = $nome . " | " . $email . " | " . $senhaHash . PHP_EOL;

        if ($_POST['id_editar'] !== "") {
            $linhas = file_exists($arquivoUsuarios) ? file($arquivoUsuarios) : [];
            $linhas[$_POST['id_editar']] = $linhaUser;
            file_put_contents($arquivoUsuarios, implode("", $linhas));
        } else {
            file_put_contents($arquivoUsuarios, $linhaUser, FILE_APPEND);
        }
    }

    header("Location: usuarios.php");
    exit;
}

$n_edit = ""; 
$e_edit = ""; 
$s_edit = ""; 
$id_edit = "";

if (isset($_GET['editar'])) {
    $id_edit = $_GET['editar'];
    $linhas = file_exists($arquivoUsuarios) ? file($arquivoUsuarios) : [];

    if (isset($linhas[$id_edit])) {
        $colunas = array_pad(explode(" | ", $linhas[$id_edit]), 3, "");

        $n_edit = $colunas[0];
        $e_edit = $colunas[1];
        $s_edit = ""; 
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Usuários </title>
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
            color: var(--kawaii-purple);
            text-decoration: none;
            font-weight: 800;
            padding: 12px 25px;
            border-radius: 50px;
            background: white;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .nav a:hover {
            background: var(--kawaii-purple);
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(205, 164, 255, 0.4);
        }

    
        .nav a.active { 
            background: var(--kawaii-pink); 
            color: white; 
            box-shadow: 0 8px 20px rgba(255, 133, 161, 0.4);
        }

        
        .container {
            width: 100%;
            max-width: 650px;
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

        input {
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

        input:focus {
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

        hr { 
            border: 0; 
            height: 3px; 
            background: linear-gradient(90deg, transparent, var(--kawaii-purple), transparent); 
            margin: 40px 0; 
            opacity: 0.5;
        }

        
        .item-user {
            background: white;
            padding: 20px 25px;
            border-radius: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            border: 2px solid var(--kawaii-light-pink);
            position: relative;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: popIn 0.5s ease backwards;
            display: flex;
            flex-direction: column;
        }

        .item-user:nth-child(1) { animation-delay: 0.1s; }
        .item-user:nth-child(2) { animation-delay: 0.2s; }
        .item-user:nth-child(3) { animation-delay: 0.3s; }

        .item-user:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 15px 30px rgba(255, 133, 161, 0.2);
            border-color: var(--kawaii-pink);
        }

        .user-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 5px;
        }

        .avatar-placeholder {
            width: 40px;
            height: 40px;
            background: var(--kawaii-light-purple);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--kawaii-purple);
            border: 2px solid white;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }

        .item-user strong {
            color: var(--text-dark);
            font-size: 1.2rem;
            font-weight: 800;
        }

        .item-user small {
            color: #888;
            font-size: 0.95rem;
            font-weight: 600;
            background: #f8f9fa;
            padding: 4px 12px;
            border-radius: 12px;
            align-self: flex-start;
            margin-bottom: 15px;
        }

        .card-actions {
            display: flex;
            gap: 12px;
            margin-top: auto;
        }

        .card-actions a {
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 800;
            padding: 8px 20px;
            border-radius: 12px;
            transition: 0.3s;
            flex: 1;
            text-align: center;
        }

        .btn-edit { background: var(--kawaii-light-purple); color: var(--kawaii-purple); }
        .btn-edit:hover { background: var(--kawaii-purple); color: white; }

        .btn-del { background: #ffe5e5; color: #ff5252; }
        .btn-del:hover { background: #ff5252; color: white; }

        
        @keyframes floatDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes floatUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes popIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
    </style>
</head>

<body>

<nav class="nav">
    <a href="index.php"> Gerenciar </a>
    <a href="usuarios.php" class="active">Usuários</a>
</nav>

<div class="container">

    <h2>
        <?php echo ($id_edit !== "") ? "<span>🪄</span> Editando Usuário #$id_edit" : "<span>🪄</span> Novo Usuário"; ?>
    </h2>

    <form method="POST">
        <input type="hidden" name="id_editar" value="<?php echo $id_edit; ?>">

        <label>Nome 🎀:</label>
        <input type="text" name="nome" value="<?php echo $n_edit; ?>" placeholder="Digite seu nome" required>

        <label>Email 💌:</label>
        <input type="email" name="email" value="<?php echo $e_edit; ?>" placeholder="Digite seu email" required>

        <label>Senha 🔐:</label>
        <input type="password" name="senha" placeholder="Digite sua senha" required>

        <button type="submit" name="btn_salvar_user" class="btn-heart">
             Salvar Usuário 
        </button>
    </form>

    <hr>

    <h3> Usuários salvos </h3>

    <?php
    $linhas = file_exists($arquivoUsuarios) ? file($arquivoUsuarios) : [];

    if (count($linhas) > 0) {
        foreach ($linhas as $num => $linha) {
            if (trim($linha) == "") continue;

            $u = array_pad(explode(" | ", $linha), 3, "");
            
          
            $inicial = strtoupper(substr($u[0], 0, 1));

            echo "<div class='item-user'>";
                echo "<div class='user-header'>";
                    echo "<div class='avatar-placeholder'>$inicial</div>";
                    echo "<strong>#{$num} • $u[0]</strong>";
                echo "</div>";
                echo "<small>💌 $u[1]</small>";
                
                echo "<div class='card-actions'>";
                    echo "<a href='?editar=$num' class='btn-edit'>Editar 🪄</a>";
                    echo "<a href='?excluir=$num' class='btn-del' onclick=\"return confirm('Tem certeza que quer remover esse usuário? ')\">Excluir ✖</a>";
                echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p style='text-align: center; color: var(--kawaii-purple); font-weight: bold;'>Nenhum usuário cadastrados! Cadastre alguém acima. </p>";
    }
    ?>

</div>

</body>
</html>
