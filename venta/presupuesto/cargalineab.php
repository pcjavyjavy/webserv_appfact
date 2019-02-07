<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$id = $_GET['id'];
$posicion = $_GET['pos'];

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con,"ajax_demo");
$sql="SELECT * FROM (SELECT PresLineas.Id, Producto.Producto, Producto.Id AS IdPro, PresLineas.Presupuesto, PresLineas.Cantidad, PresLineas.Descuento, PresLineas.Precio, PresLineas.IVA FROM PresLineas INNER JOIN Producto ON PresLineas.Producto=Producto.Id WHERE PresLineas.Presupuesto=$id ORDER BY Id LIMIT $posicion) as Resultado ORDER BY Id DESC LIMIT 1";
$result = mysqli_query($con,$sql);

$env_csv = "";
$sustituciones=array("(",")","[","]","{","}",",",";");
$separador="--..--";

while($row = mysqli_fetch_array($result)) {
    $env_csv .= $row['Id'].$separador;
    $env_csv .= $row['Producto'].$separador;
    $env_csv .= $row['IdPro'].$separador;
    $env_csv .= $row['Cantidad'].$separador;
    $env_csv .= $row['Descuento'].$separador;
    $env_csv .= $row['Precio'].$separador;
    $env_csv .= $row['IVA'].$separador;
}
$env_csv=$env_csv."X";
print $env_csv;
mysqli_close($con);
?>
