<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$cliente = $_GET['prov'];
$presupuesto = $_GET['fact'];
$minyear = $_GET['miny'];
$maxyear = $_GET['maxy'];
$minmes = $_GET['minm'];
$maxmes = $_GET['maxm'];
$mindia = $_GET['mind'];
$maxdia = $_GET['maxd'];
$minfecha = $minyear."-".$minmes."-".$mindia;
$maxfecha = $maxyear."-".$maxmes."-".$maxdia;


if(!$minyear) {
    $minfecha='2000-01-01';
}


if(!$maxyear) {
    $maxfecha=date("Y-m-d");
}


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
$sql="SELECT FVenta.Id, Cliente.Nombre, Cliente.Apellidos, FVenta.NFra, FVenta.Fecha, ROUND(SUM(Venta.Cantidad*Venta.Precio*(1-Venta.Descuento/100)*(1+Venta.IVA/100)),2) as Total FROM FVenta INNER JOIN Venta ON FVenta.Id=Venta.Venta INNER JOIN Cliente ON FVenta.Cliente=Cliente.Id WHERE Cliente.Id $cliente AND FVenta.NFra LIKE '%$presupuesto%' AND FVenta.Fecha BETWEEN '$minfecha' AND '$maxfecha' GROUP BY FVenta.Id ORDER BY FVenta.Fecha DESC, Cliente.Nombre, Cliente.Apellidos";
$result = mysqli_query($con,$sql);


$sustituciones=array("(",")","[","]","{","}",",",";");

$env_csv = "";

while($row = mysqli_fetch_array($result)) {
    $fy = substr($row['Fecha'],0,4);
    $fm = substr($row['Fecha'],5,2);
    $fd = substr($row['Fecha'],8,2);
    $env_csv .= $fd.'/'.$fm.'/'.$fy."\n";
    $swap = $row['Nombre'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap." ";
    $swap = $row['Apellidos'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap."\n";
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
