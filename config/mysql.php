<?php
//********************************
$conexion=mysql_connect (IPSERVIDORBD.":".PUERTO,USUARIOBD,CLAVEBD) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
//conexion a la base de datos
mysql_select_db (BD) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
?>