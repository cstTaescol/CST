<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
if(isset($_REQUEST["id_guia"]))$id_guia=$_REQUEST["id_guia"];
if(isset($_REQUEST["id_registro"]))$id_registro=$_REQUEST["id_registro"];
if(isset($_REQUEST["id_entidad"]))$id_entidad=$_REQUEST["id_entidad"];
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$usuario=$_SESSION["id_usuario"];

//1. Crea registro en el historial
$sql3="SELECT f.nombre FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia e ON f.id = e.id_funcionario WHERE e.id ='$id_registro'";
$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");  
$fila3=mysql_fetch_array($consulta3);
$nombre_funcionario=$fila3['nombre'];
$registro_novedad ="Funcionario Eliminado: ".
          "<br>".
          "Nombre:".$nombre_funcionario;
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
mysql_query($sql_trak,$conexion) or die (exit('Error 4'.mysql_error()));


//2. Elimina EL usuario seleccionado
$sql="DELETE FROM courier_funcionarios_guia WHERE id = '$id_registro'";
mysql_query($sql,$conexion) or die (exit('Error 1'.mysql_error()));

//Recarga de Informacion
//Carga de datos
$nombresDian="";
$nombresTaescol="";
$nombresPolfa="";
$nombresInvima="";
$nombresIca="";
$nombresOtros="";
$nombresCourier="";

//identificando funcionario que intervienen con la guia
$sql2="SELECT id,id_funcionario,tipo FROM courier_funcionarios_guia WHERE id_guia ='$id_guia'";
$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila2=mysql_fetch_array($consulta2))
{
  //Identificando la Entidad del funcionario
  $id_registro=$fila2['id'];
  $id_funcionario=$fila2['id_funcionario'];
  $tipo=$fila2['tipo']; 
  $sql3="SELECT f.nombre, f.otros, e.nombre AS nombre_entidad FROM courier_funcionario f LEFT JOIN courier_entidades e ON f.id_entidad = e.id WHERE f.id ='$id_funcionario'";
  $consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");  
  $fila3=mysql_fetch_array($consulta3);
  $nombre_funcionario=$fila3['nombre'];
  $nombre_entidad=$fila3['nombre_entidad'];

  switch ($nombre_entidad) 
  {
    case 'DIAN':
      $nombresDian .= '<div>
                          <button type="button" title="Quitar"  onclick="aquitarUsuario('.$id_guia.','.$id_registro.',1)">
                            <img src="imagenes/cancelar-act.png" valign="middle" width="20">
                          </button>
                          <img src="imagenes/sesion.png" valign="middle" width="15"> '.$nombre_funcionario.'
                      </div>';
    break;
    case 'TAESCOL':
      $nombresTaescol .= '<div>
                              <button type="button" title="Quitar" onclick="aquitarUsuario('.$id_guia.','.$id_registro.',2)">
                                <img src="imagenes/cancelar-act.png" valign="middle" width="20">
                              </button>
                              <img src="imagenes/sesion.png" valign="middle" width="15"> '.$nombre_funcionario.'
                          </div>';
    break;
    case 'POLFA':
      $nombresPolfa .= '<div>
                              <button type="button" title="Quitar" onclick="aquitarUsuario('.$id_guia.','.$id_registro.',3)">
                                <img src="imagenes/cancelar-act.png" valign="middle" width="20">
                              </button>
                              <img src="imagenes/sesion.png" valign="middle" width="15"> '.$nombre_funcionario.'
                          </div>';
    break;
    case 'INVIMA':
      $nombresInvima .= '<div>
                              <button type="button" title="Quitar" onclick="aquitarUsuario('.$id_guia.','.$id_registro.',4)">
                                <img src="imagenes/cancelar-act.png" valign="middle" width="20">
                              </button>
                              <img src="imagenes/sesion.png" valign="middle" width="15"> '.$nombre_funcionario.'
                          </div>';
    break;
    case 'ICA':
      $nombresIca .='<div>
                              <button type="button" title="Quitar" onclick="aquitarUsuario('.$id_guia.','.$id_registro.',5)">
                                <img src="imagenes/cancelar-act.png" valign="middle" width="20">
                              </button>
                              <img src="imagenes/sesion.png" valign="middle" width="15"> '.$nombre_funcionario.'
                          </div>';
    break;
    case 'OTROS':
      $nombresOtros .= '<div>
                              <button type="button" title="Quitar" onclick="aquitarUsuario('.$id_guia.','.$id_registro.',6)">
                                <img src="imagenes/cancelar-act.png" valign="middle" width="20">
                              </button>
                              <img src="imagenes/sesion.png" valign="middle" width="15"> '.$nombre_funcionario.' - '.$fila3['otros'].'
                          </div>';
    break;   
    case 'COURIER':
        if ($tipo == "C")
        {
            $nombresCourier .= '<div>
                                    <img src="imagenes/sesion.png" valign="middle" width="15">(Cargue) '.$nombre_funcionario.'
                                </div>';
        }
        else
        {
            $nombresCourier .= '<div>
                                    <button type="button" title="Quitar" onclick="aquitarUsuario('.$id_guia.','.$id_registro.',7)">
                                      <img src="imagenes/cancelar-act.png" valign="middle" width="20">
                                    </button>
                                    <img src="imagenes/sesion.png" valign="middle" width="15"> '.$nombre_funcionario.'
                                </div>';          
        }
    break;   
  }
}


switch ($id_entidad) 
{
  case 1:
    echo $nombresDian;
  break;

  case 2:
    echo $nombresTaescol;
  break;
  
  case 3:
    echo $nombresPolfa;
  break;

  case 4:
    echo $nombresInvima;
  break;

  case 5:
    echo $nombresIca;
  break;

  case 6:
    echo $nombresOtros;
  break;  

  case 7:
    echo $nombresCourier;
  break;  
}
?>