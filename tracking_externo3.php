<?php 
/*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
//require("config/control_tiempo.php");
if(isset($_REQUEST["id_guia"]))
	{
		$id_guia=$_REQUEST["id_guia"];
		$sql="SELECT * FROM guia WHERE id = '$id_guia'";
		$consulta=mysql_query($sql,$conexion) or die ("Error 1".mysql_error());
		$fila=mysql_fetch_array($consulta);
		$hija=$fila["hija"];
		$descripcion=$fila["descripcion"];
		$observaciones=$fila["observaciones"];			
		$flete=$fila["flete"];	
		$fecha_corte=$fila["fecha_corte"];
		$descripcion_bloqueo=$fila["descripcion_bloqueo"];
		$piezas_faltantes=$fila["piezas_faltantes"];
		
		if($fila["foto_ingreso1"]=="")
			$foto_ingreso1="sinfoto.jpg";
			else
				$foto_ingreso1=$fila["foto_ingreso1"];

		if($fila["foto_ingreso2"]=="")
			$foto_ingreso2="sinfoto.jpg";
			else
				$foto_ingreso2=$fila["foto_ingreso2"];

		if($fila["foto_ingreso3"]=="")
			$foto_ingreso3="sinfoto.jpg";
			else
				$foto_ingreso3=$fila["foto_ingreso3"];

		if($fila["foto_ingreso4"]=="")
			$foto_ingreso4="sinfoto.jpg";
			else
				$foto_ingreso4=$fila["foto_ingreso4"];

		if($fila["foto_despacho1"]=="")
			$foto_despacho1="sinfoto.jpg";
			else
				$foto_despacho1=$fila["foto_despacho1"];

		if($fila["foto_despacho2"]=="")
			$foto_despacho2="sinfoto.jpg";
			else
				$foto_despacho2=$fila["foto_despacho2"];

		if($fila["foto_despacho3"]=="")
			$foto_despacho3="sinfoto.jpg";
			else
				$foto_despacho3=$fila["foto_despacho3"];

		if($fila["foto_despacho4"]=="")
			$foto_despacho4="sinfoto.jpg";
			else
				$foto_despacho4=$fila["foto_despacho4"];
		// identificando columna de estado
		$estado=$fila["id_tipo_bloqueo"];	
		$sql3="SELECT nombre,descripcion FROM tipo_bloqueo_guia WHERE id='$estado'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$estado=$fila3["nombre"];
		$descripcion_estado=$fila3["descripcion"];

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
			$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
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
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$aerolinea=$fila3["nombre"];

		// identificando admon aduana
		$aduana=$fila["id_administracion_aduana"];
		$sql3="SELECT nombre FROM admon_aduana WHERE id='$aduana'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$aduana=$fila3["nombre"];

		//identificando embarcador
		$embarcador=$fila["id_embarcador"];
		$sql3="SELECT nombre FROM embarcador WHERE id='$embarcador'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$embarcador=$fila3["nombre"];

		//identificando consignatario
		$consignatario=$fila["id_consignatario"];
		$sql3="SELECT nombre FROM consignatario WHERE id='$consignatario'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$consignatario=$fila3["nombre"];
		
		// identificando disposicion
		$id_disposicion=$fila["id_disposicion"];
		$sql3="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$disposicion=$fila3["nombre"];
		// Destino
		$id_disposicion=$fila["id_disposicion"];
		//Evaluar si la disposicion no exige ningun tipo de deposito
		if ($id_disposicion ==28 || $id_disposicion ==21 || $id_disposicion ==20 || $id_disposicion ==19 || $id_disposicion ==25 || $id_disposicion ==29 || $id_disposicion ==23 || $id_disposicion ==13 || $id_disposicion ==15)
			{
				$sql3="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
				$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$fila3=mysql_fetch_array($consulta3);
				$destino=$fila3["nombre"];
			}
		else
		{
			$id_deposito=$fila["id_deposito"];
			$sql3="SELECT nombre FROM deposito WHERE id='$id_deposito'";
			$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila3=mysql_fetch_array($consulta3);
			$destino=$fila3["nombre"];
		}
		if ($id_disposicion == 26) 
		{
			$destino="BODEGA DIAN";
		}						
		include("config/evaluador_inconsistencias.php");
	}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />
	<title>TRACKING SIC-CST</title>
</head>
<body>
<table align="center">
    <tr>
        <td>
            <a href="tracking_externo.php"><img src="imagenes/traking.jpg" border="0"></a>
        </td>
    </tr>
 </table>
<p class="titulo_tab_principal">Consulta de Guia</p> 
<table width="800" align="center">
	<tr>
    	<td colspan="2" align="center" class="celda_tabla_principal"><font class="asterisco">Estado:</font><div class="letreros_tabla"><?php echo $estado;?></div></td>
    </tr>
  <tr>
    <td width="180" align="left" class="celda_tabla_principal"><div class="letreros_tabla">Master</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $master;?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Guia</div></td>
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
    <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha de Llegada</div></td>
    <td class="celda_tabla_principal celda_boton"><font color="green"><?php echo "($fecha_manifiesto)" ?></font></td>
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
    <td class="celda_tabla_principal celda_boton"><?php echo $peso; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $volumen; ?></td>
  </tr>
</table>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" name="cancelar" id="cancelar" onClick="document.location='<?php echo URLCLIENTE ?>'">
                <img src="imagenes/al_principio-act.png" title="<?php echo CLIENTE ?>" /><br>
                Volver a <?php echo CLIENTE ?>
            </button>
            <button type="button" name="cancelar" id="cancelar" onClick="document.location='tracking_externo.php'">
                <img src="imagenes/buscar-act.png" title="Buscar Otra" /><br>
                Nueva busqueda
            </button>
          </td>
        </tr>
     </table>    

</html>
