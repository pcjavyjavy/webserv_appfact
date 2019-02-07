<?php
$empresa = $_GET['emp'];
$cif = $_GET['cif'];
$telefono = $_GET['tel'];
$provincia = $_GET['pro'];
$poblacion = $_GET['pob'];
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_set_charset("utf8");

mysqli_select_db($con,"ajax_demo");
$sql="SELECT Empresa, CIF, Id FROM Proveedor WHERE Empresa LIKE '%$empresa%' AND Telefono LIKE '%$telefono%' AND Poblacion LIKE '%$poblacion%' AND Provincia LIKE '%$provincia%' AND CIF LIKE '%$cif%' ORDER BY Empresa";
$result = mysqli_query($con,$sql);


$sustituciones=array("(",")","[","]","{","}",",",";");

$env_csv = "";

while($row = mysqli_fetch_array($result)) {
    $swap = $row['Empresa'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap." CIF: ";
    $swap = $row['CIF'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap." (";
    $env_csv .= $row['Id']."),";
}
$env_csv = substr($env_csv, 0, -1);
print $env_csv;
mysqli_close($con);
?>
