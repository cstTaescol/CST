<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_guia=$_REQUEST['id_guia'];
$_SESSION["id_guia"]=$id_guia;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />
    <title>Registro Fotografico Bodega</title>
</head>
<body>
<p class="titulo_tab_principal">Registro Fotogr&aacute;fico</p>
<hr />

<table width="90%" align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Foto</div></td>
    <td class="celda_tabla_principal celda_boton">
		<input type="file" id="archivo" style="background:url(imagenes/camara.png); background-repeat:no-repeat; height:170px;" />  
        <script src="js/upload_foto_camara.js"></script>
        <div id="mensaje"></div>  
    </td>
  </tr>
</table>
<hr />
<em>Seleccione la Foto de la Carga</em>
</body>
</html>

