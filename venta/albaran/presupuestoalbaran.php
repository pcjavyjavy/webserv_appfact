<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$presupuesto = $_GET['pre'];
$fyear = $_GET['year'];
$fmonth = $_GET['mes'];
$fday = $_GET['day'];
$tablaorigen = "Presupuesto";
$tabladestino = "Albaran";


$fecha=$fyear."-".$fmonth."-".$fday;
$dayalb=$fyear.$fmonth.$fday;

if(!$fyear) {
    $fecha=date("Y-m-d");
    $dayalb=date("Ymd");
}




// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_set_charset("utf8");
mysqli_select_db($con,"ajax_demo");

$sql="SELECT MAX(Id) AS maximo from $tabladestino";
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
    $sql="SELECT NAlb from $tabladestino WHERE NAlb LIKE '".$dayalb."%' ORDER BY NAlb DESC LIMIT 1";
    $result = mysqli_query($con,$sql);
    $env_csv = "";
    while($row = mysqli_fetch_array($result)) {
        $env_csv .= $row['NAlb']."";
    }
    if(strcmp(substr($env_csv, 0, 8),$fyear) == 0){
        $numero=intval(substr($env_csv,8,3));
	$numero=$numero+1;
	$numero="$numero";
	while(strlen($numero) < 3) {
	    $numero="0".$numero;
	}
	$nfra=$dayalb.$numero;
    } else {
        $nfra=$fdayalb."001";
    }
}

$sql="SELECT Cliente, NPres, Notas FROM $tablaorigen WHERE Id=$presupuesto";
$result = mysqli_query($con,$sql);

while($row = mysqli_fetch_array($result)) {
    $cliente = $row['Cliente'];
    $notas = "Presupuesto num.: ".$row['NPres']."   Notas presupuesto: ".$row['Notas'];
    $sql="INSERT INTO $tabladestino (Id, NAlb, Cliente, Fecha, Notas,Fra) VALUES ($id,'$nfra','$cliente','$fecha','$notas',0)";
    $resultdos = mysqli_query($con,$sql);
    $sql="UPDATE $tablaorigen SET Albaran=$id WHERE Id=$presupuesto";
    $resulttres = mysqli_query($con,$sql);
}


print $id;

mysqli_close($con);
?>
