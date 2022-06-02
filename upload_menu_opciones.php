<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if(isset($_GET['tipo']))
{
	$tipo=$_GET['tipo'];
	switch($tipo)
	{
		case "PLANILLA_CARGUE":
			$titulo="Planilla de Cargue";
			$privilegio_objeto1=111;
			$privilegio_objeto2=112;
		break;

		case "PLANILLA_DESPALETIZAJE":
			$titulo="Planilla de Despaletizaje";
			$privilegio_objeto1=114;
			$privilegio_objeto2=115;
		break;
	}
	
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
        <p class="titulo_tab_principal"><?php echo $titulo; ?></p>
        <table width="600" align="center" class="decoracion_tabla">
          <tr>
            <td width="70" class="celda_tabla_principal">
                <button name="terminar" type="button" <?php  $id_objeto=$privilegio_objeto1; include("config/provilegios_objeto.php");  echo $activacion ?> onClick="abrir('ajax-upload_anexos/index.php?tipo=<?php echo $tipo ?>',480,680)">
                    <img src="imagenes/aceptar-act.png" title="Subir un archivo" />
                 </button>
            </td>
            <td width="530" class="celda_tabla_principal celda_boton">Ingresar <?php echo $titulo; ?></td>
          </tr>
          <tr>
            <td class="celda_tabla_principal">
                <button name="terminar" type="button" <?php  $id_objeto=$privilegio_objeto2; include("config/provilegios_objeto.php");  echo $activacion ?> onclick="document.location='upload_consulta_x_fecha.php?tipo=<?php echo $tipo ?>'">
                    <img src="imagenes/buscar-act.png" title="Consultar los Archivos subidos" />
                </button>
          	</td>
          	<td class="celda_tabla_principal celda_boton">Consultar <?php echo $titulo; ?></td>
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