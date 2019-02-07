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
$sql="SELECT * FROM (SELECT Compra.Id, Producto.Producto, Producto.Id AS IdPro, Compra.Compra, Compra.Cantidad, Compra.Descuento, Compra.Precio, Compra.IVA FROM Compra INNER JOIN Producto ON Compra.Producto=Producto.Id WHERE Compra=$id ORDER BY Id LIMIT $posicion) as Resultado ORDER BY Id DESC LIMIT 1";
$result = mysqli_query($con,$sql);

$env_csv = "";
$sustituciones=array("(",")","[","]","{","}",",",";");
$separador="--..--";

while($row = mysqli_fetch_array($result)) {
    $env_csv .= $row['Id'].$separador;
    $swap = $row['Producto'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap." (";
    $env_csv .= $row['IdPro'].")".$separador;
    $env_csv .= $row['Cantidad'].$separador;
    $env_csv .= $row['Descuento'].$separador;
    $env_csv .= $row['Precio'].$separador;
    $env_csv .= $row['IVA'].$separador;
}
$env_csv=$env_csv."X";
print $env_csv;
mysqli_close($con);
?>
