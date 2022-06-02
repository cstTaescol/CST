<?php
require("config/configuracion.php");
$metadata="";
//Consulta el turno que será Atendido
$sql="SELECT * FROM courier_turno WHERE visualizado=FALSE AND estado='C' ORDER BY date_creacion ASC LIMIT 0,1";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error 1'.mysql_error()));
$fila=mysql_fetch_array($consulta);
$id_turno=$fila['id'];
$id_courier=$fila['id_courier'];
$no_turno=$fila['no_turno'];
$id_linea=$fila['id_linea'];
$nfilas=mysql_num_rows($consulta);
$metadata=$nfilas.'**-**';
// Actualizacion del turno mostrado
$sql2="UPDATE courier_turno SET visualizado=TRUE WHERE id='$id_turno'";
mysql_query ($sql2,$conexion) or die (exit('Error 2'.mysql_error()));
/********************************/


//Consulta Auxiliar
$sql2="SELECT nombre FROM couriers WHERE id ='$id_courier'";
$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 3'.mysql_error()));
$fila2 = mysql_fetch_array($consulta2);
$courier=$fila2['nombre'];
//Consulta Auxiliar
$sql2="SELECT nombre FROM courier_linea WHERE id ='$id_linea'";
$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 4'.mysql_error()));
$fila2 = mysql_fetch_array($consulta2);
$linea=$fila2['nombre'];

//Se evalua cuando hay un turno nuevo disponible
if($no_turno =="")
{
	$metadata .='**-**';
}
else
{
	$metadata .='
				<div class="fakeimg" style="height: 600px;"> 
					<br>
			        <span style="font-size: 175px;">'.$no_turno.'</span>
			        <br>
			        <span style="font-size: 70px;">'.$linea.'</span>        
			        <br>
			        <span style="font-size: 50px;">'.$courier.'</span>
			    </div>                  
			  	  	**-**
		  	  	';
}

//Consulta de los ultimos 3 turnos atendidos
$sql="SELECT id,no_turno,id_courier,id_linea FROM courier_turno WHERE visualizado=TRUE ORDER BY date_creacion DESC LIMIT 0,3";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error 5'.mysql_error()));
while($fila=mysql_fetch_array($consulta))
{
	$id_turno=$fila['id'];
	$id_courier=$fila['id_courier'];
	$no_turno=$fila['no_turno'];
	$id_linea=$fila['id_linea'];
	//Consulta Auxiliar
	$sql2="SELECT nombre FROM couriers WHERE id ='$id_courier'";
	$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 5'.mysql_error()));
	$fila2 = mysql_fetch_array($consulta2);
	$courier=$fila2['nombre'];
	//Consulta Auxiliar
	$sql2="SELECT nombre FROM courier_linea WHERE id ='$id_linea'";
	$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 6'.mysql_error()));
	$fila2 = mysql_fetch_array($consulta2);
	$linea=$fila2['nombre'];

	$metadata .='
				<div class="fakeimg">
					<br>
					<h1>'.$no_turno.'</h1>
					<h2>'.$linea.'</h2>
					<h3>'.$courier.'</h3>
				</div>
				<hr> 				        
	        ';
}


echo $metadata;
?>

