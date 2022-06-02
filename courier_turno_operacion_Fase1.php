<?php
require("config/configuracion.php");
$id_linea=$_REQUEST['id_linea'];
$id_usuario=$_REQUEST['id_usuario'];
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$datetime= date("Y-m-d H:i:s");

//Consulta el turno que será Atendido
$sql="SELECT id,no_turno,id_courier FROM courier_turno WHERE estado ='C' ORDER BY date_creacion ASC";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error 1'.mysql_error()));
$fila = mysql_fetch_array($consulta);
$id_turno=$fila['id'];
$id_courier=$fila['id_courier'];

//Consulta Auxiliar
$sql2="SELECT nombre FROM couriers WHERE id ='$id_courier'";
$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 2'.mysql_error()));
$fila2 = mysql_fetch_array($consulta2);
$courier=$fila2['nombre'];

//Consulta Auxiliar
$sql2="SELECT nombre FROM courier_linea WHERE id ='$id_linea'";
$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 3'.mysql_error()));
$fila2 = mysql_fetch_array($consulta2);
$linea=$fila2['nombre'];


//Actualiza los datos en el turno  
$sql="UPDATE courier_turno SET id_linea='$id_linea', id_funcionario_atencion='$id_usuario', date_inicio_atencion='$datetime', estado='A' WHERE id = '$id_turno'";
mysql_query($sql,$conexion) or die (exit('Error 4'.mysql_error()));

$no_turno=$fila['no_turno'];
$metadata=$id_turno.'**-**
	  	<table width="650px" align="center">
	        <tr>
	          <td align="center" class="celda_tabla_principal" colspan="2"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
	        </tr>
	        <tr>
	          <td align="center" class="celda_tabla_principal" colspan="2"><div class="letreros_tabla">Seleccione una de las opciones cuando finalice la Atenci&oacute;n del turno</div></td>
	        </tr>
	        <tr>
	          <td align="center" class="celda_tabla_principal celda_boton">
	          	<div class="letreros_tabla">
					<button type="button" onclick="startFase2(\'F\')">
						<div style="font-size: 30px">F</div>						
						Finalizar
					</button>          		
	          	</div>
	          </td>
	          <td align="center" class="celda_tabla_principal celda_boton">
	          	<div class="letreros_tabla">
					<button type="button" onclick="startFase2(\'P\')">
						<div style="font-size: 30px">P</div>						
						&nbsp;Perder&nbsp;&nbsp;						
					</button>
	  			</div>
	  		  </td>
			</tr>
	        <tr>
	          <td align="center" class="celda_tabla_principal celda_boton">
	          	<div class="letreros_tabla">Finalice el turno actual</div>
	          </td>
	          <td align="center" class="celda_tabla_principal celda_boton">
	          	<div class="letreros_tabla">Perder el Turno</div>
	  		  </td>
			</tr>

		</table>
          <table width="650px" align="center">
                <tr>
                  <td align="center" class="celda_tabla_principal" colspan="4">
                  		<div class="letreros_tabla">
                  			Turno                  			
                  			<h1>'.$no_turno.'</h1>
                  			<br>
                  			'.$courier.'
                  		</div>
                  </td>
                </tr>
                <tr>
                  <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">No</div></td>
                  <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guias</div></td>
                  <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
                  <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
                </tr>
          ';

//Consulta auxiliar de guias asociadas
$sql2="SELECT id_guia FROM courier_turno_guia WHERE id_turno='$id_turno'";
$consulta2=mysql_query($sql2,$conexion) or die (exit('Error 5'.mysql_error()));
$cont=1;
while($fila2=mysql_fetch_array($consulta2))
{
  //Carga la información de la guia
  $id_guia=$fila2['id_guia'];  
  $sql3="SELECT master,piezas,peso FROM guia WHERE id='$id_guia'";
  $consulta3=mysql_query ($sql3,$conexion) or die (exit('Error 6'.mysql_error()));
  $fila3=mysql_fetch_array($consulta3);
  $metadata .= '<tr>
                  <td align="center" class="celda_tabla_principal celda_boton">'.$cont.'</td>
                  <td align="center" class="celda_tabla_principal celda_boton">'.$fila3['master'].'</td>
                  <td align="center" class="celda_tabla_principal celda_boton">'.$fila3['piezas'].'</td>
                  <td align="center" class="celda_tabla_principal celda_boton">'.$fila3['peso'].'</td>
                </td>
                ';
  $cont++;
  
  //Actualizacion de los datos de la guia
  $sql="UPDATE guia SET courier_id_linea='$id_linea' WHERE id = '$id_guia'";
  mysql_query($sql,$conexion) or die (exit('Error 7'.mysql_error()));
  
  //Crea registro en el historial de la guia
  $registro_novedad ="Inicio de Atenci&oacute;n al Turno:".
                    "<br>".
                    $no_turno.
                    "<br> En <br>".                    
                    $linea;
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
  mysql_query($sql_trak,$conexion) or die (exit('Error 8'.mysql_error()));
}
$metadata .= '</table>';
echo $metadata;
?>

