<?php 

$con = mysqli_connect("localhost", "root", "", "immobilier");
if (!$con) {
    die('Error:' . mysqli_connect_error());
}
?>