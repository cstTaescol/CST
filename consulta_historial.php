<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
include("config/control_tiempo.php");
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<?php
require("menu.php");
//Privilegios Consultar Todo el Modulo
//$id_objeto=65; 
//include("config/provilegios_modulo.php");  
//---------------------------

if (isset($_REQUEST["id_guia"]))
{
	$id_guia=$_REQUEST["id_guia"];
	$sql="SELECT * FROM tracking WHERE id_guia = '$id_guia' ORDER BY fecha_creacion ASC, hora ASC";
	$consulta=mysql_query($sql,$conexion) or die ("Error 1".mysql_error());
	$nfilas=mysql_num_rows($consulta);

	// Evalua el retorno segun el tipo de guia
	$sql3="SELECT id_tipo_guia, hija, master FROM guia WHERE id='$id_guia'";
	$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila3=mysql_fetch_array($consulta3);
	$id_tipo_guia=$fila3["id_tipo_guia"];
	$hija=$fila3['hija'];
	$master=$fila3['master'];

	switch($id_tipo_guia)
	{
		case 5:
			$retorno="consulta_guia_courier.php";
			$num_guia=$master;
		break;

		case 6:
			$retorno="consulta_guia_courier_hija.php";
			$num_guia=$hija;
		break;

		default:
			$retorno="consulta_guia.php";
			$num_guia=$hija;
		break;
	}

	$datos="";
	if ($nfilas > 0)
	{
 		while($fila=mysql_fetch_array($consulta))
		{
			$evento=$fila["evento"];
			$fecha_creacion=$fila["fecha_creacion"];
			$hora=$fila["hora"];
			$tipo_tracking=$fila["tipo_tracking"];
			$id_usuario=$fila["id_usuario"];
			//usuario
			$sql2="SELECT nombre FROM usuario WHERE id='$id_usuario'";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila2=mysql_fetch_array($consulta2);
			$nombre_usuario=$fila2['nombre'];
							
			$datos=$datos.'<tr>
							<td class="celda_tabla_principal celda_boton">'.$evento.'</td>
							<td class="celda_tabla_principal celda_boton">'.$fecha_creacion.'</td>
							<td class="celda_tabla_principal celda_boton">'.$hora.'</td>
							<td class="celda_tabla_principal celda_boton">'.$nombre_usuario.'</td>
						  </tr>';
		}
	}
}
?>
<p class="titulo_tab_principal">Historial de la Guia</p>
<p align="center" class="asterisco">
	<?php echo $num_guia ?>
	<button onclick="document.location='<?php echo $retorno ?>?id_guia=<?php echo $id_guia ?>'">
		<img src="imagenes/atras-act.png" border="0" align="absmiddle" >
	</button>	
</p>
<table align="center">
  <tr>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Suceso</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Hora</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Encargado</div></td>
  </tr>
  <?php echo $datos ?>
</table>
</body>
</html>  