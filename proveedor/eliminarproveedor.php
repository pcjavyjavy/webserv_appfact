<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$id = $_GET['id'];
$tabla = "Proveedor";
$eliminados = "Delete_Proveedor";

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

$Empresa = "";
$CIF = "";
$Telefono = "";
$Pais = "";
$Provincia = "";
$Poblacion = "";
$Cp = "";
$Direccion = "";
$Web = "";
$Notas = "";
$Mail = "";

while($row = mysqli_fetch_array($result)) {
    $Empresa = $row['Empresa'];
    $CIF = $row['CIF'];
    $Telefono = $row['Telefono'];
    $Mail = $row['Mail'];
    $Pais = $row['Pais'];
    $Provincia = $row['Provincia'];
    $Poblacion = $row['Poblacion'];
    $Cp = $row['CP'];
    $Direccion = $row['Direccion'];
    $Web = $row['Web'];
    $Notas = $row['Notas'];
}

$sql="INSERT INTO $eliminados (Id, Empresa, CIF, Telefono, Pais, Provincia, Poblacion, CP, Direccion, Web, Notas, Mail) VALUES ($id,'$Empresa','$CIF','$Telefono','$Pais','$Provincia','$Poblacion','$Cp','$Direccion','$Web','$Notas','$Mail')";
$result = mysqli_query($con,$sql);



$sql="DELETE FROM $tabla WHERE Id='$id'";
$result = mysqli_query($con,$sql);

print $result;

mysqli_close($con);
?>
