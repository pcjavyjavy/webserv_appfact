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
$sql="SELECT * FROM Cliente WHERE Id = '$id'";
$result = mysqli_query($con,$sql);

$env_csv = "";

$separador="--..--";

while($row = mysqli_fetch_array($result)) {
    $env_csv .= $row['Nombre'].$separador;
    $env_csv .= $row['Apellidos'].$separador;
    $env_csv .= $row['DNI'].$separador;
    $env_csv .= $row['Telefono'].$separador;
    $env_csv .= $row['Mail'].$separador;
    $env_csv .= $row['Pais'].$separador;
    $env_csv .= $row['Provincia'].$separador;
    $env_csv .= $row['Poblacion'].$separador;
    $env_csv .= $row['Cp'].$separador;
    $env_csv .= $row['Direccion'].$separador;
    $env_csv .= $row['Descuentos'].$separador;
    $env_csv .= $row['Notas'].$separador;
}
$env_csv=$env_csv."X";
print $env_csv;
mysqli_close($con);
?>
