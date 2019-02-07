<?php
$nombre = $_GET['nom'];
$apellidos = $_GET['ape'];
$dni = $_GET['dni'];
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
$sql="SELECT Nombre, Apellidos, DNI, Id FROM Cliente WHERE Nombre LIKE '%$nombre%' AND Apellidos LIKE '%$apellidos%' AND Telefono LIKE '%$telefono%' AND Poblacion LIKE '%$poblacion%' AND Provincia LIKE '%$provincia%' AND DNI LIKE '%$dni%' ORDER BY Nombre, Apellidos";
$result = mysqli_query($con,$sql);

$sustituciones=array("(",")","[","]","{","}",",",";");

$env_csv = "";

while($row = mysqli_fetch_array($result)) {
    $swap = $row['Nombre'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap." ";
    $swap = $row['Apellidos'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap." DNI: ";
    $swap = $row['DNI'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap." (";
    $env_csv .= $row['Id']."),";
}
$env_csv = substr($env_csv, 0, -1);
print $env_csv;
mysqli_close($con);
?>
