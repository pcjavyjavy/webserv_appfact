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
$tabla = "Compra";


// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_set_charset("utf8");

mysqli_select_db($con,"ajax_demo");
$sql="SELECT MAX(Id) AS maximo from $tabla";
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

$id=intval($env_csv)+1;


mysqli_select_db($con,"ajax_demo");

$sql="INSERT INTO $tabla (Id, Producto, Compra, Cantidad, Descuento, Precio, IVA) VALUES ($id,'$producto','$factura','$cantidad','$descuento','$precio','$iva')";
$result = mysqli_query($con,$sql);

$tabla = "Stock";

$precio=$precio*(1-$descuento/100);

$sql="INSERT INTO $tabla (Id, Producto, Compra, Cantidad, Precio, IVA) VALUES ($id,'$producto','$factura','$cantidad','$precio','$iva')";
$result = mysqli_query($con,$sql);

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
