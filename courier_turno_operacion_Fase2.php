<?php
require("config/configuracion.php");
$id_turno=$_REQUEST['id_turno'];
$id_linea=$_REQUEST['id_linea'];
$id_usuario=$_REQUEST['id_usuario'];
$boton=$_REQUEST['boton'];

$fecha=date("Y-m-d");
$hora=date("H:i:s");
$datetime= date("Y-m-d H:i:s");

if($boton=="F")
{
  $estado="F";
  $registro_novedad="Finalizaci&oacute;n de Atenci&oacute;n al Turno:<br>";
  $mensaje="Turno Finalizado";
}
else
{
  $estado="P"; 
  $registro_novedad="P&eacute;rdida del Turno:<br>";
  $mensaje="Turno Perdido";
}
//Actualizacion de datos del turno
$sql="UPDATE courier_turno SET date_fin_atencion='$datetime', estado='$estado' WHERE id = '$id_turno'";
mysql_query($sql,$conexion) or die (exit('Error 1'.mysql_error()));

//Consulta el turno que esta Atendido
$sql="SELECT no_turno FROM courier_turno WHERE id ='$id_turno'";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error 2'.mysql_error()));
$fila = mysql_fetch_array($consulta);
$no_turno=$fila['no_turno'];


//Consulta auxiliar de guias asociadas
$sql2="SELECT id_guia FROM courier_turno_guia WHERE id_turno='$id_turno'";
$consulta2=mysql_query($sql2,$conexion) or die (exit('Error 3'.mysql_error()));
while($fila2=mysql_fetch_array($consulta2))
{
  //Carga la información de la guia
  $id_guia=$fila2['id_guia'];  

  //Actualizacion de los datos de la guia
  $sql="UPDATE guia SET courier_id_linea='$id_linea' WHERE id = '$id_guia'";
  mysql_query($sql,$conexion) or die (exit('Error 4'.mysql_error()));
  
  //Crea registro en el historial de la guia
  $registro_novedad .=$no_turno;
  $sql_trak="INSERT INTO tracking (id_guia,
                                 fecha_creacion,
                                 hora,
                                 evento,
                                 tipo_tracking,
                                 id_usuario) 
                                  VALUE ('$id_guia',
                                       '$fecha',
                                       '$hora',
                                       '$registro_novedad',
                                       '1',
                                       '$id_usuario')";
  mysql_query($sql_trak,$conexion) or die (exit('Error 5'.mysql_error()));
}
echo '<div class="celda_tabla_principal caja" ><img src="imagenes/check_green.png" height="60"><br>'.$mensaje.'</div>';
?>


