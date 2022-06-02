<?php
//Calculamos la fecha de vencimiento de la guia basandose en los dias feriados parametrizados.
//*-----------------------------------------------------------------------------------------------------
$vencimiento=explode("-",$fecha_finalizacion);
$ano=$vencimiento[0];
$mes=$vencimiento[1];
$dia=$vencimiento[2];

$bisiesto=0;
$cont=0;
$sql4="SELECT ano FROM bisiestos WHERE ano = '$ano'";
$consulta4=mysql_query ($sql4,$conexion) or die ("ERROR FECHA: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta4);
if ($nfilas == 1)
	$bisiesto=1;

if ($mes==4 || $mes==6 || $mes==9 || $mes==11)
	$limited=30;
	else
		$limited=31;
if ($bisiesto==1 && $mes==2)
	$limited=29;

if ($bisiesto==0 && $mes==2)
	$limited=28;


while ($cont < $limiteportipo) //verificar dias para el vencimiento segunn el tipo de guia
{
$dia=$dia+1;
if ($dia <= $limited)
	{
		$fecha_vencimiento="$ano-$mes-$dia";
	}
	else
	{
		$dia=1;
		$mes=$mes+1;
		if ($mes <= 12)
		{
			$fecha_vencimiento="$ano-$mes-$dia";
		}
		else
			{
				$ano=$ano+1;
				$mes=1;
				$fecha_vencimiento="$ano-$mes-$dia";
			}
	}
	$sql4="SELECT id FROM feriados WHERE fecha = '$fecha_vencimiento' AND estado !='I'";
	$consulta4=mysql_query ($sql4,$conexion) or die ("ERROR FECHA: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$nfilas=mysql_num_rows($consulta4);
	if ($nfilas < 1) //cuando No sea feriado
		{
			$cont=$cont+1;
		}
}
?>