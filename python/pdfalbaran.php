<?php
$factura = $_GET['fra'];
$correo = $_GET['emails'];
$comando = "./sacaalbaran.py ".$factura." ".$correo;
print shell_exec($comando);
?>
