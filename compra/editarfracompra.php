<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$id = $_GET['id'];
$nfra = $_GET['nfra'];
$proveedor = $_GET['pro'];
$day = $_GET['day'];
$mes = $_GET['mes'];
$year = $_GET['year'];
$notas = $_GET['nota'];
$tabla = "FCompra";
$fecha = $year."-".$mes."-"."$day";

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}


mysqli_set_charset("utf8");

mysqli_select_db($con,"ajax_demo");

$sql="UPDATE $tabla SET NFra='$nfra', Proveedor='$proveedor', Fecha='$fecha', Notas='$notas' WHERE Id='$id'";
$result = mysqli_query($con,$sql);

print $result;

mysqli_close($con);
?>
