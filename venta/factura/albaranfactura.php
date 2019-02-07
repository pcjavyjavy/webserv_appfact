<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$albaran = $_GET['alb'];
$fyear = $_GET['year'];
$fmonth = $_GET['mes'];
$fday = $_GET['day'];
$tablaorigen = "Albaran";
$tabladestino = "FVenta";
$lineasorigen = "AlbLineas";
$lineasdestino = "Venta";


$fecha=$fyear."-".$fmonth."-".$fday;

if(!$fyear) {
    $fecha=date("Y-m-d");
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
    $nfra=$fyear."00001";
} else {
    $sql="SELECT NFra from $tabladestino WHERE NFra LIKE '".$fyear."%' ORDER BY NFra DESC LIMIT 1";
    $result = mysqli_query($con,$sql);
    $env_csv = "";
    while($row = mysqli_fetch_array($result)) {
        $env_csv .= $row['NFra']."";
    }
    if(strcmp(substr($env_csv, 0, 4),$fyear) == 0){
        $numero=intval(substr($env_csv,4,5));
	$numero=$numero+1;
	$numero="$numero";
	while(strlen($numero) < 5) {
	    $numero="0".$numero;
	}
	$nfra=$fyear.$numero;
    } else {
        $nfra=$fyear."00001";
    }
}

$sql="SELECT Cliente, NAlb, Notas FROM $tablaorigen WHERE Id=$albaran";
$result = mysqli_query($con,$sql);

while($row = mysqli_fetch_array($result)) {
    $cliente = $row['Cliente'];
    $notas = "Albaran num.: ".$row['NAlb']."   Notas albaran: ".$row['Notas'];
    $sql="INSERT INTO $tabladestino (Id, NFra, Cliente, Dtos, Fecha, Notas, Saldado, Liquidado) VALUES ($id,'$nfra','$cliente',0,'$fecha','$notas','0','N')";
    $resultdos = mysqli_query($con,$sql);
    $sql="UPDATE $tablaorigen SET Fra=$id WHERE Id=$albaran";
    $resulttres = mysqli_query($con,$sql);
}


$sql="SELECT MAX(Id) AS maximo from $lineasdestino";
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
$linea=intval($env_csv)+1;

$producto = array();
$stock = array();
$cantidad = array();
$descuento = array();
$precio = array();
$iva = array();
$notas = array();
$sql="SELECT Producto, Stock, Cantidad, Descuento, Precio, IVA, Descripcion FROM $lineasorigen WHERE Venta=$albaran";
$result = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($result)) {
    array_push($producto,$row['Producto']);
    array_push($stock,$row['Stock']);
    array_push($cantidad,$row['Cantidad']);
    array_push($descuento,$row['Descuento']);
    array_push($precio,$row['Precio']);
    array_push($iva,$row['IVA']);
    array_push($notas,$row['Descripcion']);
}


for ($x = 0; $x < count($producto); $x++)
{
    $sql="INSERT INTO $lineasdestino (Id, Producto, Stock, Venta, Cantidad, Descuento, Precio, IVA, Descripcion) VALUES($linea,$producto[$x],$stock[$x],$id,$cantidad[$x],$descuento[$x],$precio[$x],$iva[$x],'$notas[$x]')";
    $result = mysqli_query($con,$sql);
    $linea++;
}

print "Id. Fra.: ".$id." Num. Fra.: ".$nfra;

mysqli_close($con);
?>
