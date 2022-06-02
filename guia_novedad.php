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
    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />
    <title>NOVEDADES DE GU&Iacute;A</title>
</head>
<body>
<?php
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$id_guia=$_REQUEST['id_guia']; 
//cargar observaciones de la guia

//Carga datos de la Guia
$sql="SELECT observaciones FROM guia WHERE id='$id_guia'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$observaciones=$fila["observaciones"];

//Cuando se oprima el boton de guardar
if(isset($_REQUEST['registro_novedad']))
{
	$registro_novedad=strtoupper($_REQUEST['registro_novedad']);
	$id_usuario=$_SESSION['id_usuario'];
	$almacenamiento = $observaciones."<br>-". $registro_novedad;
		
	//Actualiza la info de la guia
	$sql_update="UPDATE guia SET observaciones='$almacenamiento' WHERE id='$id_guia'";
	mysql_query($sql_update,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	
	//Crea registro en el historial
	$sql_trak="INSERT INTO tracking (id_guia,
									 fecha_creacion,
									 hora,
									 evento,
									 tipo_tracking,
									 id_usuario) 
										VALUE ('$id_guia',
											   '$fecha',
											   '$hora',
											   'NOVEDAD REGISTRADA:<br>$registro_novedad',
											   '1',
											   '$id_usuario')";
	mysql_query($sql_trak,$conexion) or die (mysql_error());
		
	//. javascript que  permite actualizar la ventana padre y cerrar la propia
	?>
	<script language="javascript">
			alert("Registro Exitoso");
			window.opener.location.reload();
			self.close();
	</script>';	
	<?php	
}
?>
<form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
	<p class="titulo_tab_principal">Novedad de la Guia</p>
	<table align="center">
    	<tr>
        	<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Observaciones</div></td>
            <td>
	            <textarea name="registro_novedad" id="registro_novedad" cols="60" rows="4" tabindex="1" /></textarea>
					<script language="javascript">
                        document.getElementById("registro_novedad").focus();
                    </script>
	            <input type="hidden" name="id_guia" value="<?php echo $id_guia ?>" />
            </td>            
        </tr>
    	<tr>
        	<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Adjunto</div></td>
            <td class="celda_tabla_principal celda_boton">
                <button type="button" name="agregar" id="agregar" onClick="abrir('ajax-upload_anexos/index.php?id_guia=<?php echo $id_guia ?>&tipo=NOVEDAD',480,680)">
                    <img src="imagenes/agregar-act.png" title="Agregar" /><br />
                    Agregar
                </button>            
            </td>            
        </tr>
        
    </table>
    <table width="450px" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="reset" name="reset" id="reset" tabindex="3">
                <img src="imagenes/descargar-act.png" title="Limpiar" />
            </button>
            <button type="submit" name="guardar" id="guardar" tabindex="2">
                <img src="imagenes/guardar-act.png" title="Guardar" />
            </button>
          </td>
        </tr>
     </table>    
	    <div id="datos_recuperados" class="celda_tabla_principal" style="overflow:scroll; width:410px; height:150px; margin-left: auto; margin-right: auto;">
	    	<?php echo $observaciones ?>
	    </div>
</form>
</body>
</html>
<script language="javascript">
	function abrir(url,alto,ancho)
	{
		popupWin = window.open(url,'Registro_Fotografico','directories, status, scrollbars, resizable, dependent, width='+ancho+', height='+alto+', left=50, top=50')
		//  popupWin = window.open('pdf_remesa.php','nombre_ventana','menubar, toolbar, location, directories, status, scrollbars, resizable, dependent, width=640, height=480, left=0, top=0')
	}
</script>