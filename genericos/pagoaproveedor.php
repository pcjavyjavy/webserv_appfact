<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$proveedor = $_GET['pro'];
$cantidad = $_GET['can'];
$fyear = $_GET['fy'];
$fmes = $_GET['fm'];
$fday = $_GET['fd'];
$factura = $_GET['nf'];
$concepto = $_GET['con'];
$env_csv = "";

if(!$fy) {
    $fecha=date("Y-m-d");
} else {
    $fecha=$fy.'-'.$fm.'-'.$fd;
}


// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}


mysqli_select_db($con,"ajax_demo");
$sql="INSERT INTO Pago(Proveedor,Cantidad,Fecha,Fra,Concepto) VALUES($proveedor,$cantidad,'$fecha',$factura,'$concepto')";
$result = mysqli_query($con,$sql);

if($factura){
    $sql="UPDATE FCompra SET Saldado=Saldado+$cantidad WHERE Id=$factura";
    $result = mysqli_query($con,$sql);

    $sql="SELECT SUM(ROUND(Cantidad*Precio*(1-Descuento/100)*(1+IVA/100),2)) AS Total FROM Compra WHERE Compra=$factura GROUP BY Compra";
    $result = mysqli_query($con,$sql);
    while($row = mysqli_fetch_array($result)) {
        $env_csv = $row['Total'];
    }
    $importefra=$env_csv;

    $sql="SELECT Saldado FROM FCompra WHERE Id=$factura";
    $result = mysqli_query($con,$sql);
    while($row = mysqli_fetch_array($result)) {
        $env_csv = $row['Saldado'];
    }
    $saldado=$env_csv;

    if($saldado >= $importefra){
        $sql="UPDATE FCompra SET Liquidado='S' WHERE Id=$factura";
        $result = mysqli_query($con,$sql);
	if($result==1){
	    print "Saldado";
	}
    } else {
        $deuda=$importefra-$saldado;
	print "Deuda: ".$deuda;
    }

} else {
    if($result==1){
        print "Pago guardado";
    }
}

mysqli_close($con);
?>
