<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$id = $_GET['id'];
$tabla = "Compra";

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_set_charset("utf8");

mysqli_select_db($con,"ajax_demo");
$sql="SELECT MAX(Id) AS maximo FROM $tabla WHERE Compra=$id";
$result = mysqli_query($con,$sql);
$env_csv = "";
while($row = mysqli_fetch_array($result)) {
    $env_csv .= $row['maximo']."";
}
if(!$env_csv) {
    $env_csv = "0";
}
if($env_csv=='NULL') {
    $env_csv = "0";
}
$maximo=$env_csv;

mysqli_select_db($con,"ajax_demo");
$sql="SELECT MIN(Id) AS minimo FROM $tabla WHERE Compra=$id";
$result = mysqli_query($con,$sql);
$env_csv = "";
while($row = mysqli_fetch_array($result)) {
    $env_csv .= $row['minimo']."";
}
if(!$env_csv) {
    $env_csv = "0";
}
if($env_csv=='NULL') {
    $env_csv = "0";
}
$minimo=$env_csv;

mysqli_select_db($con,"ajax_demo");
$sql="SELECT COUNT(Id) AS numero FROM $tabla WHERE Compra=$id";
$result = mysqli_query($con,$sql);
$env_csv = "";
while($row = mysqli_fetch_array($result)) {
    $env_csv .= $row['numero']."";
}
if(!$env_csv) {
    $env_csv = "0";
}
if($env_csv=='NULL') {
    $env_csv = "0";
}
$numero=$env_csv;

$env_csv=$maximo.",".$minimo.",".$numero;

print $env_csv;

mysqli_close($con);
?>
