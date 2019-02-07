<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$tabla = "FCompra";

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_set_charset("utf8");

mysqli_select_db($con,"ajax_demo");
$sql="SELECT MAX(Id) AS maximo from $tabla";
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

print $env_csv;

mysqli_close($con);
?>
