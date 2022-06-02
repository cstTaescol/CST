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
if(isset($_REQUEST["id_guia"]))
	{
		$id_guia=$_REQUEST["id_guia"];
		$sql="SELECT * FROM guia WHERE id = '$id_guia'";
		$consulta=mysql_query($sql,$conexion) or die (exit('Error 1: '.mysql_error()));
		$fila=mysql_fetch_array($consulta);
		$master=$fila["master"];
		$courier_1178=$fila["courier_1178"];
		$observaciones=$fila["observaciones"];
		$courier_dato_llegada=$fila["courier_dato_llegada"];
		$courier_dato_inicio=$fila["courier_dato_inicio"];		
		$courier_dato_fin=$fila["courier_dato_fin"];
		$piezas=$fila["piezas"];
		$peso=$fila["peso"];
		$courier_docAprehension=$fila["courier_docAprehension"];		

		// identificando aerolinea
		$aerolinea=$fila["id_aerolinea"];
		$sql3="SELECT nombre FROM aerolinea WHERE id='$aerolinea'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$aerolinea=$fila3["nombre"];

		//identificando consignatario
		$consignatario=$fila["id_consignatario"];
		$sql3="SELECT nombre FROM couriers WHERE id='$consignatario'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$consignatario=$fila3["nombre"];
		
		//identificando la linea Usada
		$courier_id_linea=$fila["courier_id_linea"];
		$sql3="SELECT nombre FROM courier_linea WHERE id='$courier_id_linea'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$linea=$fila3["nombre"];		

		// identificando columna de estado
		$id_tipo_bloqueo=$fila["id_tipo_bloqueo"];
		$sql3="SELECT nombre,descripcion FROM tipo_bloqueo_guia WHERE id='$id_tipo_bloqueo'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$estado=$fila3["nombre"];

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
		
		// identificando despacho		
		$sql3="SELECT id FROM courier_despacho  WHERE id_guia ='$id_guia'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 7: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$id_despacho=$fila3["id"];	
		if ($id_despacho == "")
		{
			$contentclic='"alert(\'No tiene Entregas asociadas\')"';
		}
		else
		{
			$contentclic='"document.location=\'courier_despacho_opciones.php?id_registro='.$id_despacho.'\'"';
		}	
		
		// Identificando Facturas de la Guia
		$id_objeto=127; 
		include("config/provilegios_objeto.php");  
		if ($activacion =="")
		{
			$sql3="SELECT * FROM guia_factura WHERE id_guia=$id_guia";
			$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 6: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$impresion_factura='
				<table border="1" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">No Factura</div>
						<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Valor</div>
						<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">IVA</div>
						<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Fecha Factura</div>
						<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Facturado A:</div>
					</tr>
			';
			while ($fila3=mysql_fetch_array($consulta3))
			{
				$nfactura=$fila3["nfactura"];
				$valor_factura=$fila3["valor_factura"];
				$iva=$fila3["iva"];
				$fecha_factura=$fila3["fecha_factura"];
				$facturadoa=$fila3["facturadoa"];
				
				$impresion_factura .='
					<tr>
						<td align="right">'.$nfactura.'</td>
						<td align="right">'.number_format($valor_factura,0,",",".").'</td>
						<td align="right">'.number_format($iva,0,",",".").'</td>
						<td align="center">'.$fecha_factura.'</td>
						<td>'.$facturadoa.'</td>
					</tr>
				';
			}
			$impresion_factura .= '</table>';
		}
		else
		{
			$impresion_factura="";
		}		
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
			window.location="eliminar_guia_courier.php?id_guia="+dato;
		}
	}
