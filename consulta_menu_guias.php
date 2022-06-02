<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="js/spinmenu.js"></script>
</head>

<body>
<?php
require("menu.php");
//Privilegios Consultar Todo el Modulo
$id_objeto=63; 
include("config/provilegios_modulo.php");  
//---------------------------
?>
<p class="titulo_tab_principal">Consulta de Guias</p>
<p align="left"><font size="+2" color="#0033CC"><strong>Seleccione la consulta que desea cargar...</strong></font></p>
<hr />
<br />
<script type="text/javascript">
/*
3D Spin Menu- By Petre Stefan (http://www.eyecon.ro) w/ changes by JK
Visit JavaScript Kit (http://www.javascriptkit.com) for script
Keep this notice intact!
*/
eye.isVertical = 0; //if it's vertical or horizontal [0|1]
eye.x = 400; //  eje X de posicionamiento en pantalla
eye.y = 10; // eje Y de posicionamiento en pantalla
eye.w = 200; // Tamaño de cada letrero
eye.h = 100; // Separacion entre los botones y los letreros
eye.r = 140; // Radio de espacio entre Letreros
eye.v = 20; // velocidad (0 = maximo)
eye.s = 8; // scale in space (for 3D effect)
eye.color = '#ffffff'; // normal text color
eye.colorover = '#ffffff'; // mouseover text color
eye.backgroundcolor = '#0099ff'; // normal background color 
eye.backgroundcolorover = '#990000'; // mouseover background color
eye.bordercolor = '#000000'; //border color
eye.fontsize = 20; // font size
eye.fontfamily = 'Arial'; //font family
if (document.getElementById){
document.write('<div id="spinanchor" style="height:'+eval(eye.h+20)+'"></div>')
eye.anchor=document.getElementById('spinanchor')
eye.spinmenu();
eye.x+=getposOffset(eye.anchor, "left") //relatively position it
eye.y+=getposOffset(eye.anchor, "top")  //relatively position it

//menuitem:   eye.spinmenuitem(text, link, target)
eye.spinmenuitem("Consulta General","consulta_guiasXfecha.php");
eye.spinmenuitem("Por Numero de Guia","consulta_guia_buscar.php");
eye.spinmenuitem("Por Numero de MASTER","consulta_consolidado1.php");
eye.spinmenuitem("Guias Sin Asignar","consulta_guias_noasignadas.php");
eye.spinmenuitem("Guias Asociadas a Manifiesto","consulta_guiasXmanifiesto.php");
eye.spinmenuitem("Guias Facturadas","consulta_guias_facturadas.php");



eye.spinmenuclose();
}
</script>
<br />
<hr />
<font color="#009933"><i>Oprima los botones <font color="#0066CC"><strong>&lt;&lt; o &gt;&gt;</strong> </font>para desplazarse por las diferentes consultas de gu&iacute;a.</i></font>    

<br />
<br />
<br />
<br />
<br />
<br />

</body>
</html>