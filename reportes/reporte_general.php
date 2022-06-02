<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("../config/configuracion.php");
set_time_limit(0); // Quita el limitante de tiempo para realizar una consulta grande
$id_usuario=$_SESSION['id_usuario'];
//Usuario
$sql3="SELECT nombre FROM usuario WHERE id='$id_usuario'";
$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila3=mysql_fetch_array($consulta3);
$usuario=$fila3["nombre"];
//****************************************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../tema/estilo.css" rel="stylesheet" type="text/css" />
    <title>Reporte</title>
</head>
<body>
<div id="cargando">
	<p align="center">Espere mientras es generado su reporte...<img src="../imagenes/cargando.gif" width="20" height="21" align="absmiddle" /></p>
</div>
<?php

$ano=date("Y");
$mes=date("m");
$dia=date("d");
$hora_registro=date("H:i:s");
$titulos="No.;FECHA;AEROLINEA";
$rangoini=$_POST['rangoini'];
$rangofin=$_POST['rangofin'];
$id_aerolinea=$_POST['id_aerolinea'];
if ($id_aerolinea=="*")
{
	$sql_aerolinea="";
}
else
	{
		$sql_aerolinea="AND g.id_aerolinea ='$id_aerolinea'";
	}
//Cheks Seleccionados
if (isset($_POST['vuelo']))
{
	$vuelo=$_POST['vuelo'];
	$titulos=$titulos.";VUELO";
}
else
	{
		$vuelo="";
	}
	
if (isset($_POST['manifiesto']))
{
	$manifiesto=$_POST['manifiesto'];
	$titulos=$titulos.";MANIFIESTO";
}
else
	{
		$manifiesto="";
	}	

//Resepcion de datos
$tipoguia=$_POST['tipoguia'];
if ($tipoguia=="*")
{
	$sql_tipoguia="";
}
else
	{
		if ($tipoguia == 2) // Guais master
		{
			echo '<script>
					document.location="reporte_guias_master.php?id_aerolinea='.$id_aerolinea.'&rangoini='.$rangoini.'&rangofin='.$rangofin.'";
				   </script>';
			exit();				 
		}
		else
		{
			$sql_tipoguia="AND g.id_tipo_guia ='$tipoguia'";	
		}
		
	}
$titulos=$titulos.";TIPO DE GUIA";

if (isset($_POST['master']))
{
	$ckmaster=$_POST['master'];
	$titulos=$titulos.";MASTER";
}
else
	{
		$ckmaster="";
	}
if (isset($_POST['hija']))
{
	$hija=$_POST['hija'];
	$titulos=$titulos.";GUIA";
}
else
	{
		$hija="";
	}
if (isset($_POST['piezas']))
{
	$ckpiezas=$_POST['piezas'];
	$titulos=$titulos.";PIEZAS";
}
else
	{
		$ckpiezas="";
	}
if (isset($_POST['peso']))
{
	$ckpeso=$_POST['peso'];
	$titulos=$titulos.";PESO";
}
else
	{
		$ckpeso="";
	}
if (isset($_POST['volumen']))
{
	$ckvolumen=$_POST['volumen'];
	$titulos=$titulos.";VOLUMEN";
}
else
	{
		$ckvolumen="";
	}
if (isset($_POST['piezas_despaletizado']))
{
	$ckpiezasdespaletizado=$_POST['piezas_despaletizado'];
	$titulos=$titulos.";PIEZAS DESPALET";
}
else
	{
		$ckpiezasdespaletizado="";
	}
if (isset($_POST['peso_despaletizado']))
{
	$ckpesodespaletizado=$_POST['peso_despaletizado'];
	$titulos=$titulos.";PESO DESPALET";
}
else
	{
		$ckpesodespaletizado="";
	}

if (isset($_POST['descripcion']))
{
	$descripcion=$_POST['descripcion'];
	$titulos=$titulos.";DESCRIPCION";
}
else
	{
		$descripcion="";
	}
if (isset($_POST['verificado_piezas']))
{
	$verificado_piezas=$_POST['verificado_piezas'];
	$titulos=$titulos.";PIEZAS VERIFICADAS";
}
else
	{
		$verificado_piezas="";
	}
	
if (isset($_POST['verificado_peso']))
{
	$verificado_peso=$_POST['verificado_peso'];
	$titulos=$titulos.";PESO VERIFICADO";
}
else
	{
		$verificado_peso="";
	}		
