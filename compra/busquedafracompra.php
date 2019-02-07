<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$proveedor = $_GET['prov'];
$factura = $_GET['fact'];
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

if($proveedor) {
    $proveedor='= '.$proveedor;
}


// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_set_charset("utf8");

mysqli_select_db($con,"ajax_demo");
$sql="SELECT FCompra.Id, Proveedor.Empresa, FCompra.NFra, FCompra.Fecha, ROUND(SUM(Compra.Cantidad*Compra.Precio*(1-Compra.Descuento/100)*(1+Compra.IVA/100)),2) as Total FROM FCompra INNER JOIN Compra ON FCompra.Id=Compra.Compra INNER JOIN Proveedor ON FCompra.Proveedor=Proveedor.Id WHERE Proveedor.Id $proveedor AND FCompra.NFra LIKE '%$factura%' AND FCompra.Fecha BETWEEN '$minfecha' AND '$maxfecha' GROUP BY FCompra.Id ORDER BY Proveedor.Empresa, FCompra.Fecha";
$result = mysqli_query($con,$sql);

$sustituciones=array("(",")","[","]","{","}",",",";");

$env_csv = "";

while($row = mysqli_fetch_array($result)) {
    $fy = substr($row['Fecha'],0,4);
    $fm = substr($row['Fecha'],5,2);
    $fd = substr($row['Fecha'],8,2);
    $env_csv .= $fd.'/'.$fm.'/'.$fy.' ';
    $swap = $row['Empresa'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap." - ";
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
