<?php
$bisiesto=0;
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


//Se aumentan las horas de la Dian
$hh=$hh+$adicionar_horas;
if ($hh < 24) //Cuando no supera el mismo día.
{
	$fecha_limite="$ano-$mes-$dia";
	$hora_limite="$hh:$mm:$ss";
}
else //Cuando SUPERA el dia en curso
	{
		$dia++; 
		$hh=$hh-24;
		if ($dia <= $limited)//Cuando el Día es inferior al limite del mes
		{
			$fecha_limite="$ano-$mes-$dia";
			$hora_limite="$hh:$mm:$ss";
		}
		else
			{ //Cuando es necesario cambiar de mes
				$dia=1;
				$mes=$mes+1;
				if ($mes <= 12) //Cuando es el mismo año
				{
					$fecha_limite="$ano-$mes-$dia";
					$hora_limite="$hh:$mm:$ss";
				}
				else
					{  //Cuando es necesario cambiar el año
						$ano=$ano+1;
						$mes=1;
						$fecha_limite="$ano-$mes-$dia";
						$hora_limite="$hh:$mm:$ss";
					}
				
			}
	}
?>