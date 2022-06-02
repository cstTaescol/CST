<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<html>
	<head>
    	<style>
			body{
				background-image:url(imagenes/background.png);
				background-repeat:repeat;
				margin:0;
				padding:0;
			}
			.msg{
				border-radius: 50px;
				background-color: #999999;
				position: relative;
				color:#FFF;
				text-align:center;
				font-family: Arial, Helvetica, sans-serif;
				font-size:25px;	
				font-weight:bold;
				margin-left: auto;
				width: 500px;
				height:100px;
				margin-right: auto;
				top: 30%;
			}
		</style>
    </head>
    <body>
</html>
<?php
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
//$contador=$_POST['contador'];
$id_aerolinea=$_REQUEST['aerolinea'];
$id_vuelo=$_REQUEST['vuelo'];

$sql="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$nvuelo=$fila["nvuelo"];

//1. Actualizar datos de la guia
$id_guia=$_REQUEST["id_guia"];
//Consulta adicional		
$sql="SELECT * FROM guia WHERE id='$id_guia'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$faltante_total=$fila["faltante_total"];
$piezas_faltantes=$fila["piezas_faltantes"];
$peso_faltante=$fila["peso_faltante"];
$id_vuelo_de_faltante=$fila["id_vuelo"];
$id_tipo_guia=$fila["id_tipo_guia"];
$id_aerolinea=$fila["id_aerolinea"];
$master=$fila["master"];
$hija=$fila["hija"];
$cantidad_hijas=$fila["cantidad_hijas"];
$formulario=$fila["formulario"];
$precursores=$fila["precursores"];
$asignacion_directa=$fila["asignacion_directa"];
$id_tipo_carga=$fila["id_tipo_carga"];
$piezas_faltantes=$fila["piezas_faltantes"];
$peso_faltante=$fila["peso_faltante"];
$id_agentedecarga=$fila["id_agentedecarga"];
$id_embarcador=$fila["id_embarcador"];
$id_consignatario=$fila["id_consignatario"];
$id_deposito=$fila["id_deposito"];
$id_disposicion=$fila["id_disposicion"];
$id_administracion_aduana=$fila["id_administracion_aduana"];
$descripcion=$fila["descripcion"];
$flete=$fila["flete"];
$fecha_corte=$fila["fecha_corte"];
$nfactura=$fila["nfactura"];
if($fila["valor_factura"]=="")
	$valor_factura="NULL";
else
	$valor_factura="'".$fila["valor_factura"]."'";

$iva=$fila["iva"];
if($fila["fecha_factura"]=="")
	$fecha_factura="NULL";
else
	$fecha_factura="'".$fila["fecha_factura"]."'";

$facturadoa=$fila["facturadoa"];
$cod_departamento_destino=$fila["cod_departamento_destino"];
$cod_ciudad_destino=$fila["cod_ciudad_destino"];
$cod_pais_embarque=$fila["cod_pais_embarque"];
$cod_ciudad_embarque=$fila["cod_ciudad_embarque"];

