<?php
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
$sql="SELECT Producto, Id FROM Producto WHERE Tipo = 'T' ORDER BY Producto";
$result = mysqli_query($con,$sql);

$sustituciones=array("(",")","[","]","{","}",",",";");

$env_csv = "";

while($row = mysqli_fetch_array($result)) {
    $swap = $row['Producto'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap." (";
    $env_csv .= $row['Id']."),";
}
$env_csv = substr($env_csv, 0, -1);
print $env_csv;
mysqli_close($con);
?>
