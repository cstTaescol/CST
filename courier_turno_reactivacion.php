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
$id_turno=$_REQUEST['id_turno']; 

//Carga datos del turno
$sql="SELECT no_turno,id_courier,id_linea FROM courier_turno WHERE id='$id_turno'";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error 1'.mysql_error()));
$fila=mysql_fetch_array($consulta);
$no_turno=$fila["no_turno"];
$id_courier=$fila["id_courier"];
$id_linea=$fila["id_linea"];


//Cuando se oprima el boton de guardar
if(isset($_REQUEST['linea_seleccionada']))
{
	$linea_seleccionada=$_REQUEST['linea_seleccionada'];	
	$fecha=date("Y-m-d");
	$date_creacion= date("Y-m-d H:i:s");
	$hora=date("H:i:s");
	$id_usuario=$_SESSION["id_usuario"];

	//consulta la cantidad de Turnos ya existen en esa misma fecha
	$sql1="SELECT id FROM courier_turno WHERE date_creacion LIKE '%$fecha%'";
	$consulta1=mysql_query($sql1,$conexion) or die (exit('Error 2'.mysql_error()));
	$catidadTurnos=mysql_num_rows($consulta1) + 1;
	$no_turno_nuevo="D".date("d")."-".$catidadTurnos;

	//Consulta Auxiliar
	$sql2="SELECT nombre FROM courier_linea WHERE id ='$linea_seleccionada'";
	$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 3'.mysql_error()));
	$fila2 = mysql_fetch_array($consulta2);
	$nombre_linea_seleccionada=$fila2['nombre'];


	//Insertamos datos nuevos
	$sql_update="INSERT INTO courier_turno (
					 				no_turno,
					  				id_courier,
					  				date_creacion,
					  				id_linea,
					  				id_funcionario_creacion
					  			  	) 
							VALUE (
									'$no_turno_nuevo',
								   	'$id_courier',
								   	'$date_creacion',
								   	'$linea_seleccionada',
								   	'$id_usuario'
								   )";
	mysql_query($sql_update,$conexion) or die (exit('Error 4'.mysql_error()));
	$id_registro=mysql_insert_id($conexion);
 
	//Actualizacion de asignacion de guias al turno
	$sql_update="UPDATE courier_turno_guia SET id_turno = '$id_registro' WHERE id_turno ='$id_turno'";
	mysql_query($sql_update,$conexion) or die (exit('Error 5'.mysql_error()));

	//Actualiacion de las guias y registro en historial
	$sql3="SELECT id_guia FROM courier_turno_guia WHERE id_turno = '$id_registro'";
	$consulta3=mysql_query ($sql3,$conexion) or die (exit('Error 6'.mysql_error()));
	while($fila3=mysql_fetch_array($consulta3))
	{
		$id_guia=$fila3["id_guia"];
		//Actualiza la info de la guia
		$sql_update="UPDATE guia SET courier_id_linea='$linea_seleccionada' WHERE id = '$id_guia'";
		mysql_query($sql_update,$conexion) or die (exit('Error 7'.mysql_error()));

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
												   'Nuevo turno Re-Asignado No:<br>$no_turno_nuevo<br>Linea:<br>$nombre_linea_seleccionada',
												   '1',
												   '$id_usuario')";
		mysql_query($sql_trak,$conexion) or die (exit('Error 8'.mysql_error()));

	}
		
	//. javascript que  permite actualizar la ventana padre y cerrar la propia
	?>
	<script language="javascript">
			alert("Registro Exitoso");
			//window.opener.location.reload();
			window.opener.location="courier_turno_consulta.php?id_registro=<?php echo $id_registro;?>";
			self.close();
	</script>';	
	<?php	
}

?>
<form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
	<p class="titulo_tab_principal">Reactivaci&oacute;n de Turno</p>
	<div align="center" style="font-size: 45px"><?php echo $no_turno ?></div>
	<table align="center">
    	<tr>
        	<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">L&iacute;nea</div></td>
	        <td class="celda_tabla_principal celda_boton" colspan="2">
	          <select name="linea_seleccionada" id="linea_seleccionada" tabindex="1">	              
	              <?php
	                    $sql="SELECT id,nombre FROM courier_linea WHERE estado='A'";
	                    $consulta=mysql_query ($sql,$conexion) or die (exit('Error 9'.mysql_error()));
	                    while($fila=mysql_fetch_array($consulta))
	                    {
	                        if($id_linea == $fila['id'])
	                            $seleccion='selected="selected"';
	                        else
	                          $seleccion='';
	                        echo '<option value="'.$fila['id'].'" '.$seleccion.'>'.$fila['nombre'].'</option>';
	                    }
	              ?>
	          </select>         	
	        </td>            
        </tr>
    </table>
    <input type="hidden" name="id_turno" value="<?php echo $id_turno ?>" />
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
	    <div id="datos_recuperados" class="celda_tabla_principal" style="overflow:scroll; width:410px; height:110px; margin-left: auto; margin-right: auto;">
	    	Cree un nuevo turno usando la informaci&oacute;n de un turno perdido.<br><br>
	    	Escoja la l&iacute;nea y oprima el bot&oacute;n de Guardar.
	    </div>
</form>
</body>
</html>