</script>
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
      <button <?php  $id_objeto=124; include("config/provilegios_objeto.php");  echo $activacion; echo $activacionEliminar; ?> onClick="document.location='courier_guia_modificar.php?id_guia=<?php echo $id_guia ?>'">
          <img src="imagenes/settings-act.png" title="Modificar esta Gu&iacute;a"/>
    </button>	    
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Historial</div>      
    <button <?php  $id_objeto=128; include("config/provilegios_objeto.php");  echo $activacion ?> onClick="document.location='consulta_historial.php?id_guia=<?php echo $id_guia?>'">
          <img src="imagenes/buscar-act.png" title="Muestra el Historial de movimiento de la Gu&iacute;a"/>
    </button>					
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Act.Aduanera</div>      
    <button <?php  $id_objeto=129; include("config/provilegios_objeto.php");  echo $activacion ?> onClick="document.location='courier_registro_hija.php?id_guia=<?php echo $id_guia?>'">
          <img src="imagenes/quitar_link-act.png" title="Creaci&oacute;n de Gu&iacute;as Hijas con Actuaci&oacute;n Aduanera">
    </button>					
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Novedad</div>      
        <button type="button" title="Agregar una NOVEDAD a la Gu&iacute;a" onClick="openPopup('guia_novedad.php?id_guia=<?php echo $id_guia ?>','new','700','450','scrollbars=1',true);" <?php  $id_objeto=126; include("config/provilegios_objeto.php");  echo $activacion ?>>
          <img src="imagenes/alerta-act.png" title="Agregar una Novedad a la Gu&iacute;a">
        </button>
    </td>
  </tr>
</table>

