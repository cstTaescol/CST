<?php
require("config/configuracion.php");
$id_linea=$_REQUEST['id_linea'];
$sql="SELECT * FROM courier_turno WHERE estado ='C' AND id_linea='$id_linea'";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error 1'.mysql_error()));
$nfilas= mysql_num_rows($consulta);
echo $nfilas;
?>
