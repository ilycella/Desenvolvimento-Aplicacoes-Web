<?php

if(file_exists("perguntas.txt")) {
 echo "<h1>Conteúdo do Arquivo TXT:</h1>";
 echo "<pre>" . file_get_contents("perguntas.txt") . "</pre>";
} else {
 echo "O arquivo ainda não foi criado.";
}
?>
