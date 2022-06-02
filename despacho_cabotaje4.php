<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_cabotaje=$_GET["id_registro"];
?>
<html>
<head>
<script language="javascript">
function abrir(url)
{
	popupWin = window.open(url,'','directories, status, scrollbars, resizable, dependent, width=640, height=480, left=100, top=100')
	//  popupWin = window.open('pdf_remesa.php','nombre_ventana','menubar, toolbar, location, directories, status, scrollbars, resizable, dependent, width=640, height=480, left=0, top=0')
}
//Funcion para confirmar la eliminacion de un despacho
function conf_eliminar(url)
{
var respuesta=confirm('ATENCION: Confirma que Desea Eliminar el Despacho?,  Las GUIAS despachadas seran Retornadas al INVENTARIO');
if (respuesta)
	{
		window.location="eliminar_cabotaje1.php?id="+url;
	}
}
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Reporte de Cabotaje</p>
<p class="asterisco" align="center">Despacho identificado con exito... N&uacute;mero: <font size="+2" color="006600"><?php echo $id_cabotaje; ?></font></p>
<p align="center">Ahora puede proceder a imprimir el despacho.</p>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button name="terminar" type="button" onClick="document.location='base.php'">
                <img src="imagenes/aceptar-act.png" title="Terminar" /><br />
              <strong>Terminar</strong>
            </button>
            
            <button name="modificar" type="button" onClick="document.location='modificar_cabotaje1.php?id=<?php echo $id_cabotaje ?>'">
                <img src="imagenes/settings-act.png" title="Modificar" /><br />
              <strong>Modificar</strong>
            </button>
            
            <button name="eliminar" type="button" onClick="conf_eliminar(<?php echo $id_cabotaje; ?>);">
                <img src="imagenes/eliminar-act.png" title="Eliminar" /><br />
              <strong>Eliminar</strong>
            </button>
            
            <button name="imprimir" type="button" onClick="abrir('pdf_cabotaje.php?id_cabotaje=<?php echo $id_cabotaje ?>')">
                <img src="imagenes/imprimir-act.png" title="Imprimir" /><br />
              <strong>Imprimir</strong>
            </button>
      </td>
    </tr>
</table>    
</body>
</html>