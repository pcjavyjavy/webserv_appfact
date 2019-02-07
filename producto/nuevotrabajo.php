<?php
$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];
$producto = $_GET['pro'];
$descripcion = $_GET['des'];
$cantidad = $_GET['can'];
$precio = $_GET['pre'];
$nuevo = $_GET['new'];
$productos = 'Producto';
$compras = 'Compra';
$stock = 'Stock';

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_set_charset("utf8");
mysqli_select_db($con,"ajax_demo");
if($nuevo == '0') {
    $sql="SELECT MAX(Id) AS maximo from $productos";
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
    $nuevo=$id;


    $sql="INSERT INTO $productos (Id, Producto, Tipo, Descripcion) VALUES ($id,'$producto','T','$descripcion')";
    $result = mysqli_query($con,$sql);


    $sql="SELECT MAX(Id) AS maximo from $stock";
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


    $sql="INSERT INTO $stock (Id, Producto, Compra, Cantidad, Precio, IVA) VALUES ($id,'$nuevo','0','$cantidad','$precio','21')";
    $result = mysqli_query($con,$sql);
} else {
    $sql="UPDATE $stock SET Cantidad=Cantidad+$cantidad, Precio=$precio WHERE Producto=$nuevo";
    $result = mysqli_query($con,$sql);
}


print $result;

mysqli_close($con);
?>
