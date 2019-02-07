<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$minyear = $_GET['minyear'];
$maxyear = $_GET['maxyear'];
$minmes = $_GET['minmes'];
$maxmes = $_GET['maxmes'];
$mindia = $_GET['minday'];
$maxdia = $_GET['maxday'];
$minfecha = $minyear."-".$minmes."-".$mindia;
$maxfecha = $maxyear."-".$maxmes."-".$maxdia;

if(!$minyear) {
    $minfecha='2000-01-01';
}


if(!$maxyear) {
    $maxfecha=date("Y-m-d");
}

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con,"ajax_demo");
$sql="SELECT CONCAT(Cliente.Nombre,SPACE(1),Cliente.Apellidos) AS Cliente, Cobro.Cantidad, Cobro.Fecha FROM Cobro INNER JOIN Cliente ON Cobro.Cliente=Cliente.Id WHERE Cobro.Fecha BETWEEN '$minfecha' AND '$maxfecha'";
$result = mysqli_query($con,$sql);

$env_csv = "";

$separador="--..--";

while($row = mysqli_fetch_array($result)) {
    $env_csv .= $row['Cliente'].$separador;
    $env_csv .= $row['Cantidad'].$separador;
    $env_csv .= $row['Fecha'].$separador;
}
$env_csv=$env_csv."X";
print $env_csv;
mysqli_close($con);
?>
