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
$vminyear = $_GET['vminy'];
$vmaxyear = $_GET['vmaxy'];
$vminmes = $_GET['vminm'];
$vmaxmes = $_GET['vmaxm'];
$vmindia = $_GET['vmind'];
$vmaxdia = $_GET['vmaxd'];
$minfecha = $minyear."-".$minmes."-".$mindia;
$maxfecha = $maxyear."-".$maxmes."-".$maxdia;
$vminfecha = $vminyear."-".$vminmes."-".$vmindia;
$vmaxfecha = $vmaxyear."-".$vmaxmes."-".$vmaxdia;


if(!$minyear) {
    $minfecha='2000-01-01';
}


if(!$maxyear) {
    $maxfecha=date("Y-m-d");
}


if(!$vminyear) {
    $vminfecha='2000-01-01';
}


if(!$vmaxyear) {
    $vmaxfecha=date("2099-12-31");
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
$sql="SELECT Presupuesto.Id, Cliente.Nombre, Cliente.Apellidos, Presupuesto.NPres, Presupuesto.Fecha, Presupuesto.Validez, ROUND(SUM(PresLineas.Cantidad*PresLineas.Precio*(1-PresLineas.Descuento/100)*(1+PresLineas.IVA/100)),2) as Total FROM Presupuesto INNER JOIN PresLineas ON Presupuesto.Id=PresLineas.Presupuesto INNER JOIN Cliente ON Presupuesto.Cliente=Cliente.Id WHERE Cliente.Id $cliente AND Presupuesto.NPres LIKE '%$presupuesto%' AND Presupuesto.Fecha BETWEEN '$minfecha' AND '$maxfecha' AND Presupuesto.Validez BETWEEN '$vminfecha' AND '$vmaxfecha' GROUP BY Presupuesto.Id ORDER BY Presupuesto.Fecha DESC, Cliente.Nombre, Cliente.Apellidos";
$result = mysqli_query($con,$sql);


$sustituciones=array("(",")","[","]","{","}",",",";");

$env_csv = "";

while($row = mysqli_fetch_array($result)) {
    $fy = substr($row['Fecha'],0,4);
    $fm = substr($row['Fecha'],5,2);
    $fd = substr($row['Fecha'],8,2);
    $env_csv .= $fd.'/'.$fm.'/'.$fy.' Val. hasta: ';
    $fy = substr($row['Validez'],0,4);
    $fm = substr($row['Validez'],5,2);
    $fd = substr($row['Validez'],8,2);
    $env_csv .= $fd.'/'.$fm.'/'.$fy."\n";
    $swap = $row['Nombre'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap." ";
    $swap = $row['Apellidos'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap."\n";
    $swap = $row['NPres'];
    $swap = str_replace($sustituciones,"",$swap);
    $env_csv .= $swap." - ";
    $env_csv .= $row['Total']." Euros (";
    $env_csv .= $row['Id']."),";
}
$env_csv = substr($env_csv, 0, -1);
print $env_csv;
mysqli_close($con);
?>
