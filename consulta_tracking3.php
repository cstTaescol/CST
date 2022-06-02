<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
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
		$faltante_total=$fila["faltante_total"];
		$estado=$fila["id_tipo_bloqueo"];	
		
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
				
		if ($faltante_total == "S")
		{
			$estado="FALTANTE";
		}
		else
			{				
				// identificando columna de estado		
				$sql3="SELECT nombre,descripcion FROM tipo_bloqueo_guia WHERE id='$estado'";
				$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
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
</head>
<body>
<?php
require("menu.php");
//Privilegios Consultar Todo el Modulo
$id_objeto=64; 
include("config/provilegios_modulo.php");  
//---------------------------
?>
<p class="titulo_tab_principal">Tracking de la Guia</p>
<table align="center">
  <tr>
    <td align="center" class="celda_tabla_principal">
   	  <div class="letreros_tabla">Estado</div>
       <font class="asterisco" size="+3"><i><?php echo $estado;?></i></font>
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Info Completa</div>     
      <button onClick="document.location='consulta_guia.php?id_guia=<?php echo $id_guia?>'" <?php  $id_objeto=51; include("config/provilegios_objeto.php");  echo $activacion ?>>
          <img src="imagenes/nuevo_link-act.png"/>
    </button>					
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Historial</div>      
    <button onClick="document.location='consulta_historial.php?id_guia=<?php echo $id_guia?>'" <?php  $id_objeto=65; include("config/provilegios_objeto.php");  echo $activacion ?>>
          <img src="imagenes/buscar-act.png" />
    </button>					
    </td>
  </tr>
</table>
<table align="center">
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
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas Faltantes</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $piezas_faltantes; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Novedades</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $descripcion_bloqueo; ?></td>
  </tr>
</table>
</body>
</html>
