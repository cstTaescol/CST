<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if(isset($_REQUEST["id_guia"]))
	{
		$id_guia=$_REQUEST["id_guia"];
		$sql="SELECT * FROM guia WHERE id = '$id_guia'";
		$consulta=mysql_query($sql,$conexion) or die (exit('Error 1: '.mysql_error()));
		$fila=mysql_fetch_array($consulta);
		$observaciones=$fila["observaciones"];
		$piezas=$fila["piezas"];
		$peso=$fila["peso"];
		$courier_docAprehension=$fila["courier_docAprehension"];	
		$hija=$fila["hija"];
		// identificando guia master		
		$id_master=$fila["master"];
		$sql3="SELECT master FROM guia WHERE id='$id_master'";
		$consulta3=mysql_query ($sql3,$conexion) or die (exit('Error 2: '.mysql_error()));
		$fila3=mysql_fetch_array($consulta3);
		$master=$fila3["master"];
		// identificando el tipo de actuación aduanera
		$id_tipo_actuacion_aduanera=$fila["id_tipo_actuacion_aduanera"];	
		$sql3="SELECT nombre FROM tipo_actuacion_aduanera WHERE id='$id_tipo_actuacion_aduanera'";
		$consulta3=mysql_query ($sql3,$conexion) or die (exit('Error 3: '.mysql_error()));
		$fila3=mysql_fetch_array($consulta3);
		$actuacion_aduanera=$fila3["nombre"];
		// identificando la posicion de la carga en el rack
		$courier_id_posicion=$fila["courier_id_posicion"];	
		$sql3="SELECT nombre FROM courier_posiciones WHERE id='$courier_id_posicion'";
		$consulta3=mysql_query ($sql3,$conexion) or die (exit('Error 3: '.mysql_error()));
		$fila3=mysql_fetch_array($consulta3);
		$posicion=$fila3["nombre"];
		// identificando columna de estado
		$id_tipo_bloqueo=$fila["id_tipo_bloqueo"];
		$sql3="SELECT nombre,descripcion FROM tipo_bloqueo_guia WHERE id='$id_tipo_bloqueo'";
		$consulta3=mysql_query ($sql3,$conexion) or die (exit('Error 3: '.mysql_error()));
		$fila3=mysql_fetch_array($consulta3);
		$estado=$fila3["nombre"];
		// identificando columna de estado
		$id_entidad=$fila["courier_id_entidad"];
		$sql3="SELECT nombre FROM courier_entidades WHERE id='$id_entidad'";
		$consulta3=mysql_query ($sql3,$conexion) or die (exit('Error 3: '.mysql_error()));
		$fila3=mysql_fetch_array($consulta3);
		$entidad=$fila3["nombre"];

		//Identificacion de Estado para Eliminacion de Guía
		switch ($id_tipo_bloqueo)
		{
			case (1):
				$activacionEliminar="";		
			break;

			default:
				$activacionEliminar='disabled="disabled"';
			break;
		}
		
		// identificando entrega		
		$sql3="SELECT id FROM courier_despacho_hija  WHERE id_guia ='$id_guia'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 7: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$id_despacho=$fila3["id"];	
		if ($id_despacho == "")
		{
			$contentclic='"alert(\'No tiene Entregas asociadas\')"';
		}
		else
		{
			$contentclic='"document.location=\'courier_despacho_opciones_hija.php?id_registro='.$id_despacho.'\'"';
		}			
	}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
	require("menu.php");
	//Privilegios Consultar Todo el Modulo
	$id_objeto=122; 
	include("config/provilegios_modulo.php");  
	//---------------------------
?>
<p class="titulo_tab_principal">Descripcion de la Guia</p>
<table align="center">
  <tr>
    <td align="center" class="celda_tabla_principal">
    	<div class="letreros_tabla">Estado</div>
    	<font class="asterisco" size="+3"><i><?php echo $estado;?></i></font>
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Eliminar</div>
		<button <?php  $id_objeto=125; include("config/provilegios_objeto.php");  echo $activacion; echo $activacionEliminar; ?> onClick="conf_eliminar(<?php echo $id_guia?>);">
		  <img src="imagenes/eliminar-act.png" title="Eliminar esta Gu&iacute;a" />
		</button>	    
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Modificar</div>
		<button <?php  $id_objeto=124; include("config/provilegios_objeto.php");  echo $activacion; echo $activacionEliminar ?> onClick="document.location='courier_guia_hija_modificar.php?id_guia=<?php echo $id_guia ?>'">
			<img src="imagenes/settings-act.png" title="Modificar esta Gu&iacute;a"/>
		</button>	    
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Historial</div>      
	    <button <?php  $id_objeto=128; include("config/provilegios_objeto.php");  echo $activacion ?> onClick="document.location='consulta_historial.php?id_guia=<?php echo $id_guia?>'">
	        <img src="imagenes/buscar-act.png" title="Muestra el Historial de movimiento de la Gu&iacute;a"/>
	    </button>					
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Novedad</div>      
        <button type="button" title="Agregar una NOVEDAD a la Gu&iacute;a" onClick="openPopup('guia_novedad.php?id_guia=<?php echo $id_guia ?>','new','700','450','scrollbars=1',true);" <?php  $id_objeto=126; include("config/provilegios_objeto.php");  echo $activacion ?>>
          <img src="imagenes/alerta-act.png" title="Agregar una Novedad a la Gu&iacute;a">
        </button>
    </td>
  </tr>
</table>

<table align="center">
  <tr>
    <td width="180" class="celda_tabla_principal"><div class="letreros_tabla">Master</div></td>
    <td width="400" class="celda_tabla_principal celda_boton">
    	<?php echo $master?>
		<button type="button" title="Acceder a la INFORMACION de la Gu&iacute;a Master" onclick="document.location='consulta_guia_courier.php?id_guia=<?php echo $id_master ?>'">
			<img src="imagenes/info.png" height="29" width="33" />
		</button>				    	
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Hija</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $hija; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $piezas; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $peso; ?> Kg.</td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Actuaci&oacute;n Aduanera</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $actuacion_aduanera; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Actuaci&oacute;n Aduanera</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $courier_docAprehension; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Ubicaci&oacute;n</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $posicion; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Funcionarios <?php echo $entidad ?> Que Intervienen</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<?php 
			//identificando funcionario que intervienen con la guia
			$nombre_funcionarios="";
			$sql2="SELECT f.nombre, f.no_documento 
				   FROM courier_funcionario f 
				   LEFT JOIN courier_funcionarios_guia_hija g 
				   ON f.id = g.id_funcionario 
				   WHERE g.id_guia='$id_guia'";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 7: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			while($fila2=mysql_fetch_array($consulta2))
			{
			  $nombre_funcionarios .= "- " . $fila2['nombre'] . ", Documento No. " . $fila2['no_documento'] . "<br>";
			}
    		echo $nombre_funcionarios; 
    	?>    
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Datos de la Entrega</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<button type="button" onclick=<?php echo $contentclic ?>>
    		<img src="imagenes/imprimir-act.png">    		
    	</button>
    </td>
  </tr>
  <tr>
    <td height="47" class="celda_tabla_principal"><div class="letreros_tabla">Observacion / Novedad</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $observaciones; ?></td>
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
			window.location="eliminar_guia_courier.php?id_guia="+dato;
		}
	}
</script>