<?php
$id = $_GET['id'];
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$separador="--..--";

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_set_charset("utf8");


mysqli_select_db($con,"ajax_demo");
$sql="SELECT Proveedor.Empresa, FCompra.NFra FROM FCompra INNER JOIN Proveedor ON Proveedor=Proveedor.Id WHERE FCompra.Id = '".$id."'";


$result = mysqli_query($con,$sql);

$env_csv = "";

while($row = mysqli_fetch_array($result)) {
    $env_csv .= $row['Empresa'].$separador;
    $env_csv .= $row['NFra']."_";
}
print $env_csv;
mysqli_close($con);
?>
