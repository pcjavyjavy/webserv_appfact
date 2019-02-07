<?php
$factura = $_GET['fra'];
$correo = $_GET['emails'];
$comando = "./sacapresupuesto.py ".$factura." ".$correo;
print shell_exec($comando);
?>