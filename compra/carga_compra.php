<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$id = $_GET['id'];

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con,"ajax_demo");
$sql="SELECT Producto, Cantidad, Descuento, Precio, IVA FROM Compra WHERE Id = '".$id."'";
$result = mysqli_query($con,$sql);

$env_csv = "";

$separador="--..--";

while($row = mysqli_fetch_array($result)) {
    $env_csv .= $row['Producto'].$separador;
    $env_csv .= $row['Cantidad'].$separador;
    $env_csv .= $row['Descuento'].$separador;
    $env_csv .= $row['Precio'].$separador;
    $env_csv .= $row['IVA'].$separador;
}
$env_csv=$env_csv."X";
print $env_csv;
mysqli_close($con);
?>
