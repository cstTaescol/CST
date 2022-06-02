<?php
$bisiesto=0;
$sql4="SELECT ano FROM bisiestos WHERE ano = '$ano2'";
$consulta4=mysql_query ($sql4,$conexion) or die ("ERROR FECHA: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta4);
if ($nfilas == 1)
	$bisiesto=1;

if ($mes2==4 || $mes2==6 || $mes2==9 || $mes2==11)
	$limited=30;
	else
		$limited=31;
if ($bisiesto==1 && $mes2==2)
	$limited=29;

if ($bisiesto==0 && $mes2==2)
	$limited=28;


//Se aumentan las horas de la Dian
$hh2=$hh2+$adicionar_horas2;
//echo "-$hh2";	

if ($hh2 < 24) //Cuando no supera el mismo día.
{
	$fecha_limite="$ano2-$mes2-$dia2";
	$hora_limite="$hh2:$mm2:$ss2";
}
else //Cuando SUPERA el dia en curso
	{
		$dia2++; 
		$hh2=$hh2-24;
		if ($dia2 <= $limited)//Cuando el Día es inferior al limite del mes
		{
			$fecha_limite="$ano2-$mes2-$dia2";
			$hora_limite="$hh2:$mm2:$ss2";
		}
		else
			{ //Cuando es necesario cambiar de mes
				$dia2=1;
				$mes2=$mes2+1;
				if ($mes2 <= 12) //Cuando es el mismo año
				{
					$fecha_limite="$ano2-$mes2-$dia2";
					$hora_limite="$hh2:$mm2:$ss2";
				}
				else
					{  //Cuando es necesario cambiar el año
						$ano2=$ano2+1;
						$mes2=1;
						$fecha_limite="$ano2-$mes2-$dia2";
						$hora_limite="$hh2:$mm2:$ss2";
					}
				
			}
	}
?>