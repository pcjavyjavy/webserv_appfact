<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$id = $_GET['id'];
$tabla = "Cliente";
$eliminados = "Delete_Cliente";


// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}


mysqli_set_charset("utf8");

mysqli_select_db($con,"ajax_demo");

$sql="SELECT * FROM $tabla WHERE Id = $id";
$result = mysqli_query($con,$sql);

$Nombre = "";
$Apellidos = "";
$DNI = "";
$Telefono = "";
$Pais = "";
$Provincia = "";
$Poblacion = "";
$Cp = "";
$Direccion = "";
$Descuentos = "";
$Notas = "";
$Mail = "";

while($row = mysqli_fetch_array($result)) {
    $Nombre = $row['Nombre'];
    $Apellidos = $row['Apellidos'];
    $DNI = $row['DNI'];
    $Telefono = $row['Telefono'];
    $Mail = $row['Mail'];
    $Pais = $row['Pais'];
    $Provincia = $row['Provincia'];
    $Poblacion = $row['Poblacion'];
    $Cp = $row['Cp'];
    $Direccion = $row['Direccion'];
    $Descuentos = $row['Descuentos'];
    $Notas = $row['Notas'];
}

$sql="INSERT INTO $eliminados (Id, Nombre, Apellidos, DNI, Telefono, Pais, Provincia, Poblacion, Cp, Direccion, Descuentos, Notas, Mail) VALUES ($id,'$Nombre','$Apellidos','$DNI','$Telefono','$Pais','$Provincia','$Poblacion','$Cp','$Direccion','$Descuentos','$Notas','$Mail')";
$result = mysqli_query($con,$sql);

$sql="DELETE FROM $tabla WHERE Id='$id'";
$result = mysqli_query($con,$sql);

print $result;

mysqli_close($con);
?>
