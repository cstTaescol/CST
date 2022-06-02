<?php
//Reportaran por correo la informacion
$sql_mail="SELECT g.hija,g.id_consignatario,g.id_tipo_guia,g.master,c.emails FROM guia g LEFT JOIN consignatario c ON g.id_consignatario=c.id WHERE g.id='$id_guia'";
$consulta_mail=mysql_query ($sql_mail,$conexion) or die ("ERROR GUIA: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila_mail=mysql_fetch_array($consulta_mail);
$id_tipo_guia=$fila_mail['id_tipo_guia'];
$hija=$fila_mail['hija'];
$asunto="REPORTE DE GUIA: $hija";

//Verificacion de Guias para identificar destinatario del correo.
switch($id_tipo_guia)
{
	case 1: //Guias Directas
		$destinatario=$fila_mail['emails'];
		$mensaje="GUIA $hija $addmensaje";
		if ($destinatario != "")
		{	
			$sql_insert_mail="INSERT INTO historial_correo (id_guia,
											   destinatario,
											   asunto,
											   mensaje,
											   fecha,
											   hora) VALUE 
														('$id_guia',
														 '$destinatario',
														 '$asunto',
														 '$mensaje',
														 '$fecha',
														 '$hora')";
			mysql_query($sql_insert_mail,$conexion) or die ("Error al crear El CORREO ". mysql_error());
		}
	break;
	
	case 3: //Guias Hijas
		//Consultamos la informacion del consolidador en vez de la informacion de cada guia Hija.
		$master=$fila_mail['master'];
		$sql_mail2="SELECT g.id_consignatario,g.master,c.emails FROM guia g LEFT JOIN consignatario c ON g.id_consignatario=c.id WHERE g.id='$master'";
		$consulta_mail2=mysql_query ($sql_mail2,$conexion) or die ("ERROR GUIA MASTER: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_mail2=mysql_fetch_array($consulta_mail2);
		$nmaster=$fila_mail2['master'];
		$mensaje="GUIA:($nmaster) $hija  $addmensaje";
		$destinatario=$fila_mail2['emails'];
		if ($destinatario != "")
		{	
			$sql_insert_mail="INSERT INTO historial_correo (id_guia,
											   destinatario,
											   asunto,
											   mensaje,
											   fecha,
											   hora) VALUE 
														('$id_guia',
														 '$destinatario',
														 '$asunto',
														 '$mensaje',
														 '$fecha',
														 '$hora')";
			mysql_query($sql_insert_mail,$conexion) or die ("Error al crear El CORREO ". mysql_error());
		}
	break;
	
	case 4: //Guias Correo
		$destinatario=$fila_mail['emails'];
		$mensaje="GUIA $hija $addmensaje";
		if ($destinatario != "")
		{	
			$sql_insert_mail="INSERT INTO historial_correo (id_guia,
											   destinatario,
											   asunto,
											   mensaje,
											   fecha,
											   hora) VALUE 
														('$id_guia',
														 '$destinatario',
														 '$asunto',
														 '$mensaje',
														 '$fecha',
														 '$hora')";
			mysql_query($sql_insert_mail,$conexion) or die ("Error al crear El CORREO ". mysql_error());
		}
	break;
}
?>