<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$id = $_GET['id'];
$cliente = $_GET['cli'];
$fyear = $_GET['year'];
$fmonth = $_GET['mes'];
$fday = $_GET['day'];
$dtos = $_GET['desc'];
$notas = $_GET['nota'];
$guardar = $_GET['save'];
$tabla = "Albaran";

$separador="--..--";
if(strlen($fmonth) < 2) {
    $fmonth="0".$fmonth;
}
if(strlen($fday) < 2) {
    $fday="0".$fday;
}
$dayalb=$fyear.$fmonth.$fday;
$fecha=$fyear."-".$fmonth."-".$fday;

$dtos=str_replace(",",".",$dtos);

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_set_charset("utf8");

mysqli_select_db($con,"ajax_demo");

if($guardar == 'N'){
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

    $id=intval($env_csv)+1;

    if($env_csv == "0") {
        $nfra=$dayalb."001";
    } else {
        $sql="SELECT NAlb from $tabla WHERE NAlb LIKE '".$dayalb."%' ORDER BY NAlb DESC LIMIT 1";
        $result = mysqli_query($con,$sql);
        $env_csv = "";
        while($row = mysqli_fetch_array($result)) {
            $env_csv .= $row['NAlb']."";
        }
        if(strcmp(substr($env_csv, 0, 8),$dayalb) == 0){
            $numero=intval(substr($env_csv,8,3));
	    $numero=$numero+1;
	    $numero="$numero";
	    while(strlen($numero) < 3) {
	        $numero="0".$numero;
	    }
	    $nfra=$dayalb.$numero;
        } else {
            $nfra=$dayalb."001";
        }
    }

    $sql="INSERT INTO $tabla (Id, NAlb, Cliente, Fecha, Notas, Fra) VALUES ($id,'$nfra','$cliente','$fecha','$notas','')";
    $result = mysqli_query($con,$sql);
    print $id.",".$result;

} elseif($guardar == 'U') {
    $sql="UPDATE $tabla SET Cliente='$cliente', Fecha='$fecha', Notas='$notas' WHERE Id='$id'";
    $result = mysqli_query($con,$sql);
    print $result;

} elseif($guardar == 'D') {
    $sql="DELETE FROM $tabla WHERE Id='$id'";
    $result = mysqli_query($con,$sql);
    print $result;

} elseif($guardar == 'C') {
    $sql="SELECT Cliente.Id, Cliente.Nombre, Cliente.Apellidos, Albaran.NAlb, Albaran.Fecha, Albaran.Notas FROM Albaran INNER JOIN Cliente ON Albaran.Cliente=Cliente.Id WHERE Albaran.Id = '".$id."'";
    $result = mysqli_query($con,$sql);
    $env_csv = "";
    $sustituciones=array("(",")","[","]","{","}",",",";");

    while($row = mysqli_fetch_array($result)) {
        $swap = $row['Nombre'];
        $swap = str_replace($sustituciones,"",$swap);
        $env_csv .= $swap." ";
	$swap = $row['Apellidos'];
        $swap = str_replace($sustituciones,"",$swap);
        $env_csv .= $swap." (";
        $env_csv .= $row['Id'].")";
        $env_csv .= $separador;
        $env_csv .= $row['Nombre'].$separador;
        $env_csv .= $row['Apellidos'].$separador;
        $env_csv .= $row['NAlb'].$separador;
        $env_csv .= $row['Fecha'].$separador;
        $env_csv .= $row['Notas'].$separador;
    }
    $env_csv=$env_csv."X";
    print $env_csv;

}
mysqli_close($con);
?>
