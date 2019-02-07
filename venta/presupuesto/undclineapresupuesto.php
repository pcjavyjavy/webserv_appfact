<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$id = $_GET['id'];
$presupuesto = $_GET['fac'];
$producto = $_GET['pro'];
$cantidad = $_GET['cant'];
$descuento = $_GET['des'];
$descripcion = $_GET['descr'];
$precio = $_GET['pre'];
$iva = $_GET['iva'];
$precio_tipo = $_GET['tipo'];
$guardar = $_GET['save'];
$tabla = "PresLineas";

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
        $sql="UPDATE $tabla SET Producto='$producto', Cantidad='$cantidad', Descuento='$descuento', Precio='$precio', IVA='$iva', Descripcion='$descripcion' WHERE Id='$id'";
        $result = mysqli_query($con,$sql);

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
	$sql="INSERT INTO $tabla (Id, Producto, Presupuesto, Cantidad, Descuento, Precio, IVA, Descripcion) VALUES ($id,'$producto','$presupuesto','$cantidad','$descuento','$precio','$iva','$descripcion')";
        $result = mysqli_query($con,$sql);

    } elseif ($guardar == 'D') {
        $sql="DELETE FROM $tabla WHERE Id='$id'";
        $result = mysqli_query($con,$sql);
    }
}

$sql="SELECT PresLineas.Presupuesto, Producto.Producto, PresLineas.Cantidad, PresLineas.Descuento, PresLineas.Precio AS PrecioBase, PresLineas.Precio*(1-PresLineas.Descuento/100)*PresLineas.Cantidad AS Total , PresLineas.Precio*(1-PresLineas.Descuento/100)*(1+PresLineas.IVA/100)*PresLineas.Cantidad as PrecioFinal FROM PresLineas INNER JOIN Producto ON PresLineas.Producto=Producto.Id WHERE PresLineas.Presupuesto = '".$presupuesto."'";

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
