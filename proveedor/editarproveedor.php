<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$id = $_GET['id'];
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
$tabla = "Proveedor";

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

$sql="UPDATE $tabla SET Empresa='$empresa', CIF='$cif', Telefono='$telefono', Pais='$pais', Provincia='$provincia', Poblacion='$pueblo', CP='$cp', Direccion='$direccion', Web='$web', Notas='$notas', Mail='$email' WHERE Id='$id'";
$result = mysqli_query($con,$sql);

print $result;

mysqli_close($con);
?>
