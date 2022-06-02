<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */     						
require("config/configuracion.php");
if(isset($_SESSION["id_usuario"]))
{
	$fecha=date("Y").date("m").date("d");
	$hora=date("H:i:s");
	$usuario=$_SESSION["id_usuario"];		
	$metadata='                
            <table width="650px" align="center">
                <tr>
                  <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">...</div></td>
                  <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">No. Turno</div></td>
                  <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Courier</div></td>
                  <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
                  <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Estado</div></td>
                </tr>
             ';

  //Consulta inicial
  $fecha_consulta=isset($_GET['fecha_consulta']) ? $_GET['fecha_consulta'] : $_POST['fecha_consulta'];
  $sql="SELECT * FROM courier_turno WHERE date_creacion LIKE '%$fecha_consulta%' ORDER BY date_creacion DESC";
  $consulta=mysql_query ($sql,$conexion) or die (exit('Error 1'.mysql_error()));
  $contC=0;
  $contA=0;
  $contF=0;
  while($fila=mysql_fetch_array($consulta))
  {
      switch ($fila['estado']) 
      {
        case 'C':
          $estado="Esperando";
          $contC++;  
        break;
        
        case 'A':
          $estado="Atendiendo";
          $contA++;
        break;

        case 'F':
          $estado="Finalizado";
          $contF++;
        break;

		case 'P':
		  $estado="Perdido";			  
		break;        
      }
      $courier=$fila['id_courier'];  
      $sql_aux="SELECT nombre FROM couriers WHERE id = '$courier'";
      $consulta_aux=mysql_query ($sql_aux,$conexion) or die (exit('Error 2'.mysql_error()));      
      $fila_aux=mysql_fetch_array($consulta_aux);
      $courier=$fila_aux['nombre'];
      $metadata .= '
                    <tr>
                      <td align="center" class="celda_tabla_principal celda_boton"><button type="button" onclick="document.location=\'courier_turno_consulta.php?id_registro='.$fila['id'].'\'"><img src="imagenes/buscar-act.png"></button></td>
                      <td align="center" class="celda_tabla_principal celda_boton">'.$fila['no_turno'].'</td>
                      <td align="center" class="celda_tabla_principal celda_boton">'.$courier.'</td>
                      <td align="center" class="celda_tabla_principal celda_boton">'.$fila['date_creacion'].'</td>
                      <td align="center" class="celda_tabla_principal celda_boton">'.$estado.'</td>                          
                    </tr>
                  ';
    }

    $metadata .= '
                    </table>
                    <div id="column-left" class="flotando" style="top: 200px;">
                      <strong><h3>TURNOS</h3></strong>
                    </div>
                    <div id="column-left" class="flotando" style="background-image: linear-gradient(-90deg, orange, red); top: 250px;">
                      <strong>Espera<hr></strong>
                      <h1>'. $contC .'</h1>
                    </div>
                    <div id="column-left" class="flotando" style="background-image: linear-gradient(-90deg, orange, orange); top: 400px;">
                      <strong>Atenci&oacute;n<hr></strong>
                      <h1>'. $contA .'</h1>
                    </div>
                    <div id="column-left" class="flotando" style="background-image: linear-gradient(-90deg, #4682B4, green); top: 550px;">
                      <strong>Finalizados<hr></strong>
                      <h1>'. $contF .'</h1>  
                    </div>
                ';                
    echo $metadata;	
}
else
{
	echo "Error 0";
}
?>
