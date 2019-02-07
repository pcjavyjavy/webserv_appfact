<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$producto = $_GET['pro'];
$factura = $_GET['fac'];
$cantidad = $_GET['cant'];
$descuento = $_GET['des'];
$precio = $_GET['pre'];
$iva = $_GET['iva'];
$precio_tipo = $_GET['tipo'];
$tabla = "Compra";


// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_set_charset("utf8");

mysqli_select_db($con,"ajax_demo");


$sql="SELECT Producto.Producto, Compra.Cantidad, Compra.Precio AS PrecioBase, Compra.Precio*(1-Compra.Descuento/100)*Compra.Cantidad AS Total , Compra.Precio*(1-Compra.Descuento/100)*(1+Compra.IVA/100)*Compra.Cantidad as PrecioFinal FROM Compra INNER JOIN Producto ON Compra.Producto=Producto.Id WHERE Compra.Compra = '".$factura."'";

$result = mysqli_query($con,$sql);

$env_csv = "";

while($row = mysqli_fetch_array($result)) {
    $env_csv .= $row['Producto'].",";
    $env_csv .= $row['Cantidad'].",";
    $env_csv .= $row['PrecioBase'].",";
    $env_csv .= $row['Total'].",";
    $env_csv .= $row['PrecioFinal'].",";
}
$env_csv = $env_csv."X";
print $env_csv;

mysqli_close($con);
?>
