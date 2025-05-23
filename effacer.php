<?php 

$id = $_GET['id'];
include_once "connexion.php";

$id = mysqli_real_escape_string($con, $id);

$req = mysqli_query($con, "DELETE FROM images WHERE id='$id'");

header("Location: categorie.php");
exit;
?>