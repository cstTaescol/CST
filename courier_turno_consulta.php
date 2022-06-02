<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$botones="";
$cumplidos="";
$posicion="";
$totalpiezas=0;
$totalpeso=0;
$planilla_envio="";
$planilla_recepcion="";	
if(isset($_REQUEST["id_registro"]))
	{
		$id_turno=$_REQUEST["id_registro"];
		$sql="SELECT * FROM courier_turno WHERE id = '$id_turno'";
		$consulta=mysql_query($sql,$conexion) or die (exit('Error 1: '.mysql_error()));
		$fila=mysql_fetch_array($consulta);
		$no_turno=$fila["no_turno"];
		$date_creacion=$fila["date_creacion"];
		$date_inicio_atencion=$fila["date_inicio_atencion"];
		$date_fin_atencion=$fila["date_fin_atencion"];
		$estado=$fila["estado"];		

		// identificando courier
		$id_courier=$fila["id_courier"];
		$sql3="SELECT nombre FROM couriers WHERE id='$id_courier'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("Error 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$courier=$fila3["nombre"];

		//identificando la linea Usada
		$id_linea=$fila["id_linea"];
		$sql3="SELECT nombre FROM courier_linea WHERE id='$id_linea'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("Error 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$linea=$fila3["nombre"];				

		//Consulta estado y habilita boton de reactivacion de turnos perdidos
		$EstadoBoton='disabled="disabled"';
		switch ($fila['estado']) 
		{
			case 'C':
			  $estado="Esperando";			  
			break;

			case 'A':
			  $estado="Atendiendo";			  
			break;

			case 'F':
			  $estado="Finalizado";			  
			break;

			case 'P':
			  $estado="Perdido";			  
			  $EstadoBoton='';
			break;
		}
		
		//identificando usuario
		$id_funcionario_creacion=$fila["id_funcionario_creacion"];		
		$sql3="SELECT nombre FROM usuario WHERE id='$id_funcionario_creacion'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("Error 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$funcionario_creacion=$fila3["nombre"];
		
		//identificando usuario		
		$id_funcionario_atencion=$fila["id_funcionario_atencion"];			
		$sql3="SELECT nombre FROM usuario WHERE id='$id_funcionario_atencion'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("Error 6: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$funcionario_atencion=$fila3["nombre"];
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function abrir(url,alto,ancho)
	{
		alto = (alto) ? alto : '600';
		ancho = (ancho) ? ancho : '800';	
		popupWin = window.open(url,'','directories, status, scrollbars, resizable, dependent, width='+ancho+', height='+alto+', left=50, top=50')
	}
	
	//Funcion para confirmar la eliminacion de una guia
	function conf_eliminar(dato)
	{
	var respuesta=confirm('ATENCION: Confirma que, Desea Eliminar la Guia?');
	if (respuesta)
		{
			window.location="eliminar_guia1.php?id_guia="+dato;
		}
	}
</script>
</head>
<body>
<?php
require("menu.php");
//Privilegios Consultar Todo el Modulo
$id_objeto=51; 
include("config/provilegios_modulo.php");  
//---------------------------
?>
<p class="titulo_tab_principal">Descripcion del Turno</p>
<table align="center">
  <tr>
    <td align="center" class="celda_tabla_principal" >
    	<div class="letreros_tabla">Estado</div>
    	<font class="asterisco" size="+3"><i><?php echo $estado;?></i></font>
    </td>
    <td align="center" class="celda_tabla_principal" colspan="5"><div class="letreros_tabla">Reactivar</div>
        <button type="button" title="Volver a crear un turno perdido" onClick="openPopup('courier_turno_reactivacion.php?id_turno=<?php echo $id_turno ?>','new','700','450','scrollbars=1',true);" <?php  $id_objeto=147; include("config/provilegios_objeto.php");  echo $activacion ?> <?php echo $EstadoBoton; ?>>
          <img src="imagenes/recargar-act.png" title="Volver a crear un turno perdido">
        </button>
        <br>
        A partir de un turno perdido, crear un turno nuevo.
    </td>
  </tr>
</table>

<table align="center" width="80%">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Turno No.</div></td>
    <td class="celda_tabla_principal celda_boton"><h1><?php echo $no_turno;?></h1></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Courier</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $courier;?></td>
  </tr>
   <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">L&iacute;nea</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $linea; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Usuario Creador</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $funcionario_creacion; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha de Creaci&oacute;n</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $date_creacion;?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Usuario Operador L&iacute;nea</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $funcionario_atencion;  ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha de Inicio de Atenci&oacute;n</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $date_inicio_atencion; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha de Finalizaci&oacute;n de Atenci&oacute;n</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $date_fin_atencion; ?></td>
  </tr>  
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Gu&iacute;a Asociadas</div></td>
    <td class="celda_tabla_principal celda_boton">
		<table border="1" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">No Guia</div>
				<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div>
				<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div>
				<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Ver</div>
			</tr>    	
	    	<?php 
				//identificando funcionario que intervienen con la guia
				$metadata="";
				$sql2="SELECT id_guia 
					   FROM courier_turno_guia  
					   WHERE id_turno ='$id_turno'					   
					   ";
				$consulta2=mysql_query ($sql2,$conexion) or die ("Error 7: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila2=mysql_fetch_array($consulta2))
				{					
					$id_guia=$fila2['id_guia'];
					$sql3="SELECT master,piezas,peso 
						   FROM guia  
						   WHERE id ='$id_guia'							   
						   ";
					$consulta3=mysql_query ($sql3,$conexion) or die ("Error 8: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
					$fila3=mysql_fetch_array($consulta3);						
					$metadata .= '
									<tr>
										<td align="right">'.$fila3['master'].'</td>
										<td align="right">'.number_format($fila3['piezas'],0,",",".").'</td>
										<td align="right">'.number_format($fila3['peso'],0,",",".").'</td>
										<td><button type="button" onclick="document.location=\'consulta_guia_courier.php?id_guia='.$id_guia.'\'"><img src="imagenes/buscar-act.png"></button></td>
									</tr>
									';			    		
				}
				echo $metadata; 
	    	?>       
    	</table> 	    

    </td>
  </tr>  
</table>
</body>
</html>
<?php
mysql_free_result($consulta);
//mysql_free_result($consulta2);
mysql_free_result($consulta3);
mysql_close($conexion);
?>
<script language="javascript">
function openPopup(url,name,w,h,props,center)
{
	l=18;t=18
	if(center){l=(screen.availWidth-w)/2;t=(screen.availHeight-h)/2}
	url=url.replace(/[ ]/g,'%20')
	popup=window.open(url,name,'left='+l+',top='+t+',width='+w+',height='+h+',scrollbars=1'+((props)?','+props:''))
	props=props||''
	if(props.indexOf('fullscreen')!=-1){popup.moveTo(0,0);popup.resizeTo(screen.width,screen.height)}
	popup.focus()
}
</script>