<table align="center" width="80%">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $aerolinea;?></td>
  </tr>
  <tr >
    <td width="180" class="celda_tabla_principal"><div class="letreros_tabla">Plan&iacute;lla 1178</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $courier_1178;?></td>
  </tr>
  <tr >
    <td width="180" class="celda_tabla_principal"><div class="letreros_tabla">Master</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $master;?></td>
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
    <td class="celda_tabla_principal"><div class="letreros_tabla">Courier</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $consignatario; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Datos de Llegada</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $courier_dato_llegada; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Datos de Inicio Revisi&oacute;n</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $courier_dato_inicio; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Datos de Finalizaci&oacute;n</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $courier_dato_fin; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Funcionarios Dian que Revisaron</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<?php 
			//identificando funcionario que intervienen con la guia
			$nombre_funcionarios="";
			$sql2="SELECT f.nombre, f.no_documento 
				   FROM courier_funcionario f 
				   LEFT JOIN courier_funcionarios_guia g 
				   ON f.id = g.id_funcionario 
				   WHERE f.id_entidad ='1' AND g.id_guia='$id_guia'";
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
    <td class="celda_tabla_principal"><div class="letreros_tabla">Funcionarios Taescol que Revisaron</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<?php 
			//identificando funcionario que intervienen con la guia
			$nombre_funcionarios="";
			$sql2="SELECT f.nombre, f.no_documento 
				   FROM courier_funcionario f 
				   LEFT JOIN courier_funcionarios_guia g 
				   ON f.id = g.id_funcionario 
				   WHERE f.id_entidad ='2' AND g.id_guia='$id_guia'";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 8: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			while($fila2=mysql_fetch_array($consulta2))
			{
			  $nombre_funcionarios .= "- " . $fila2['nombre'] . ", Documento No. " . $fila2['no_documento'] . "<br>";
			}
    		echo $nombre_funcionarios; 
    	?>    
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Funcionarios Polfa que Revisaron</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<?php 
			//identificando funcionario que intervienen con la guia
			$nombre_funcionarios="";
			$sql2="SELECT f.nombre, f.no_documento 
				   FROM courier_funcionario f 
				   LEFT JOIN courier_funcionarios_guia g 
				   ON f.id = g.id_funcionario 
				   WHERE f.id_entidad ='3' AND g.id_guia='$id_guia'";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 8: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			while($fila2=mysql_fetch_array($consulta2))
			{
			  $nombre_funcionarios .= "- " . $fila2['nombre'] . ", Documento No. " . $fila2['no_documento'] . "<br>";
			}
    		echo $nombre_funcionarios; 
    	?>    
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Funcionarios Invima que Revisaron</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<?php 
			//identificando funcionario que intervienen con la guia
			$nombre_funcionarios="";
			$sql2="SELECT f.nombre, f.no_documento 
				   FROM courier_funcionario f 
				   LEFT JOIN courier_funcionarios_guia g 
				   ON f.id = g.id_funcionario 
				   WHERE f.id_entidad ='4' AND g.id_guia='$id_guia'";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 8: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			while($fila2=mysql_fetch_array($consulta2))
			{
			  $nombre_funcionarios .= "- " . $fila2['nombre'] . ", Documento No. " . $fila2['no_documento'] . "<br>";
			}
    		echo $nombre_funcionarios; 
    	?>    
    </td>
  </tr>

  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Funcionarios ICA que Revisaron</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<?php 
			//identificando funcionario que intervienen con la guia
			$nombre_funcionarios="";
			$sql2="SELECT f.nombre, f.no_documento 
				   FROM courier_funcionario f 
				   LEFT JOIN courier_funcionarios_guia g 
				   ON f.id = g.id_funcionario 
				   WHERE f.id_entidad ='5' AND g.id_guia='$id_guia'";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 8: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			while($fila2=mysql_fetch_array($consulta2))
			{
			  $nombre_funcionarios .= "- " . $fila2['nombre'] . ", Documento No. " . $fila2['no_documento'] . "<br>";
			}
    		echo $nombre_funcionarios; 
    	?>    
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Otros Funcionarios que Revisaron</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<?php 
			//identificando funcionario que intervienen con la guia
			$nombre_funcionarios="";
			$sql2="SELECT f.nombre, f.no_documento, f.otros 
				   FROM courier_funcionario f 
				   LEFT JOIN courier_funcionarios_guia g 
				   ON f.id = g.id_funcionario 
				   WHERE f.id_entidad ='6' AND g.id_guia='$id_guia'";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 8: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			while($fila2=mysql_fetch_array($consulta2))
			{
			  $nombre_funcionarios .= "- " . $fila2['nombre'] . ", Documento No. " . $fila2['no_documento'] . "- Entidad:".$fila2['otros']." <br>";
			}
    		echo $nombre_funcionarios; 
    	?>    
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Funcionarios del Courier que Revisaron</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<?php 
			//identificando funcionario que intervienen con la guia
			$nombre_funcionarios="";
			$sql2="SELECT f.nombre, f.no_documento 
				   FROM courier_funcionario f 
				   LEFT JOIN courier_funcionarios_guia g 
				   ON f.id = g.id_funcionario 
				   WHERE f.id_entidad ='7' AND g.id_guia='$id_guia'";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 8: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			while($fila2=mysql_fetch_array($consulta2))
			{
			  $nombre_funcionarios .= "- " . $fila2['nombre'] . ", Documento No. " . $fila2['no_documento'] . "<br>";
			}
    		echo $nombre_funcionarios; 
    	?>    
    </td>
  </tr>


  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">L&iacute;nea Usada</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $linea ?></td>
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
    <td class="celda_tabla_principal"><div class="letreros_tabla">Gu&iacute;a con Actuaci&oacute;n Aduanera</div></td>
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
				$sql2="SELECT id,hija,piezas,peso 
					   FROM guia  
					   WHERE master ='$id_guia'
					   ORDER BY hija ASC
					   ";
				$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 9: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila2=mysql_fetch_array($consulta2))
				{
					$metadata .= '
					<tr>
						<td align="right">'.$fila2['hija'].'</td>
						<td align="right">'.number_format($fila2['piezas'],0,",",".").'</td>
						<td align="right">'.number_format($fila2['peso'],0,",",".").'</td>
						<td><button type="button" onclick="document.location=\'consulta_guia_courier_hija.php?id_guia='.$fila2['id'].'\'">
								<img src="imagenes/buscar-act.png">
							</button>
						</td>
					</tr>
					';
				}
	    		echo $metadata; 
	    	?>       
    	</table> 	    
    </td>
  </tr>  


  <tr>
    <td height="47" class="celda_tabla_principal"><div class="letreros_tabla">Observacion / Novedad</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $observaciones; ?></td>
  </tr>
  <tr>
    <td height="47" class="celda_tabla_principal"><div class="letreros_tabla">Facturacion</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $impresion_factura; ?></td>
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
