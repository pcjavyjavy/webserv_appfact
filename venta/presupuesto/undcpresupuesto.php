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
$vyear = $_GET['vyear'];
$vmonth = $_GET['vmes'];
$vday = $_GET['vday'];
$dtos = $_GET['desc'];
$notas = $_GET['nota'];
$guardar = $_GET['save'];
$tabla = "Presupuesto";

$separador="--..--";
if(strlen($fmonth) < 2) {
    $fmonth="0".$fmonth;
}
$mespres=$fyear.$fmonth;
$fecha=$fyear."-".$fmonth."-".$fday;
if($vday=='' or $vday=='0'){
    $validez='2099-12-31';
} else {
    $validez=$vyear."-".$vmonth."-".$vday;
}

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
        $nfra=$mespres."001";
    } else {
        $sql="SELECT NPres from $tabla WHERE NPres LIKE '".$mespres."%' ORDER BY NPres DESC LIMIT 1";
        $result = mysqli_query($con,$sql);
        $env_csv = "";
        while($row = mysqli_fetch_array($result)) {
            $env_csv .= $row['NPres']."";
        }
        if(strcmp(substr($env_csv, 0, 6),$mespres) == 0){
            $numero=intval(substr($env_csv,6,3));
	    $numero=$numero+1;
	    $numero="$numero";
	    while(strlen($numero) < 3) {
	        $numero="0".$numero;
	    }
	    $nfra=$mespres.$numero;
        } else {
            $nfra=$mespres."001";
        }
    }

    $sql="INSERT INTO $tabla (Id, NPres, Cliente, Fecha, Validez, Notas, Albaran) VALUES ($id,'$nfra','$cliente','$fecha','$validez','$notas','')";
    $result = mysqli_query($con,$sql);
    print $id.",".$result;

} elseif($guardar == 'U') {
    $sql="UPDATE $tabla SET Cliente='$cliente', Fecha='$fecha', Validez='$validez', Notas='$notas' WHERE Id='$id'";
    $result = mysqli_query($con,$sql);
    print $result;

} elseif($guardar == 'D') {
    $sql="DELETE FROM $tabla WHERE Id='$id'";
    $result = mysqli_query($con,$sql);
    print $result;

} elseif($guardar == 'C') {
    $sql="SELECT Cliente.Id, Cliente.Nombre, Cliente.Apellidos, Presupuesto.NPres, Presupuesto.Fecha, Presupuesto.Validez, Presupuesto.Notas FROM Presupuesto INNER JOIN Cliente ON Presupuesto.Cliente=Cliente.Id WHERE Presupuesto.Id = '".$id."'";
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
        $env_csv .= $row['NPres'].$separador;
        $env_csv .= $row['Fecha'].$separador;
        $env_csv .= $row['Validez'].$separador;
        $env_csv .= $row['Notas'].$separador;
    }
    $env_csv=$env_csv."X";
    print $env_csv;

}
mysqli_close($con);
?>
