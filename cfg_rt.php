<?php
session_start(); /*     "This product includes PHP software, freely available from */
include("config/configuracion.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
	body,td,th {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 14px;
		color: #0F0;
		font-weight: bold;
	}
	body {
		background-color: #000;
	}
</style>
</head>
<body>
    <p align="center"><font size="+2">CONFIGURADOR DEL SISTEMA</font><br></p>
    <hr>
        <div id="configurador">
        	<input type="button" onClick="document.location='config/mod_cfg.php'" value="Inicio"> ... CONFIGURADOR
        </div>
        <div id="servidor_de_aplicacion">
        	<input type="button" onClick="document.location='<?php echo PROGINI ?>'" value="Inicio"> ... APLICACION
        </div>
        <div id="bk">
        	<input type="button" onClick="abrir('cfg-_bakup.php');" value="Inicio"> ... BACKUP BASE DE DATOS
        </div> 
        <div id="monitoreo">
        	<input type="button" onClick="document.location='monitor.php'" value="Inicio"> ... MONITOR
        </div> 		       
    <br>
    <br>
    <hr>
</body>
</html>
<script language="javascript">
function abrir(url)
{
	popupWin = window.open(url,'','directories, status, scrollbars, resizable, dependent, width=640, height=480, left=100, top=100')
}
</script>

