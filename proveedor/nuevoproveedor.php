<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$empresa = $_GET['emp'];
$cif = $_GET['cif'];
$telefono = $_GET['tel'];
$pais = $_GET['pais'];
$provincia = $_GET['prov'];
$pueblo = $_GET['pob'];
$cp = $_GET['cp'];
$direccion = $_GET['dir'];
$web = $_GET['web'];
$notas = $_GET['nota'];
$email = $_GET['mail'];
$proveedores = "Proveedor";
$eliminados = "Delete_Proveedor";

$pais = str_replace(":",",",$pais);
$provincia = str_replace(":",",",$provincia);
$pueblo = str_replace(":",",",$pueblo);

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_set_charset("utf8");

mysqli_select_db($con,"ajax_demo");
$sql="SELECT MAX(Id) AS maximo from $proveedores";
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


$sql="INSERT INTO $proveedores (Id, Empresa, CIF, Telefono, Pais, Provincia, Poblacion, CP, Direccion, Web, Notas, Mail) VALUES ($id,'$empresa','$cif','$telefono','$pais','$provincia','$pueblo','$cp','$direccion','$web','$notas','$email')";
$result = mysqli_query($con,$sql);

print $result;

mysqli_close($con);
?>
