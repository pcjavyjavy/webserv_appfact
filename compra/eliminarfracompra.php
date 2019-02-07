<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$id = $_GET['id'];
$tabla = "FCompra";
$tablab = "Compra";

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}


mysqli_set_charset("utf8");

mysqli_select_db($con,"ajax_demo");

$sql="DELETE FROM $tabla WHERE Id='$id'";
$result = mysqli_query($con,$sql);


$sql="DELETE FROM $tablab WHERE Compra='$id'";
$result = mysqli_query($con,$sql);

print $result;

mysqli_close($con);
?>
