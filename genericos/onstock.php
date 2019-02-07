<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$producto = $_GET['pro'];

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}


mysqli_select_db($con,"ajax_demo");
$sql="SELECT Stock.Id, Producto.Producto, Proveedor.Empresa, Stock.Cantidad, Stock.Precio, Stock.IVA, FCompra.Fecha FROM Stock INNER JOIN Producto ON Stock.Producto=Producto.Id INNER JOIN FCompra ON Stock.Compra=FCompra.Id INNER JOIN Proveedor ON FCompra.Proveedor=Proveedor.Id WHERE Stock.Cantidad > 0 AND Producto.Id = $producto ORDER BY Stock.Id";
$result = mysqli_query($con,$sql);

$env_csv = "";

$sustituciones=array("(",")","[","]","{","}",",",";");

while($row = mysqli_fetch_array($result)) {
    $swap = $row['Empresa'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap." (";
    $env_csv .= $row['Id'].")\nStock [";
    $env_csv .= $row['Cantidad']."-";
    $swap = $row['Fecha'];
    $swap = str_replace("-","/",$swap);
    $env_csv .= $swap."]\n{";
    $env_csv .= $row['Precio']." Euros},";
}
$env_csv = substr($env_csv, 0, -1);
print $env_csv;
mysqli_close($con);
?>
