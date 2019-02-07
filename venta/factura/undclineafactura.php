<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$id = $_GET['id'];
$factura = $_GET['fac'];
$producto = $_GET['pro'];
$stock = $_GET['sto'];
$previocantidad = $_GET['preca'];
$previostock = $_GET['prest'];
$cantidad = $_GET['cant'];
$descuento = $_GET['des'];
$descripcion = $_GET['descr'];
$precio = $_GET['pre'];
$iva = $_GET['iva'];
$precio_tipo = $_GET['tipo'];
$guardar = $_GET['save'];
$tabla = "Venta";

$separador="--..--";
$cantidad=str_replace(",",".",$cantidad);
$descuento=str_replace(",",".",$descuento);
$precio=str_replace(",",".",$precio);
$iva=str_replace(",",".",$iva);

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}


mysqli_set_charset("utf8");

mysqli_select_db($con,"ajax_demo");

if($guardar) {
    switch($precio_tipo) {
        case "1":
            $precio=(floatval($precio)/(1+floatval($iva)/100))/(1-floatval($descuento)/100);
	    break;
        case "2":
            $precio=$precio;
	    break;
        case "3":
            $precio=floatval($precio)/(1-floatval($descuento)/100);
	    break;
        case "4":
            $precio=floatval($precio)/(1+floatval($iva)/100);
	    break;
    }
    if($guardar == 'U') {
        $sql="UPDATE $tabla SET Producto='$producto', Stock='$stock', Cantidad='$cantidad', Descuento='$descuento', Precio='$precio', IVA='$iva', Descripcion='$descripcion' WHERE Id='$id'";
        $result = mysqli_query($con,$sql);

        if($previostock=$stock) {

            if($previocantidad > $cantidad){
    	        $cantidad=$previocantidad-$cantidad;
	        $sql="UPDATE Stock SET Cantidad=Cantidad+'$cantidad' WHERE Id='$stock'";
                $result = mysqli_query($con,$sql);
	    } else {
	        $cantidad=$cantidad-$previocantidad;
	        $sql="UPDATE Stock SET Cantidad=Cantidad-'$cantidad' WHERE Id='$stock'";
                $result = mysqli_query($con,$sql);
	    }
	} else {
	    $sql="UPDATE Stock SET Cantidad=Cantidad+'$previocantidad' WHERE Id='$previostock'";
            $result = mysqli_query($con,$sql);
	    $sql="UPDATE Stock SET Cantidad=Cantidad-'$cantidad' WHERE Id='$stock'";
            $result = mysqli_query($con,$sql);
	}

    } elseif ($guardar == 'N') {
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
	$sql="INSERT INTO $tabla (Id, Producto, Stock, Venta, Cantidad, Descuento, Precio, IVA, Descripcion) VALUES ($id,'$producto','$stock','$factura','$cantidad','$descuento','$precio','$iva','$descripcion')";
        $result = mysqli_query($con,$sql);

        $sql="UPDATE Stock SET Cantidad=Cantidad-'$cantidad' WHERE Id='$stock'";
        $result = mysqli_query($con,$sql);

    } elseif ($guardar == 'D') {
        $sql="DELETE FROM $tabla WHERE Id='$id'";
        $result = mysqli_query($con,$sql);

        $sql="UPDATE Stock SET Cantidad=Cantidad+'$previocantidad' WHERE Id='$stock'";
        $result = mysqli_query($con,$sql);
    }
}

$sql="SELECT Venta.Venta, Producto.Producto, Venta.Cantidad, Venta.Descuento, Venta.Precio AS PrecioBase, Venta.Precio*(1-Venta.Descuento/100)*Venta.Cantidad AS Total , Venta.Precio*(1-Venta.Descuento/100)*(1+Venta.IVA/100)*Venta.Cantidad as PrecioFinal FROM Venta INNER JOIN Producto ON Venta.Producto=Producto.Id WHERE Venta.Venta = '".$factura."'";

$result = mysqli_query($con,$sql);

$env_csv = "";

while($row = mysqli_fetch_array($result)) {
    $env_csv .= $row['Producto'].$separador;
    $env_csv .= $row['Cantidad'].$separador;
    $env_csv .= $row['PrecioBase'].$separador;
    $env_csv .= $row['Total'].$separador;
    $env_csv .= $row['PrecioFinal'].$separador;
}
$env_csv = $env_csv."X";
print $env_csv;

mysqli_close($con);
?>
