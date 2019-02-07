<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$deuda = $_GET['deuda'];
$signo = $_GET['signo'];
$minyear = $_GET['minyear'];
$maxyear = $_GET['maxyear'];
$minmes = $_GET['minmes'];
$maxmes = $_GET['maxmes'];
$mindia = $_GET['minday'];
$maxdia = $_GET['maxday'];
$minfecha = $minyear."-".$minmes."-".$mindia;
$maxfecha = $maxyear."-".$maxmes."-".$maxdia;

if(!$minyear){
    $minfecha='2000-01-01';
}


if(!$maxyear){
    $maxfecha=date("Y-m-d");
}


if(!$deuda) {
    $deuda=0;
}

if(!$signo) {
    $signo=">";
}


// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con,"ajax_demo");
$sql="SELECT NFra AS Factura, Cliente, DNI, Telefono, Mail, Poblacion, Provincia, ROUND(Importe,2) AS Importes, Saldado AS Pagados, ROUND(Importe-Saldado,2) AS Deudas FROM (SELECT FVenta.NFra, CONCAT(Cliente.Nombre,SPACE(1),Cliente.Apellidos) AS Cliente, Cliente.DNI, Cliente.Telefono, Cliente.Mail, Cliente.Poblacion, Cliente.Provincia, SUM(Venta.Cantidad*Venta.Precio*(1-Venta.Descuento/100)*(1+Venta.IVA/100)) AS Importe, FVenta.Saldado FROM FVenta LEFT JOIN Cliente ON FVenta.Cliente=Cliente.Id INNER JOIN Venta ON FVenta.Id=Venta.Venta WHERE FVenta.Fecha BETWEEN '$minfecha' AND '$maxfecha' GROUP BY FVenta.Id) AS Resultado WHERE Importe-Saldado $signo $deuda";
$result = mysqli_query($con,$sql);
$env_csv = "";

$separador="--..--";

while($row = mysqli_fetch_array($result)) {
    $env_csv .= $row['Factura'].$separador;
    $env_csv .= $row['Cliente'].$separador;
    $env_csv .= $row['DNI'].$separador;
    $env_csv .= $row['Telefono'].$separador;
    $env_csv .= $row['Mail'].$separador;
    $env_csv .= $row['Poblacion'].$separador;
    $env_csv .= $row['Provincia'].$separador;
    $env_csv .= $row['Importes'].$separador;
    $env_csv .= $row['Pagados'].$separador;
    $env_csv .= $row['Deudas'].$separador;
}
$env_csv=$env_csv."X";
print $env_csv;
mysqli_close($con);
?>
