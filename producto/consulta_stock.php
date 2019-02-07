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
$sql="SELECT Producto.Producto, Proveedor.Empresa, Stock.Cantidad, ROUND(Stock.Precio,2) AS PrecioCompra FROM Stock INNER JOIN Producto ON Stock.Producto=Producto.Id INNER JOIN FCompra ON Stock.Compra=FCompra.Id INNER JOIN Proveedor ON FCompra.Proveedor=Proveedor.Id WHERE Stock.Cantidad > 0";
$result = mysqli_query($con,$sql);

separador="--..--":

$env_csv = "";

while($row = mysqli_fetch_array($result)) {
    $env_csv .= $row['Producto'].$separador;
    $env_csv .= $row['Empresa'].$separador;
    $env_csv .= $row['Cantidad'].$separador;
    $env_csv .= $row['PrecioCompra'].$separador;
}
$env_csv = $env_csv."X";
print $env_csv;
mysqli_close($con);
?>
