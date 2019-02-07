<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$id = $_GET['id'];
$factura = $_GET['fac'];
$producto = $_GET['pro'];
$cantidad = $_GET['cant'];
$descuento = $_GET['des'];
$precio = $_GET['pre'];
$iva = $_GET['iva'];
$precio_tipo = $_GET['tipo'];
$guardar = $_GET['save'];
$tabla = "Compra";

$separador="--..--";

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

    $sql="UPDATE $tabla SET Producto='$producto', Cantidad='$cantidad', Descuento='$descuento', Precio='$precio', IVA=$iva WHERE Id='$id'";
    $result = mysqli_query($con,$sql);

    $tabla = "Stock";

    $sql="UPDATE $tabla SET Producto='$producto', Cantidad='$cantidad', Descuento='$descuento', Precio='$precio', IVA=$iva WHERE Id='$id'";
    $result = mysqli_query($con,$sql);
}

$sql="SELECT Producto.Producto, Compra.Cantidad, Compra.Precio AS PrecioBase, Compra.Precio*(1-Compra.Descuento/100)*Compra.Cantidad AS Total , Compra.Precio*(1-Compra.Descuento/100)*(1+Compra.IVA/100)*Compra.Cantidad as PrecioFinal FROM Compra INNER JOIN Producto ON Compra.Producto=Producto.Id WHERE Compra.Compra = '".$factura."'";

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