if (isset($_POST['destino']))
{
	$destino=$_POST['destino'];
	$titulos=$titulos.";DESTINO";
}
else
	{
		$destino="";
	}
if (isset($_POST['despacho']))
{
	$despacho=$_POST['despacho'];
	$titulos=$titulos.";DESPACHO";
}
else
	{
		$despacho="";
	}
if (isset($_POST['embarcador']))
{
	$embarcador=$_POST['embarcador'];
	$titulos=$titulos.";EMBARCADOR";
}
else
	{
		$embarcador="";
	}
if (isset($_POST['consignatario']))
{
	$consignatario=$_POST['consignatario'];
	$titulos=$titulos.";CONSIGNATARIO";
}
else
	{
		$consignatario="";
	}
if (isset($_POST['agente_carga']))
{
	$agente_carga=$_POST['agente_carga'];
	$titulos=$titulos.";AGENTE DE CARGA";
}
else
	{
		$agente_carga="";
	}
if (isset($_POST['fecha_corte']))
{
	$fecha_corte=$_POST['fecha_corte'];
	$titulos=$titulos.";FECHA DE CORTE";
}
else
	{
		$fecha_corte="";
	}
if (isset($_POST['asignacion_origen']))
{
	$asignacion_origen=$_POST['asignacion_origen'];
	$titulos=$titulos.";ASIGNACION DE ORIGEN";
}
else
	{
		$asignacion_origen="";
	}
if (isset($_POST['reasignacion']))
{
	$reasignacion=$_POST['reasignacion'];
	$titulos=$titulos.";RE-ASIGNADA";
}
else
	{
		$reasignacion="";
	}
if (isset($_POST['precursor']))
{
	$precursor=$_POST['precursor'];
	$titulos=$titulos.";PRECURSOR";
}
else
	{
		$precursor="";
	}
if (isset($_POST['tipo_carga']))
{
	$tipo_carga=$_POST['tipo_carga'];
	$titulos=$titulos.";TIPO DE CARGA";
}
else
	{
		$tipo_carga="";
	}
if (isset($_POST['disposicion']))
{
	$disposicion=$_POST['disposicion'];
	$titulos=$titulos.";DISPOSICION DE CARGA";
}
else
	{
		$disposicion="";
	}
if (isset($_POST['factura']))
{
	$factura=$_POST['factura'];
	$titulos=$titulos.";FACTURA";
}
else
	{
		$factura="";
	}

if (isset($_POST['valor_factura']))
{
	$valor_factura=$_POST['valor_factura'];
	$titulos=$titulos.";VALOR FACTURA";
}
else
	{
		$valor_factura="";
	}

if (isset($_POST['iva']))
{
	$iva=$_POST['iva'];
	$titulos=$titulos.";IVA";
}
else
	{
		$iva="";
	}


if (isset($_POST['fecha_factura']))
{
	$fecha_factura=$_POST['fecha_factura'];
	$titulos=$titulos.";FECHA FACTURA";
}
else
	{
		$fecha_factura="";
	}

if (isset($_POST['facturadoa']))
{
	$facturadoa=$_POST['facturadoa'];
	$titulos=$titulos.";FACTURADO A";
}
else
	{
		$facturadoa="";
	}

if (isset($_POST['cumplido_remesa']))
{
	$cumplido_remesa=$_POST['cumplido_remesa'];
	$titulos=$titulos.";CUMPLIDO DE REMESA";
}
else
	{
		$cumplido_remesa="";
	}
	
if (isset($_POST['reg_foto_bodega']))
{
	$reg_foto_bodega=$_POST['reg_foto_bodega'];
	$titulos=$titulos.";REG FOTO. BODEGA";
}
else
	{
		$reg_foto_bodega="";
	}

if (isset($_POST['reg_foto_seguridad']))
{
	$reg_foto_seguridad=$_POST['reg_foto_seguridad'];
	$titulos=$titulos.";REG FOTO. SEGURIDAD";
}
else
	{
		$reg_foto_seguridad="";
	}

if (isset($_POST['reg_foto_despacho']))
{
	$reg_foto_despacho=$_POST['reg_foto_despacho'];
	$titulos=$titulos.";REG FOTO. DESPACHO";
}
else
	{
		$reg_foto_despacho="";
	}		

