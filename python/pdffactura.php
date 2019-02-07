<?php
$factura = $_GET['fra'];
$correo = $_GET['emails'];
$comando = "./sacafactura.py ".$factura." ".$correo;
print shell_exec($comando);
?>
