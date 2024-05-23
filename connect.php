<?php
$host = "localhost";
$utente = "root";
$password = "mariadb";
$nome = "site";
$porta = 3306;

$conn = new mysqli($host,$utente,$password,$nome,$porta);

if($conn -> error){
	echo "Errore nella connessione";
}else{
    //echo "Connesso";
}
?>