if (isset($_POST['planilla_recepcion']))
{
	$planilla_recepcion=$_POST['planilla_recepcion'];
	$titulos=$titulos.";PLANILLA DE RECEPCION";
}
else
	{
		$planilla_recepcion="";
	}		

$sql="SELECT v.*,g.* FROM vuelo v LEFT JOIN guia g ON v.id = g.id_vuelo WHERE (g.fecha_inconsistencia != '' OR g.fecha_inconsistencia IS NOT NULL) AND g.fecha_inconsistencia BETWEEN '$rangoini' AND '$rangofin' $sql_tipoguia $sql_aerolinea ORDER BY g.fecha_inconsistencia ASC";

$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);
if ($nfilas > 0)
{
	//Creacion de Archivo
	$nombre_archivo="csv/".time()."-".$ano."-".$mes."-".$dia."_reporte_general.csv";
	$CL=fopen("$nombre_archivo","a") or die("Problemas en la creacion del archivo de Plano, consulete con el soporte tecnico" . exit());
	fputs($CL,"$titulos;\n");
	for ($i=1; $i<=$nfilas; $i++)
	{
		$fila=mysql_fetch_array($consulta);
		$id_guia=$fila["id"];
		$fecha_inconsistencia=$fila["fecha_inconsistencia"];
		$tipo_guia=$fila["id_tipo_guia"];
		//carga dato adicionales
		$sql_add="SELECT nombre FROM tipo_guia WHERE id='$tipo_guia'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$tipo_guia=$fila_add["nombre"];
		//************************
		
		$aerolinea=$fila["id_aerolinea"];
		//carga dato adicionales
		$sql_add="SELECT nombre FROM aerolinea WHERE id='$aerolinea'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$aerolinea=$fila_add["nombre"];
		//************************

		//Revision de los Cheks habilitados para crear la consulta.
		if ($hija==1)
		{	
			$hija_impresion=";".$fila["hija"];
		}
		else
			{
				$hija_impresion="";
			}

		
		if ($ckmaster==1)
		{	
			$master=$fila["master"];
			include("../config/master.php");
			$master=";".$master;
		}
		else
			{
				$master="";
			}

		if ($ckpiezas==1)
		{	
			$piezas_impresion=";".$fila["piezas"];;
		}
		else
			{
				$piezas_impresion="";
			}

		if ($ckpeso==1)
		{	
			$peso_impresion=";".number_format($fila["peso"],2,",","");
		}
		else
			{
				$peso_impresion="";
			}

		if ($ckvolumen==1)
		{	
			$volumen_impresion=";".number_format($fila["volumen"],2,",","");
		}
		else
			{
				$volumen_impresion="";
			}
		if ($ckpiezasdespaletizado==1)
		{				
			$piezadespaletizado_impresion=";".$fila["piezas_inconsistencia"];
		}
		else
			{
				$piezadespaletizado_impresion="";
			}
		if ($ckpesodespaletizado==1)
		{				
			$pesodespaletizado_impresion=";".number_format($fila["peso_inconsistencia"],2,",","");
		}
		else
			{
				$pesodespaletizado_impresion="";
			}
		if ($descripcion==1)
		{	
			$descripcion_impresion=";".$fila["descripcion"];
		}
		else
			{
				$descripcion_impresion="";
			}
		if ($verificado_piezas==1)
		{
			// identificando peso verificado
			$total_veri=0;
			$sql_verificacion="SELECT piezas FROM peso_verificado WHERE id_guia=$id_guia";
			$consulta_veri=mysql_query ($sql_verificacion,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			while ($fila_veri=mysql_fetch_array($consulta_veri))
			{
				$total_veri += $fila_veri["piezas"];
			}		
			$verificado_piezas_impresion=";".$total_veri;
		}
		else
			{
				$verificado_piezas_impresion="";
			}			
		if ($verificado_peso==1)
		{
			// identificando peso verificado
			$total_veri=0;
			$sql_verificacion="SELECT peso FROM peso_verificado WHERE id_guia=$id_guia";
			$consulta_veri=mysql_query ($sql_verificacion,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			while ($fila_veri=mysql_fetch_array($consulta_veri))
			{
				$total_veri += $fila_veri["peso"];
			}		
			$verificado_peso_impresion=";".$total_veri;
		}
		else
			{
				$verificado_peso_impresion="";
			}				
		if ($destino==1)
		{	
			// Destino
			$id_disposicion=$fila["id_disposicion"];
			//Evaluar si la disposicion no exige ningun tipo de deposito
			if ($id_disposicion ==28 || $id_disposicion ==21 || $id_disposicion ==20 || $id_disposicion ==19 || $id_disposicion ==25 || $id_disposicion ==29 || $id_disposicion ==23 || $id_disposicion ==13 || $id_disposicion ==15)
				{
					$sql3="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
					$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 6: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
					$fila3=mysql_fetch_array($consulta3);
					$destino_impresion=";".$fila3["nombre"];
				}
			else
			{
				$id_deposito=$fila["id_deposito"];
				$sql3="SELECT nombre FROM deposito WHERE id='$id_deposito'";
				$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 7: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$fila3=mysql_fetch_array($consulta3);
				$destino_impresion=";".$fila3["nombre"];
			}
			if ($id_disposicion == 26 || $id_disposicion == 27) 
			{
				$destino_impresion=";"."BODEGA DIAN";
			}				
		}
		else
			{
				$destino_impresion="";
			}

		if ($despacho==1)
		{	
			//DESPACHOS*********************************************************************************
			$id_disposicion=$fila["id_disposicion"];
			$id_tipo_bloqueo=$fila["id_tipo_bloqueo"];
			//cuando ya despacharon la mercancia crea un vinculo al despacho segun el tipo de disposicion
			if ($id_tipo_bloqueo==4 or $id_tipo_bloqueo==3)
			{
				//remesas - depositos
				if ($id_disposicion==10 || $id_disposicion==11 || $id_disposicion==18 || $id_disposicion==22)
				{
					$sql_despacho="SELECT id_remesa FROM carga_remesa WHERE id_guia='$id_guia'";
					$tipo=1;
				}
				//cabotajes
				if ($id_disposicion==12 || $id_disposicion==13 || $id_disposicion==14 || $id_disposicion==16 || $id_disposicion==17 || $id_disposicion==23 || $id_disposicion==24)
				{
					$sql_despacho="SELECT id FROM cabotaje WHERE id_guia='$id_guia'";	
					$tipo=2;
				}
				//descargues directos
				if ($id_disposicion==19 || $id_disposicion==20 || $id_disposicion==21)
				{
					$sql_despacho="SELECT id FROM descargue_directo WHERE id_guia='$id_guia'";
					$tipo=3;
				}
				//Guias de correo
				if ($id_disposicion == 26 || $id_disposicion == 27)
				{
					$sql_despacho="SELECT id_correo FROM carga_correo WHERE id_guia='$id_guia'";	
					$tipo=4;
				}
				//Otros Despachos
				if ($id_disposicion==15 || $id_disposicion==28 || $id_disposicion==29)
				{
					$sql_despacho="SELECT id_otros FROM carga_otros WHERE id_guia='$id_guia'";	
					$tipo=5;
				}
				$despacho_impresion="";
				$consulta2=mysql_query ($sql_despacho,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila2=mysql_fetch_array($consulta2))
				{
					switch ($tipo)
					{
						case 1:
							$despacho_impresion=$despacho_impresion." ".$fila2['id_remesa'];
						break;
						case 2:
							$despacho_impresion=$despacho_impresion." ".$fila2['id'];
						break;
						case 3:
							$despacho_impresion=$despacho_impresion." ".$fila2['id'];
						break;
						case 4:
							$despacho_impresion=$despacho_impresion." ".$fila2['id_correo'];
						break;
						case 5:
							$despacho_impresion=$despacho_impresion." ".$fila2['id_otros'];
						break;
					}
				}
				$despacho_impresion=";".$despacho_impresion;
			}
			// *****************************************************************************************
		}
		else
			{
				$despacho_impresion="";
			}
		if ($vuelo==1)
		{	
			$vuelo_impresion=";".$fila["nvuelo"];
		}
		else
			{
				$vuelo_impresion="";
			}

		if ($manifiesto==1)
		{	
			$manifiesto_impresion=";".$fila["nmanifiesto"];
		}
		else
			{
				$manifiesto_impresion="";
			}

		if ($embarcador==1)
		{	
			$embarcador_impresion=$fila["id_embarcador"];
			//carga dato adicionales
			$sql_add="SELECT nombre FROM embarcador WHERE id='$embarcador_impresion'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila_add=mysql_fetch_array($consulta_add);
			$embarcador_impresion=";".$fila_add["nombre"];
			//************************
		}
		else
			{
				$embarcador_impresion="";
			}
		if ($consignatario==1)
		{	
			$consignatario_impresion=$fila["id_consignatario"];
			//carga dato adicionales
			$sql_add="SELECT nombre FROM consignatario WHERE id='$consignatario_impresion'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila_add=mysql_fetch_array($consulta_add);
			$consignatario_impresion=";".$fila_add["nombre"];
			//************************
		}
		else
			{
				$consignatario_impresion="";
			}

		if ($agente_carga==1)
		{	
			$agente_carga_impresion=$fila["id_agentedecarga"];
			//carga dato adicionales
			$sql_add="SELECT razon_social FROM agente_carga WHERE id='$agente_carga_impresion'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila_add=mysql_fetch_array($consulta_add);
			$agente_carga_impresion=";".$fila_add["razon_social"];
			//************************
		}
		else
			{
				$agente_carga_impresion="";
			}
			
		if ($fecha_corte==1)
		{	
			$fecha_corte_impresion=";".$fila["fecha_corte"];
		}
		else
			{
				$fecha_corte_impresion="";
			}

		if ($asignacion_origen==1)
		{	
			$asignacion_origene_impresion=";".$fila["asignacion_directa"];
		}
		else
			{
				$asignacion_origene_impresion="";
			}

		if ($reasignacion==1)
		{	
			$reasignacion_impresion=";".$fila["reasignacion"];
		}
		else
			{
				$reasignacion_impresion="";
			}
			
		if ($precursor==1)
		{	
			$precursor_impresion=";".$fila["precursores"];
		}
		else
			{
				$precursor_impresion="";
			}

		if ($tipo_carga==1)
		{	
			$tipo_carga_impresion=$fila["id_tipo_carga"];
			//carga dato adicionales
			$sql_add="SELECT nombre FROM tipo_carga WHERE id='$tipo_carga_impresion'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila_add=mysql_fetch_array($consulta_add);
			$tipo_carga_impresion=";".$fila_add["nombre"];
			//************************
		}
		else
			{
				$tipo_carga_impresion="";
			}

		if ($disposicion==1)
		{	
			$disposicion_impresion=$fila["id_disposicion"];
			//carga dato adicionales
			$sql_add="SELECT nombre FROM disposicion_cargue WHERE id='$disposicion_impresion'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila_add=mysql_fetch_array($consulta_add);
			$disposicion_impresion=";".$fila_add["nombre"];
			//************************
		}
		else
			{
				$disposicion_impresion="";
			}

		if ($factura==1)
		{	
			$factura_impresion=";".$fila["nfactura"];
		}
		else
			{
				$factura_impresion="";
			}

		if ($valor_factura==1)
		{	
			$valor_factura_impresion=";".number_format($fila["valor_factura"],0,",",".");
		}
		else
			{
				$valor_factura_impresion="";
			}

		if ($iva==1)
		{	
			$iva_impresion=";".number_format($fila["iva"],0,",",".");
		}
		else
			{
				$iva_impresion="";
			}

		if ($fecha_factura==1)
		{	
			$fecha_factura_impresion=";".$fila["fecha_factura"];
		}
		else
			{
				$fecha_factura_impresion="";
			}

		if ($facturadoa==1)
		{	
			$facturadoa_impresion=";".$fila["facturadoa"];
		}
		else
			{
				$facturadoa_impresion="";
			}

		if ($cumplido_remesa==1)
		{
			//carga dato adicionales
			$sql_add="SELECT c.id_remesa, r.foto_cumplido FROM carga_remesa c LEFT JOIN remesa r ON c.id_remesa = r.id WHERE c.id_guia ='$id_guia'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila_add=mysql_fetch_array($consulta_add);
			
			//$cumplido_remesa_impresion=";".$fila_add["foto_cumplido"];
			
			if ($fila_add["foto_cumplido"] == "")
			{
				$cumplido_remesa_impresion=";N";
			}
			else 
				{
					$cumplido_remesa_impresion=";S";
				}
		}
		else
			{
				$cumplido_remesa_impresion="";
			}		
			
		if ($reg_foto_bodega==1)
		{
			//carga dato adicionales
			$sql_add="SELECT id FROM registro_fotografico WHERE id_guia =' $id_guia' AND seccion = 'foto_bodega'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$nfilas_add=mysql_num_rows($consulta_add);
			if ($nfilas_add > 0)
			{
				$reg_foto_bodega_impresion=";S";
			}
			else 
				{
					$reg_foto_bodega_impresion=";N";
				}
		}
		else
			{
				$reg_foto_bodega_impresion="";
			}		
			
		if ($reg_foto_seguridad==1)
		{
			//carga dato adicionales
			$sql_add="SELECT id FROM registro_fotografico WHERE id_guia =' $id_guia' AND seccion = 'foto_seguridad'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$nfilas_add=mysql_num_rows($consulta_add);
			if ($nfilas_add > 0)
			{
				$reg_foto_seguridad_impresion=";S";
			}
			else 
				{
					$reg_foto_seguridad_impresion=";N";
				}
		}
		else
			{
				$reg_foto_seguridad_impresion="";
			}		

		if ($reg_foto_despacho==1)
		{
			//carga dato adicionales
			$sql_add="SELECT id FROM registro_fotografico WHERE id_guia =' $id_guia' AND seccion = 'foto_despacho'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$nfilas_add=mysql_num_rows($consulta_add);
			if ($nfilas_add > 0)
			{
				$reg_foto_despacho_impresion=";S";
			}
			else 
				{
					$reg_foto_despacho_impresion=";N";
				}
		}
		else
			{
				$reg_foto_despacho_impresion="";
			}		

			
		if ($planilla_recepcion==1)
		{
			//carga dato adicionales
			$sql_add="SELECT r.planilla_recepcion FROM remesa r LEFT JOIN carga_remesa c ON r.id = c.id_remesa WHERE c.id_guia =' $id_guia'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$nfilas_add=mysql_num_rows($consulta_add);
			if ($nfilas_add > 0)
			{
				$planilla_recepcion_impresion=";S";
			}
			else 
				{
					$planilla_recepcion_impresion=";N";
				}
		}
		else
			{
				$planilla_recepcion_impresion="";
			}		
			
		fputs($CL,"$i;$fecha_inconsistencia;$aerolinea$vuelo_impresion$manifiesto_impresion;$tipo_guia$master$hija_impresion$piezas_impresion$peso_impresion$volumen_impresion$piezadespaletizado_impresion$pesodespaletizado_impresion$descripcion_impresion$verificado_piezas_impresion$verificado_peso_impresion$destino_impresion$despacho_impresion$embarcador_impresion$consignatario_impresion$agente_carga_impresion$fecha_corte_impresion$asignacion_origene_impresion$reasignacion_impresion$precursor_impresion$tipo_carga_impresion$disposicion_impresion$factura_impresion$valor_factura_impresion$iva_impresion$fecha_factura_impresion$facturadoa_impresion$cumplido_remesa_impresion$reg_foto_bodega_impresion$reg_foto_seguridad_impresion$reg_foto_despacho_impresion$planilla_recepcion_impresion; \n");
	}
	fputs($CL,";REPORTE CREADO POR;$usuario; \n");
	fputs($CL,";FECHA;$ano-$mes-$dia; \n");
	fputs($CL,";HORA;$hora_registro; \n");
	fputs($CL,";FIN DEL REPORTE; \n");
	fclose($CL);
	echo '
			<p class="titulo_tab_principal">Archivo de Reporte</p>
			<hr>
			<br>
			<p align="center">El archivo se ha generado de manera Exitosa, oprima el bot&oacute;n para descargarlo.</p>
			<br>
			<br>
			<p align="center">
				<button type="button" onclick="document.location=\''.$nombre_archivo.'\'";>
						<img src="../imagenes/descargar-act2.png" title="Descargar"/><br>
						Descargar
				</button>
			</p>
			<p align="center">
				<img src="../imagenes/excel.jpg" width="45" height="43" align="absmiddle" />
				Recomendamos el uso de Microsoft Excel para la lectura de este archivo.
			</p>
		';
}
else
{
	echo '
		<p align="center"><font size="+3"><strong>ATENCION</strong></font></p>
		<hr>
		<br>
		<p align="center">No existen GU&Iacute;AS en EN ESE RANGO para generar un REPORTE</p>
		';
}

?>
<script language="javascript">
	document.getElementById("cargando").innerHTML="";
</script>
</body>
</html>