//*****************
if($faltante_total=="S")
{
	//Inactiva el registro del inventario de inconsistencias
	$sql_update="UPDATE inconsistencias SET estado='I' WHERE id_guia='$id_guia' AND id_vuelo='$id_vuelo_de_faltante'";
	mysql_query($sql_update,$conexion) or die ('<font color="red" size="5">ATENCION: Error al modificar las INCONSISTENCIAS'.mysql_error().' Comun&iacute;quese con el Soporte T&eacute;cnico</font>');
	//Modifica la guia para agregarla a este nuevo vuelo
	$sql_update="UPDATE guia SET id_tipo_bloqueo='7',id_vuelo='$id_vuelo',piezas_faltantes=NULL, peso_faltante=NULL, piezas_inconsistencia=NULL, peso_inconsistencia=NULL, volumen_inconsistencia=NULL,faltante_total='N' WHERE id='$id_guia'";
	mysql_query($sql_update,$conexion) or die ('<font color="red" size="5">ATENCION: Error al ingresar la Gu&iacute;a:'.mysql_error().' Comun&iacute;quese con el Soporte T&eacute;cnico</font>');			
}
else
	{
		$sql_update="UPDATE inconsistencias SET estado='I' WHERE id_guia='$id_guia' AND id_vuelo='$id_vuelo_de_faltante'";
		mysql_query($sql_update,$conexion) or die ('<font color="red" size="5">ATENCION: Error al modificar las INCONSISTENCIAS'.mysql_error().' Comun&iacute;quese con el Soporte T&eacute;cnico</font>');				
		$sql_update="INSERT INTO guia (id_tipo_guia,
									   id_vuelo,
									   id_aerolinea,
									   master,
									   hija,
									   cantidad_hijas,
									   formulario,
									   precursores,
									   asignacion_directa,
									   id_tipo_carga,
									   piezas,
									   peso,
									   volumen,
									   hora,
									   fecha_creacion,
									   id_usuario,
									   id_tipo_bloqueo,
									   id_agentedecarga,
									   id_embarcador,
									   id_consignatario,
									   id_deposito,
									   id_disposicion,
									   id_administracion_aduana,
									   descripcion,
									   flete,
									   fecha_corte,
									   nfactura,
									   valor_factura,
									   iva,
									   fecha_factura,
									   facturadoa,
									   cod_departamento_destino,
									   cod_ciudad_destino,
									   cod_pais_embarque,
									   cod_ciudad_embarque) 
										VALUE ('$id_tipo_guia',
												'$id_vuelo',
												'$id_aerolinea',
												'$master',
												'$hija',
												'$cantidad_hijas',
												'$formulario',
												'$precursores',
												'$asignacion_directa',
												'$id_tipo_carga',
												'$piezas_faltantes',
												'$peso_faltante',
												'$peso_faltante',
												'$hora',
												'$fecha',
												'$id_usuario',
												'7',
												'$id_agentedecarga',
												'$id_embarcador',
												'$id_consignatario',
												'$id_deposito',
												'$id_disposicion',
												'$id_administracion_aduana',
												'$descripcion',
												'$flete',
												'$fecha_corte',
												'$nfactura',
												$valor_factura,
												'$iva',
												$fecha_factura,
												'$facturadoa',
												'$cod_departamento_destino',
												'$cod_ciudad_destino',
												'$cod_pais_embarque',
												'$cod_ciudad_embarque')";
		mysql_query($sql_update,$conexion) or die ('<font color="red" size="5">ATENCION: Error al ingresar la Gu&iacute;a:'.mysql_error().' Comun&iacute;quese con el Soporte T&eacute;cnico</font>');
		$id_guia_nueva = mysql_insert_id($conexion); //obtiene el id de la ultima inserción
		
		//almacenamiento del traking
		$sql_trak="INSERT INTO tracking (id_guia,
										 fecha_creacion,
										 hora,
										 evento,
										 tipo_tracking,
										 id_usuario) 
											value ('$id_guia_nueva',
												   '$fecha',
												   '$hora',
												   'GUIA CREADA A PARTIR DE UNA GUIA CON FALTANTE PARCIAL.<BR>
												   ASIGNADA AL VUELO: $nvuelo',
												   '1',
												   '$id_usuario')";
		mysql_query($sql_trak,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		}

//2. almacenamiento del traking
$sql_trak="INSERT INTO tracking (id_guia,
								 fecha_creacion,
								 hora,
								 evento,
								 tipo_tracking,
								 id_usuario) 
									value ('$id_guia',
										   '$fecha',
										   '$hora',
										   'GUIA FALTANTE ASIGNADA AL VUELO: $nvuelo',
										   '1',
										   '$id_usuario')";
mysql_query($sql_trak,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");

//3. javascript que  permite actualizar la ventana padre y cerrar la propia
echo '<script language="javascript">
		window.opener.location.reload();
		self.close();
	</script>';
?>