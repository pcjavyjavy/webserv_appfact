<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$cliente = $_GET['prov'];


if($cliente) {
    $cliente='= '.$cliente;
}


// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_set_charset("utf8");

mysqli_select_db($con,"ajax_demo");
$sql="SELECT FVenta.Id, FVenta.NFra, FVenta.Fecha, ROUND(SUM(Venta.Cantidad*Venta.Precio*(1-Venta.Descuento/100)*(1+Venta.IVA/100))-FVenta.Saldado,2) as Deuda, ROUND(SUM(Venta.Cantidad*Venta.Precio*(1-Venta.Descuento/100)*(1+Venta.IVA/100)),2) as Total FROM FVenta INNER JOIN Venta ON FVenta.Id=Venta.Venta INNER JOIN Cliente ON FVenta.Cliente=Cliente.Id WHERE Cliente.Id $cliente AND FVenta.Liquidado LIKE 'N' GROUP BY FVenta.Id ORDER BY FVenta.Fecha DESC";
$result = mysqli_query($con,$sql);


$sustituciones=array("(",")","[","]","{","}",",",";");

$env_csv = "";

while($row = mysqli_fetch_array($result)) {
    $fy = substr($row['Fecha'],0,4);
    $fm = substr($row['Fecha'],5,2);
    $fd = substr($row['Fecha'],8,2);
    $env_csv .= $fd.'/'.$fm.'/'.$fy."\nDeuda: ";
    $env_csv .= $row[Deuda]." Euros\n";
    $swap = $row['NFra'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap." - ";
    $env_csv .= $row['Total']." Euros (";
    $env_csv .= $row['Id']."),";
}
$env_csv = substr($env_csv, 0, -1);
print $env_csv;
mysqli_close($con);
?>
