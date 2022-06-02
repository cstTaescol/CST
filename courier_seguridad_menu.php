<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<style type="text/css">
		.tamaño{
			height: 70px;
			width: 70px;
		}
	</style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />
    <script src="js/srcNewCalendar/js/jscal2.js"></script>
    <script src="js/srcNewCalendar/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/steel/steel.css" />    
    <title>Seguridad Courier</title>
</head>
<body>
<?php
	$id_usuario=$_SESSION['id_usuario'];
	$fecha=date("Y").date("m").date("d");
	$hora=date("H:i:s");
	$id_guia=$_REQUEST['id_guia']; 
	//cargar observaciones de la guia

	//Carga datos de la Guia
	$sql="SELECT master, id_consignatario FROM guia WHERE id='$id_guia'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$master=$fila["master"];
?>
<form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" onsubmit="return validar();" >
	<p class="titulo_tab_principal">Datos de Seguridad</p>
	<p class="asterisco" align="center">Gu&iacute;a: <?php echo $master?></p>
	<table align="center">
    	<!-- TITULOS -->
    	<tr>
        	<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Veh&iacute;culos</div></td>
        	<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Personal</div></td>
        	<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Entrega</div></td>
        </tr> 
        <!-- BOTONES -->
        <tr>
            <td class="celda_tabla_principal celda_boton" align="center">
                <button type="button" name="transportador" id="transportador" tabindex="1" class="tamaño" title="Datos del Vehiculo" onclick="document.location='courier_seguridad_transportador.php?id_guia=<?php echo $id_guia ?>'" <?php  $id_objeto=130; include("config/provilegios_objeto.php");  echo $activacion ?>> 
                    <img src="imagenes/camion.png"  align="absmiddle"/> 
                </button>            	            	
            </td>                 
            <td class="celda_tabla_principal celda_boton" align="center">
                <button type="button" name="personal" id="personal"  tabindex="2" class="tamaño"  title="Datos del Personal Autorizado" onclick="document.location='courier_seguridad_funcionario.php?entidad=7&tipo=C&id_guia=<?php echo $id_guia ?>'" <?php  $id_objeto=131; include("config/provilegios_objeto.php");  echo $activacion ?>> 
                    <img src="imagenes/sesion.png" align="absmiddle"/> 
                </button>            	            	
            </td>                 
            <td class="celda_tabla_principal celda_boton" align="center">
                <button type="button" name="finalizacion" id="finalizacion" tabindex="3" class="tamaño" title="Finalizaci&oacute;n" onclick="document.location='courier_seguridad_entrega.php?id_guia=<?php echo $id_guia; ?>'" <?php  $id_objeto=132; include("config/provilegios_objeto.php");  echo $activacion ?> > 
                    <img src="imagenes/check_blue.png" align="absmiddle" /> 
                </button>            	            	
            </td>                 
        </tr>
        <!-- DESCRIPCIONES -->
        <tr>
            <td class="celda_tabla_principal celda_boton" style="text-align:left;">
            	Asigne los veh&iacute;culos que realizar&aacute;n el transporte de la gu&iacute;a.
            </td>                         	
            <td class="celda_tabla_principal celda_boton" style="text-align:left;">
            	Asigne los datos de la persona autorizada para el ingreso a verificaci&oacute;n de la carga.
            </td>                         	
            <td class="celda_tabla_principal celda_boton" style="text-align:left;">
            	Confirmaci&oacute;n del proceso de entrega de la gu&iacute;a en los veh&iacute;culos del cliente.
            </td>                         	
        </tr>
    </table>
</form>
</body>
</html>
