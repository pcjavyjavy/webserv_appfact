<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$cantidad = $_GET['can'];
$desecho = $_GET['des'];
$id = $_GET['id'];
$stock = 'Stock';

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_set_charset("utf8");
mysqli_select_db($con,"ajax_demo");
if($desecho) {
    $sql="UPDATE $stock SET Cantidad=Cantidad-$desecho WHERE Id=$id";
    $result = mysqli_query($con,$sql);
} else {
    $sql="UPDATE $stock SET Cantidad=$cantidad WHERE Id=$id";
    $result = mysqli_query($con,$sql);
}



print $result;

mysqli_close($con);
?>
