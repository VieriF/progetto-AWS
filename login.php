<?php
include "connect.php";

if(isset($_POST['email']) && isset($_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
	$sql = "SELECT * FROM utenti WHERE username = '$email' AND password = '$password'";
	$res = $conn -> query($sql);
	if($res -> num_rows > 0){
		header("Location: principale.php");
	}else{
		header("Location: index.php");
	}
}
?>
