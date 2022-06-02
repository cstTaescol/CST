<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$guia=$_REQUEST['ta'];
$id_guia=$_REQUEST['id_guia'];
$boton=$_REQUEST['boton'];
switch($boton)
{
	case "foto_bodega":
		$mensaje="Bodega";
	break;

	case "foto_despacho":
		$mensaje="Despachos";
	break;

	case "foto_seguridad":
		$mensaje="Seguridad";
	break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Registro Fotografico</p>
<p class="asterisco" align="center">Fotos correspondientes a la guia No. <font color="red"><?php echo $guia ?></font></p>
<p class="asterisco" align="center">Procesando fotos del departamente de <font color="#0066FF"><?php echo $mensaje ?></font></p>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
        <button type="button" name="agregar" id="agregar" onClick="abrir('ajax-upload/index.php?id_guia=<?php echo $id_guia ?>&tipo=<?php echo $boton ?>',480,680)">
            <img src="imagenes/agregar-act.png" title="Agregar" /><br />
            Agregar
        </button>
        <button type="button" name="ver" id="ver" onClick="abrir('galeria/galeria.php?id_guia=<?php echo $id_guia ?>&tipo=<?php echo $boton ?>',600,800)">
            <img src="imagenes/buscar-act.png" title="Ver" /><br />
            &nbsp;Ver&nbsp;
        </button>
        <button type="submit" name="eliminar" id="eliminar" onClick="abrir('registro_fotografico_eliminar.php?id_guia=<?php echo $id_guia ?>&tipo=<?php echo $boton ?>',600,800)">
            <img src="imagenes/eliminar-act.png" title="Eliminar" /><br />
            Eliminar
        </button>
      </td>
    </tr>
 </table>
</body>
</html>
<script language="javascript">
	function abrir(url,alto,ancho)
	{
		popupWin = window.open(url,'Registro_Fotografico','directories, status, scrollbars, resizable, dependent, width='+ancho+', height='+alto+', left=50, top=50')
		//  popupWin = window.open('pdf_remesa.php','nombre_ventana','menubar, toolbar, location, directories, status, scrollbars, resizable, dependent, width=640, height=480, left=0, top=0')
	}
</script>