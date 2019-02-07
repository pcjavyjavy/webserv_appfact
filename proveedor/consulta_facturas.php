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
$sql="SELECT NFra as Factura, Empresa, CIF, Telefono, Mail, Fecha, Importe, Saldado, Importe-Saldado AS Deuda FROM (SELECT FCompra.NFra, Proveedor.CIF, Proveedor.Telefono, Proveedor.Mail, Proveedor.Empresa, FCompra.Fecha, ROUND(SUM(Compra.Cantidad*Compra.Precio*(1-Compra.Descuento/100)*(1+Compra.IVA/100)),2) AS Importe, FCompra.Saldado FROM FCompra INNER JOIN Proveedor ON FCompra.Proveedor=Proveedor.Id INNER JOIN Compra ON FCompra.Id=Compra.Compra WHERE FCompra.Fecha BETWEEN '$minfecha' AND '$maxfecha' GROUP BY FCompra.Id) AS Resultado WHERE Importe-Saldado $signo $deuda";
$result = mysqli_query($con,$sql);

$env_csv = "";

$separador="--..--";

while($row = mysqli_fetch_array($result)) {
    $env_csv .= $row['Factura'].$separador;
    $env_csv .= $row['Empresa'].$separador;
    $env_csv .= $row['CIF'].$separador;
    $env_csv .= $row['Telefono'].$separador;
    $env_csv .= $row['Mail'].$separador;
    $env_csv .= $row['Importe'].$separador;
    $env_csv .= $row['Liquidado'].$separador;
    $env_csv .= $row['Deuda'].$separador;
}
$env_csv=$env_csv."X";
print $env_csv;
mysqli_close($con);
?>
