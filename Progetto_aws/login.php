<?php
include "connect.php";

if(isset($_POST['username']) && isset($_POST['password'])){
    $username = $_POST['username'];
    $password = md5($_POST['password']);
	$sql = "SELECT * FROM utenti WHERE username = '$username' 
			AND password = '$password'";
	$res = $conn -> query($sql);
	if($res -> num_rows > 0){
		header("Location: home.php");
	}else{
		echo "<script>";
        echo "var message = 'Utente non trovato';";
        echo "alert(message);";
        echo "window.location.href = 'login.php';";
        echo "</script>";
	}
}
?>
