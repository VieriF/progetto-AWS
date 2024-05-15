<?php
$host = "52.90.91.231";
$utente = "root";
$password = "";
$nome = "aws";
$porta = 3306;

$conn = new mysqli($host,$utente,$password,$nome,$porta);

if($conn -> error){
	echo "Errore nella connessione";
}else{
    //echo "Connesso";
}
?>
