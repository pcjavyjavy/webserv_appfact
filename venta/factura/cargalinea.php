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
$sql="SELECT * FROM (SELECT Venta.Id, Producto.Producto, Producto.Id AS IdPro, Stock.Id AS IdSto, Proveedor.Empresa, Stock.Cantidad AS CantTotal, Stock.Precio AS PrecioStock, FCompra.Fecha AS FechaCompra, Venta.Venta, Venta.Cantidad, Venta.Descuento, Venta.Precio, Venta.IVA, Venta.Descripcion FROM Venta INNER JOIN Producto ON Venta.Producto=Producto.Id INNER JOIN Stock ON Venta.Stock=Stock.Id INNER JOIN FCompra ON Stock.Compra=FCompra.Id INNER JOIN Proveedor ON FCompra.Proveedor=Proveedor.Id WHERE Venta.Venta=$id ORDER BY Id LIMIT $posicion) as Resultado ORDER BY Id DESC LIMIT 1";
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
    $swap = $row['Empresa'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap." (";
    $env_csv .= $row['IdSto'].")\nStock [";
    $env_csv .= $row['CantTotal']."-";
    $swap = $row['FechaCompra'];
    $swap = str_replace("-","/",$swap);
    $env_csv .= $swap."]\n{";
    $env_csv .= $row['PrecioStock']." Euros}".$separador;
    $env_csv .= $row['Cantidad'].$separador;
    $env_csv .= $row['Descuento'].$separador;
    $env_csv .= $row['Precio'].$separador;
    $env_csv .= $row['IVA'].$separador;
    $env_csv .= $row['IdSto'].$separador;
    $env_csv .= $row['Descripcion'].$separador;
    
}
$env_csv=$env_csv."X";
print $env_csv;
mysqli_close($con);
?>
