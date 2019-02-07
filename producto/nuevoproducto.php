<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$producto = $_GET['pro'];
$descripcion = $_GET['des'];
$productos = "Producto";
$eliminados = "Delete_Producto";

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_set_charset("utf8");

mysqli_select_db($con,"ajax_demo");
$sql="SELECT MAX(Id) AS maximo from $productos";
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

$idcli=intval($env_csv)+1;


$sql="SELECT MAX(Id) AS maximo from $eliminados";
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

$iddel=intval($env_csv)+1;

$id=0;

if($idcli > $iddel) {
    $id = $idcli;
} else {
    $id = $iddel;
}

$sql="INSERT INTO $productos (Id, Producto, Tipo, Descripcion) VALUES ($id,'$producto','M','$descripcion')";
$result = mysqli_query($con,$sql);

print $result;

mysqli_close($con);
?>
