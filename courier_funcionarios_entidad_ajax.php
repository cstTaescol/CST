<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$buffer = null;
if(isset($_REQUEST["id_entidad"])){
  $id_entidad=$_REQUEST["id_entidad"];
  $sql="SELECT id,nombre FROM courier_funcionario WHERE id_entidad='$id_entidad' AND estado='A' ORDER BY nombre ASC";
  $consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
  while($fila=mysql_fetch_array($consulta))
  {
    $buffer .= '--->'.$fila['id'] .'{{{{'.$fila['nombre'].'';
  }
  echo $buffer;
}
?>