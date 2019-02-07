<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$nfra = $_GET['nfra'];
$proveedor = $_GET['pro'];
$fyear = $_GET['year'];
$fmonth = $_GET['mes'];
$fday = $_GET['day'];
$notas = $_GET['nota'];
$fra_com = "FCompra";

$fecha=$fyear."-".$fmonth."-".$fday;

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_set_charset("utf8");

mysqli_select_db($con,"ajax_demo");
$sql="SELECT MAX(Id) AS maximo from $fra_com";
$result = mysqli_query($con,$sql);

$env_csv = "";

while($row = mysqli_fetch_array($result)) {
    $env_csv .= $row['maximo']."";
}

if(!$env_csv) {
    $env_csv = "0";
}

if($env_csv=='NULL') {
    $env_csv = "0";
}

$id=intval($env_csv)+1;


$sql="INSERT INTO $fra_com (Id, NFra, Proveedor, Fecha, Notas, Liquidado, Saldado) VALUES ($id,'$nfra','$proveedor','$fecha','$notas','N',0)";
$result = mysqli_query($con,$sql);

print $id;

mysqli_close($con);
?>
