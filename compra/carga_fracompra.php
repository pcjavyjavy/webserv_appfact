<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$id = $_GET['id'];

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con,"ajax_demo");
$sql="SELECT Proveedor.Id, Proveedor.Empresa, FCompra.NFra, FCompra.Fecha, FCompra.Notas FROM FCompra INNER JOIN Proveedor ON FCompra.Proveedor=Proveedor.Id WHERE FCompra.Id = '".$id."'";
$result = mysqli_query($con,$sql);

$env_csv = "";
$separador="--..--";
$sustituciones=array("(",")","[","]","{","}",",",";");

while($row = mysqli_fetch_array($result)) {
    $swap = $row['Empresa'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap." (";
    $env_csv .= $row['Id'].")";
    $env_csv .= $separador;
    $env_csv .= $row['Empresa'].$separador;
    $env_csv .= $row['NFra'].$separador;
    $env_csv .= $row['Fecha'].$separador;
    $env_csv .= $row['Notas'].$separador;
}
$env_csv=$env_csv."X";
print $env_csv;
mysqli_close($con);
?>
