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
		$consulta=mysql_query($sql,$conexion) or die (exit('Error 1 '.mysql_error()));
		$fila=mysql_fetch_array($consulta);
		$hija=$fila["hija"];
		$descripcion=$fila["descripcion"];
		$observaciones=$fila["observaciones"];
		$flete=$fila["flete"];
		$fecha_corte=$fila["fecha_corte"];		
		$descripcion_bloqueo=$fila["descripcion_bloqueo"];
		$piezas=$fila["piezas"];
		$peso=$fila["peso"];
		$volumen=$fila["volumen_inconsistencia"];
		$piezas_inconsistencia=$fila["piezas_inconsistencia"];
		$peso_inconsistencia=$fila["peso_inconsistencia"];
		$observaciones_cumplido=$fila["observaciones_cumplido"];
		$observaciones_despacho=$fila["observaciones_despacho"];
		$observaciones_bodega=$fila["observaciones_bodega"];
		$n_acta_inmoforzosa=$fila["n_acta_inmoforzosa"];
		$n_acta_bloqmanual=$fila["n_acta_bloqmanual"];
		$n_acta_desbloqmanual=$fila["n_acta_desbloqmanual"];
		$n_acta_movilizacion=$fila["n_acta_movilizacion"];
		$id_tipo_bloqueo=null;
		$faltante_total=$fila["faltante_total"];
		if ($faltante_total == "S")
		{
			$estado="FALTANTE";
		}
		else
			{				
				// identificando columna de estado
				$id_tipo_bloqueo=$fila["id_tipo_bloqueo"];
				$sql3="SELECT nombre,descripcion FROM tipo_bloqueo_guia WHERE id='$id_tipo_bloqueo'";
				$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$fila3=mysql_fetch_array($consulta3);
				$estado=$fila3["nombre"];
				$descripcion_estado=$fila3["descripcion"];				
			}
		
		// identificando columna de consoliado
		$id_tipo_guia=$fila["id_tipo_guia"];
		if ($id_tipo_guia==3)
		{
			$master=$fila["master"];
			require("config/master.php");
		}
		else
		{
			$sql3="SELECT nombre FROM tipo_guia WHERE id='$id_tipo_guia'";
			$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila3=mysql_fetch_array($consulta3);
			$master=$fila3["nombre"];
		}

		//identificando vuelo
		$id_vuelo=$fila["id_vuelo"];
		$sql3="SELECT nmanifiesto,hora_manifiesto,fecha_manifiesto,nvuelo FROM vuelo WHERE id='$id_vuelo'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$nmanifiesto=$fila3["nmanifiesto"];
		$hora_manifiesto=$fila3["hora_manifiesto"];
		$fecha_manifiesto=$fila3["fecha_manifiesto"];
		$vuelo=$fila3["nvuelo"];
	
		// identificando aerolinea
		$aerolinea=$fila["id_aerolinea"];
		$sql3="SELECT nombre FROM aerolinea WHERE id='$aerolinea'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$aerolinea=$fila3["nombre"];

		// identificando admon aduana
		$aduana=$fila["id_administracion_aduana"];
		$sql3="SELECT nombre FROM admon_aduana WHERE id='$aduana'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 6: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$aduana=$fila3["nombre"];

		//identificando embarcador
		$embarcador=$fila["id_embarcador"];
		$sql3="SELECT nombre FROM embarcador WHERE id='$embarcador'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 7: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$embarcador=$fila3["nombre"];

		//identificando consignatario
		$consignatario=$fila["id_consignatario"];
		$sql3="SELECT nombre FROM consignatario WHERE id='$consignatario'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 8: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$consignatario=$fila3["nombre"];
		
		// identificando disposicion
		$id_disposicion=$fila["id_disposicion"];
		$sql3="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 9: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$disposicion=$fila3["nombre"];
		
		// identificando agente de carga
		$id_agentedecarga=$fila["id_agentedecarga"];
		$sql3="SELECT razon_social FROM agente_carga WHERE id='$id_agentedecarga'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 10: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$id_agentedecarga=$fila3["razon_social"];
		
		// identificando tipo de carga
		$tipo_carga=$fila["id_tipo_carga"];
		$sql3="SELECT nombre FROM tipo_carga WHERE id='$tipo_carga'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 11: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$tipo_carga=$fila3["nombre"];

		// identificando departamento destino
		$cod_departamento_destino=$fila["cod_departamento_destino"];
		$sql3="SELECT nombre FROM departamento WHERE codigo='$cod_departamento_destino'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 12: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$cod_departamento_destino=$fila3["nombre"];

		// identificando ciudad destino
		$cod_ciudad_destino=$fila["cod_ciudad_destino"];
		$sql3="SELECT nombre FROM ciudad_destino WHERE codigo='$cod_ciudad_destino'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 13: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$cod_ciudad_destino=$fila3["nombre"];

		// identificando pais origen
		$cod_pais_embarque=$fila["cod_pais_embarque"];
		$sql3="SELECT nombre FROM pais WHERE codigo='$cod_pais_embarque'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 14: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$cod_pais_embarque=$fila3["nombre"];

		// identificando ciudad origen
		$cod_ciudad_embarque=$fila["cod_ciudad_embarque"];
		$sql3="SELECT nombre FROM ciudad_embarque WHERE id='$cod_ciudad_embarque'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 15: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$cod_ciudad_embarque=$fila3["nombre"];

		
		// identificando peso verificado
		$sql3="SELECT piezas,peso FROM peso_verificado WHERE id_guia=$id_guia";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 16: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		while ($fila3=mysql_fetch_array($consulta3))
		{
			$totalpiezas += $fila3["piezas"];
			$totalpeso += $fila3["peso"];	
		}

		// Identificando Facturas de la Guia
		$id_objeto=117; 
		include("config/provilegios_objeto.php");  
		if ($activacion =="")
		{
			$sql3="SELECT * FROM guia_factura WHERE id_guia=$id_guia";
			$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 17: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
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
		// ****************************************************


		
		
		//Cuearto Frio Si o No
		$cuarto_frio=$fila["cuarto_frio"];
		if ($cuarto_frio=="N")
			$cuarto_frio="No";
		else
			$cuarto_frio="Si";

	
		//Precursores Si o No
		$precursores=$fila["precursores"];
		if ($precursores=="N")
			$precursores="No";
		else
			$precursores="Si";

		//Asignacion Directa Si o No
		$asignacion_directa=$fila["asignacion_directa"];
		if ($asignacion_directa=="N")
			$asignacion_directa="No";
		else
			$asignacion_directa="Si";


		//Reasignada Si o No
		$reasignacion=$fila["reasignacion"];
		if ($reasignacion=="N")
			$reasignacion="No";
		else
			$reasignacion="Si";

		// DESTINO*****************************************************************************************
		if ($id_disposicion == 0 or $id_disposicion == "") //Ajuste automatico temporal para correccion de desvinculación de guias de descargue directo
			{
				mysql_query ("UPDATE guia SET id_disposicion='21' WHERE id='$id_guia'",$conexion) or die ("ERROR 17: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$destino="ENTREGA EN LUGAR DE ARRIBO";				
				$id_disposicion=21;
				$disposicion="ENTREGA EN LUGAR DE ARRIBO";
				//2. almacenamiento del traking
				$fecha_historial=date("Y-m-d");
				$hora_historial=date("H:i:s");
				$sql_trak="INSERT INTO tracking (id_guia,
												 fecha_creacion,
												 hora,
												 evento,
												 tipo_tracking,
												 id_usuario) 
													VALUE ('$id_guia',
														   '$fecha_historial',
														   '$hora_historial',
														   'AJUSTE AUTOMATICO DEL SISTEMA POR DISPOSICION DE CARGUE',
														   '1',
														   '1')";
				mysql_query($sql_trak,$conexion) or die ("ERROR 17-A ".mysql_error());
			}
		
		//Evaluar si la disposicion incorpora un deposito como destino
		if ($id_disposicion ==10 || $id_disposicion ==11 || $id_disposicion ==12 || $id_disposicion ==14 || $id_disposicion ==16 || $id_disposicion ==17 || $id_disposicion ==18 || $id_disposicion ==22 || $id_disposicion ==24)
			{
				$id_deposito=$fila["id_deposito"];
				$sql3="SELECT nombre FROM deposito WHERE id='$id_deposito'";
				$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 17-B: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$fila3=mysql_fetch_array($consulta3);
				$destino=$fila3["nombre"];				
			}
		//Evaluar si la disposicion NO incorpora un deposito como destino e imprime el nombre de la disposicion			
		if ($id_disposicion ==13 || $id_disposicion ==15 || $id_disposicion ==19 || $id_disposicion ==20 || $id_disposicion ==21 || $id_disposicion ==23 || $id_disposicion ==26 || $id_disposicion ==28 || $id_disposicion ==29)
			{
				$sql3="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
				$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 18: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$fila3=mysql_fetch_array($consulta3);
				$destino=$fila3["nombre"];	
			}
		//Evaluar si la disposicion INDICA una guia de correo que tiene como destino la Bodega de la Dian.		
		if ($id_disposicion == 26 || $id_disposicion == 27)
			{
				$destino="BODEGA DIAN";
			}						
		// *****************************************************************************************	
		
		//DESPACHOS*********************************************************************************
		//cuando ya despacharon la mercancia crea un vinculo al despacho segun el tipo de disposicion
		if ($id_tipo_bloqueo==4 or $id_tipo_bloqueo==3)
		{
			//remesas - depositos
			if ($id_disposicion==10 || $id_disposicion==11 || $id_disposicion==18 || $id_disposicion==22)
			{
				$sql_despacho="SELECT id_remesa FROM carga_remesa WHERE id_guia='$id_guia'";
				$tipo=1; //Tipo de botón   
			}
			//cabotajes
			if ($id_disposicion==12 || $id_disposicion==16 || $id_disposicion==17 || $id_disposicion==24)
			{
				$sql_despacho="SELECT id FROM cabotaje WHERE id_guia='$id_guia'";	
				$tipo=2; //Tipo de botón
			}
			//descargues directos
			if ($id_disposicion==19 || $id_disposicion==20 || $id_disposicion==21)
			{
				$sql_despacho="SELECT id FROM descargue_directo WHERE id_guia='$id_guia'";
				$tipo=3; //Tipo de botón
			}
			//Guias de correo
			if ($id_disposicion == 26 || $id_disposicion == 27)
			{
				$sql_despacho="SELECT id_correo FROM carga_correo WHERE id_guia='$id_guia'";	
				$tipo=4; //Tipo de botón
			}
			//Otros Despachos
			if ($id_disposicion==15 || $id_disposicion==25 || $id_disposicion==28 || $id_disposicion==29)
			{
				$sql_despacho="SELECT id_otros FROM carga_otros WHERE id_guia='$id_guia'";	
				$tipo=5; //Tipo de botón
			}
			//trasbordos
			if ($id_disposicion==13 || $id_disposicion==14 || $id_disposicion==23)
			{
				$sql_despacho="SELECT id FROM trasbordo WHERE id_guia='$id_guia'";	
				$tipo=6; //Tipo de botón
			}



			$consulta2=mysql_query ($sql_despacho,$conexion) or die ("ERROR 19: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			while($fila2=mysql_fetch_array($consulta2))
			{
				$alto=480;
				$ancho=680;	
				switch ($tipo)
				{
					case 1:
						$id=$fila2['id_remesa'];
						$botones=$botones."- <input type=\"button\" value=\"$id\" onClick=\"abrir('pdf_remesa.php?id_remesa=$id',$alto,$ancho)\">";
						//carga los datos del cumplido de la remesa
						$sql_remesa="SELECT foto_cumplido, planilla_envio, planilla_recepcion  FROM remesa WHERE id = '$id'";
						$consulta_remesa=mysql_query ($sql_remesa,$conexion) or die ("ERROR 20: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila_remesa=mysql_fetch_array($consulta_remesa);
						$foto_cumplido=$fila_remesa['foto_cumplido'];
						if ($foto_cumplido == "") $foto_cumplido="imagen_no_disponible.jpg";						
						$cumplidos .= "- <input type=\"button\" style=\"background:#CC0\" value=\"$id\" onClick=\"abrir('fotos/cumplidos/$foto_cumplido',$alto,$ancho)\">";						
						if ($fila_remesa['planilla_envio'] != "") $planilla_envio .=$fila_remesa['planilla_envio']."<br>"; 
						if ($fila_remesa['planilla_recepcion'] != "") $planilla_recepcion .=$fila_remesa['planilla_recepcion']."<br>";						
					break;
					case 2:
						$id=$fila2['id'];
						$botones=$botones."- <input type=\"button\" value=\"$id\" onClick=\"abrir('pdf_cabotaje.php?id_cabotaje=$id',$alto,$ancho)\">";
					break;
					case 3:
						$id=$fila2['id'];
						$botones=$botones."- <input type=\"button\" value=\"$id\" onClick=\"abrir('pdf_descargue_directo.php?id_ddirecto=$id',$alto,$ancho)\">";
					break;
					case 4:
						$id=$fila2['id_correo'];
						$botones=$botones."- <input type=\"button\" value=\"$id\" onClick=\"abrir('pdf_correo.php?id=$id',$alto,$ancho)\">";
					break;
					case 5:
						$id=$fila2['id_otros'];
						$botones=$botones."- <input type=\"button\" value=\"$id\" onClick=\"document.location='despacho_otros3.php?id=$id',$alto,$ancho\">";
					break;
					case 6:
						$id=$fila2['id'];
						$botones=$botones."- <input type=\"button\" value=\"$id\" onClick=\"abrir('pdf_trasbordo.php?id_trasbordo=$id',$alto,$ancho)\">";		
					break;

				}
			}
		}
		// *****************************************************************************************
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
	function conf_eliminar(url)
	{
	var respuesta=confirm('ATENCION: Confirma que, Desea Eliminar la Guia?');
	if (respuesta)
		{
			window.location="eliminar_guia1.php?id_guia="+url;
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
<p class="titulo_tab_principal">Descripcion de la Guia</p>
<table align="center">
  <tr>
    <td align="center" class="celda_tabla_principal">
    	<div class="letreros_tabla">Estado</div>
    	<font class="asterisco" size="+3"><i><?php echo $estado;?></i></font>
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Eliminar</div>
      <button <?php  $id_objeto=71; include("config/provilegios_objeto.php");  echo $activacion ?> onClick="conf_eliminar(<?php echo $id_guia?>);">
          <img src="imagenes/eliminar-act.png" title="Eliminar esta Gu&iacute;a" />
    </button>	    
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Modificar</div>
      <button <?php  $id_objeto=72; include("config/provilegios_objeto.php");  echo $activacion ?> onClick="document.location='modificar_guia1.php?id_guia=<?php echo $id_guia ?>'">
          <img src="imagenes/settings-act.png" title="Modificar esta Gu&iacute;a"/>
    </button>	    
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Tracking</div>      
      <button <?php  $id_objeto=64; include("config/provilegios_objeto.php");  echo $activacion ?> onClick="document.location='consulta_tracking3.php?id_guia=<?php echo $id_guia?>'">
          <img src="imagenes/quitar_link-act.png" title="Consulta en modo Tracking"/>
    </button>	
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Historial</div>      
    <button <?php  $id_objeto=65; include("config/provilegios_objeto.php");  echo $activacion ?> onClick="document.location='consulta_historial.php?id_guia=<?php echo $id_guia?>'">
          <img src="imagenes/buscar-act.png" title="Muestra el Historial de movimiento de la Gu&iacute;a"/>
    </button>					
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Pausar</div>      
    <button <?php  $id_objeto=104; include("config/provilegios_objeto.php");  echo $activacion ?> onClick="document.location='guia_pausar.php?id_guia=<?php echo $id_guia?>'">
          <img src="imagenes/quitar_link-act.png" title="Pausar la guia para revision detallada en INVENTARIO DE PAUSAS">
    </button>					
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Novedad</div>      
        <button type="button" title="Agregar una NOVEDAD a la Gu&iacute;a" onClick="openPopup('guia_novedad.php?id_guia=<?php echo $id_guia ?>','new','700','450','scrollbars=1',true);" <?php  $id_objeto=94; include("config/provilegios_objeto.php");  echo $activacion ?>>
          <img src="imagenes/alerta-act.png" title="Agregar una Novedad a la Gu&iacute;a">
        </button>
    </td>
  </tr>
</table>

<table align="center" width="80%">
  <tr >
    <td width="180" class="celda_tabla_principal"><div class="letreros_tabla">Master</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $master;?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Hija</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $hija;?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $aerolinea;?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Vuelo</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $vuelo;?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Manifiesto</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo "$nmanifiesto" ?> - <font color="green"><?php echo "($fecha_manifiesto)" ?></font> / <font color="blue"><?php echo "($hora_manifiesto)" ?></font></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Aduana</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $aduana; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Disposicion</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $disposicion; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Asignacion de Origen</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $asignacion_directa; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Reasignada</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $reasignacion; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Agente de Cargue</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $id_agentedecarga; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Embarcador</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $embarcador; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Consignatario</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $consignatario; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Deposito</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $destino; ?></td>
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
    <td class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $volumen; ?> Kg.</td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas Despaletizadas</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $piezas_inconsistencia; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso Despaletizado</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $peso_inconsistencia; ?> Kg.</td>
  </tr>   
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Requiere Cuarto Fr&iacute;o</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $cuarto_frio; ?></td>
  </tr>  
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Precursores</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $precursores; ?></td>
  </tr>
  <tr>
    <td height="47" class="celda_tabla_principal"><div class="letreros_tabla">Descripcion</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $descripcion; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas Verificadas</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $totalpiezas ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso Verificado</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $totalpeso ?> Kg.</td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Flete</div></td>
    <td class="celda_tabla_principal celda_boton">$<?php echo number_format($flete,0,".","."); ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Tipo de Carga</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $tipo_carga ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Pais de Origen</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $cod_pais_embarque; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Ciudad de Origen</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $cod_ciudad_embarque; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Departamento Destino</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $cod_departamento_destino; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Ciudad Destino</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $cod_ciudad_destino; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha Corte</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $fecha_corte; ?> 
  </tr>
  <tr>
    <td height="47" class="celda_tabla_principal"><div class="letreros_tabla">Observacion / Novedad</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $observaciones; ?></td>
  </tr>
  <tr>
    <td height="47" class="celda_tabla_principal"><div class="letreros_tabla">Observacion Bodega</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $observaciones_bodega; ?></td>
  </tr>
  <tr>
    <td height="47" class="celda_tabla_principal"><div class="letreros_tabla">Observacion Despacho</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $observaciones_despacho; ?></td>
  </tr>
  <tr>
    <td height="47" class="celda_tabla_principal"><div class="letreros_tabla">Observacion de Cumplido</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $observaciones_cumplido; ?></td>
  </tr>
  <tr>
    <td height="47" class="celda_tabla_principal"><div class="letreros_tabla">Facturacion</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $impresion_factura; ?></td>
  </tr>  
  <tr>
    <td height="47" class="celda_tabla_principal"><div class="letreros_tabla">Descrip. Bloqueo</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $descripcion_bloqueo; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Despacho No.</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $botones; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Cumplidos</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $cumplidos; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Planilla de Envio</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $planilla_envio; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Planilla de Recepcion</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $planilla_recepcion; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Ubicacion</div></td>
    <td class="celda_tabla_principal celda_boton">
	<?php
		$posicion="";
		//Ubica la Posicion en Bodega
		$sql_posiscion="SELECT p.*,pc.* FROM posicion_carga pc LEFT JOIN posicion p ON pc.id_posicion=p.id WHERE pc.id_guia='$id_guia'";
		$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die (exit('Error 21:'.mysql_error()));
		while($fila_posicion=mysql_fetch_array($consulta_posicion))
		{
			$plaqueta=$fila_posicion['rack']."-".$fila_posicion['seccion']."-".$fila_posicion['nivel']."-".$fila_posicion['lado']." ".$fila_posicion['fondo'];
			if ($fila_posicion['rack'] < "J")
				$mapa_destino="ubicacion_mapa.php";
			else
				$mapa_destino="ubicacion_mapa2.php";
			
			$posicion=$posicion." - <a href=\"$mapa_destino?id_guia=$id_guia\"><font color=\"blue\">$plaqueta</font></a>";
		}
		echo $posicion;
	?>
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Inclusion Forzosa</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $n_acta_inmoforzosa; ?>
</td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Bloqueo Manual</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $n_acta_bloqmanual; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Movilizacion 1154</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $n_acta_movilizacion; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Movilizacion Manual</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $n_acta_desbloqmanual; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">Registro Fotografico</div></td>
  </tr>
  <tr>
  	<td colspan="2" align="center" class="celda_tabla_principal celda_boton">
  		<button type="button" onClick="abrir('galeria/galeria.php?id_guia=<?php echo $id_guia ?>&tipo=foto_bodega')">
  			<img src="imagenes/caja2.png" width="100" height="100" title="Registro Fotografico" /><br>
  			<strong>Bodega</strong>
  		</button>
  		<button type="button" onClick="abrir('galeria/galeria.php?id_guia=<?php echo $id_guia ?>&tipo=foto_seguridad')">
  			<img src="imagenes/poli1.png" width="100" height="100" title="Registro Fotografico" /><br>
  			<strong>Seguridad</strong>
  		</button>  		
  		<button type="button" onClick="abrir('galeria/galeria.php?id_guia=<?php echo $id_guia ?>&tipo=foto_despacho')">
  			<img src="imagenes/camion.png" width="100" height="100" title="Registro Fotografico" /><br>
  			<strong>Despacho</strong>
  		</button>
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
<!-- función que permite abrir ventanas emergentes con las propiedades deseadas -->
function openPopup(url,name,w,h,props,center){
	l=18;t=18
	if(center){l=(screen.availWidth-w)/2;t=(screen.availHeight-h)/2}
	url=url.replace(/[ ]/g,'%20')
	popup=window.open(url,name,'left='+l+',top='+t+',width='+w+',height='+h+',scrollbars=1'+((props)?','+props:''))
	props=props||''
	if(props.indexOf('fullscreen')!=-1){popup.moveTo(0,0);popup.resizeTo(screen.width,screen.height)}
	popup.focus()
}
<!-- -------------------------------------------------------------------------- -->
</script>
