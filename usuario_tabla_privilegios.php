<?php 
$id_usuario=$id_usuario_modificar;
?>
<table width="100%">
  <!-- FILA -->
  <tr>
    <td width="50%" valign="top">
      <table width="100%">
        <tr>
          <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">PARAMETROS</div></td>
        </tr>
          <?php
                $sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='6' ORDER BY nombre ASC";
                $consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
                while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
                {
                    $cont++;
                    $nombre=$fila_privilegios['nombre'];
                    $id_objeto=$fila_privilegios['id'];
            				//Consulta por objetos privilegiados para el usuario
            				$sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";				
            				$consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
            				$nfilas=mysql_num_rows($consulta_objetos);
            				if($nfilas > 0)
            					$seleccion="checked=\"checked\"";
            				else
            					$seleccion="";

                    echo "<tr>
              						  <td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
              						  <td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
              					  </tr>";
                }
          ?>
      </table>
    </td>
    <td width="50%" valign="top">
      <table width="100%">
        <tr>
          <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">CLAVE</div></td>
        </tr>
        <?php
              $sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='5' ORDER BY nombre ASC";
              $consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
              while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
              {
                  $cont++;
                  $nombre=$fila_privilegios['nombre'];
                  $id_objeto=$fila_privilegios['id'];
          				//Consulta por objetos privilegiados para el usuario
          				$sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";
          				$consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
          				$nfilas=mysql_num_rows($consulta_objetos);
          				if($nfilas > 0)
          					$seleccion="checked=\"checked\"";
          				else
          					$seleccion="";
  				
                  echo "<tr>
            						<td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
            						<td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
            					</tr>";
              }
        ?>
      </table>
    </td>
  </tr>
  <!-- FILA -->
  <tr>
    <td valign="top">
    <table width="100%">
      <tr>
        <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">PROCESOS DE VUELO</div></td>
      </tr>
      <?php
            $sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='8' ORDER BY nombre ASC";
            $consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
            while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
            {
                $cont++;
                $nombre=$fila_privilegios['nombre'];
                $id_objeto=$fila_privilegios['id'];
				//Consulta por objetos privilegiados para el usuario
				$sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";
				$consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
				$nfilas=mysql_num_rows($consulta_objetos);
				if($nfilas > 0)
					$seleccion="checked=\"checked\"";
				else
					$seleccion="";
				
                echo "<tr>
						<td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
						<td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
					</tr>";
            }
            ?>
    </table></td>
    <td valign="top">
    <table width="100%">
      <tr>
        <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">PROCESOS DE GUIA</div></td>
      </tr>
      <?php
            $sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='9' ORDER BY nombre ASC";
            $consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
            while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
            {
                $cont++;
                $nombre=$fila_privilegios['nombre'];
                $id_objeto=$fila_privilegios['id'];
				//Consulta por objetos privilegiados para el usuario
				$sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";
				$consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
				$nfilas=mysql_num_rows($consulta_objetos);
				if($nfilas > 0)
					$seleccion="checked=\"checked\"";
				else
					$seleccion="";
				
                echo "<tr>
						<td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
						<td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
					</tr>";
            }
            ?>
    </table></td>
  </tr>
  <tr>
    <td valign="top">
    <table width="100%">
      <tr>
        <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">CONSULTAS</div></td>
      </tr>
      <?php
            $sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='11' ORDER BY nombre ASC";
            $consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
            while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
            {
                $cont++;
                $nombre=$fila_privilegios['nombre'];
                $id_objeto=$fila_privilegios['id'];
				//Consulta por objetos privilegiados para el usuario
				$sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";
				$consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
				$nfilas=mysql_num_rows($consulta_objetos);
				if($nfilas > 0)
					$seleccion="checked=\"checked\"";
				else
					$seleccion="";
				
                echo "<tr>
						<td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
						<td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
					</tr>";
            }
            ?>
    </table></td>
    <td valign="top">
    <table width="100%">
      <tr>
        <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">DESPACHOS</div></td>
      </tr>
      <?php
            $sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='10' ORDER BY nombre ASC";
            $consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
            while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
            {
                $cont++;
                $nombre=$fila_privilegios['nombre'];
                $id_objeto=$fila_privilegios['id'];
				//Consulta por objetos privilegiados para el usuario
				$sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";
				$consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
				$nfilas=mysql_num_rows($consulta_objetos);
				if($nfilas > 0)
					$seleccion="checked=\"checked\"";
				else
					$seleccion="";
				
                echo "<tr>
						<td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
						<td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
					</tr>";
            }
            ?>
    </table></td>
  </tr>
  <tr>
    <td valign="top">
    <table width="100%">
      <tr>
        <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">REPORTES</div></td>
      </tr>
      <?php
            $sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='12' ORDER BY nombre ASC";
            $consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
            while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
            {
                $cont++;
                $nombre=$fila_privilegios['nombre'];
                $id_objeto=$fila_privilegios['id'];
				//Consulta por objetos privilegiados para el usuario
				$sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";
				$consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
				$nfilas=mysql_num_rows($consulta_objetos);
				if($nfilas > 0)
					$seleccion="checked=\"checked\"";
				else
					$seleccion="";
				
                echo "<tr>
						<td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
						<td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
					</tr>";
            }
            ?>
    </table></td>
    <td valign="top">
    <table width="100%">
      <tr>
        <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">CUMPLIDOS</div></td>
      </tr>
      <?php
            $sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='13' ORDER BY nombre ASC";
            $consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
            while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
            {
                $cont++;
                $nombre=$fila_privilegios['nombre'];
                $id_objeto=$fila_privilegios['id'];
				//Consulta por objetos privilegiados para el usuario
				$sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";
				$consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
				$nfilas=mysql_num_rows($consulta_objetos);
				if($nfilas > 0)
					$seleccion="checked=\"checked\"";
				else
					$seleccion="";
				
                echo "<tr>
						<td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
						<td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
					</tr>";
            }
            ?>
    </table></td>
  </tr>
  <tr>
    <td valign="top">
    <table width="100%">
      <tr>
        <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">BODEGA</div></td>
      </tr>
      <?php
            $sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='16' ORDER BY nombre ASC";
            $consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
            while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
            {
                $cont++;
                $nombre=$fila_privilegios['nombre'];
                $id_objeto=$fila_privilegios['id'];
				//Consulta por objetos privilegiados para el usuario
				$sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";
				$consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
				$nfilas=mysql_num_rows($consulta_objetos);
				if($nfilas > 0)
					$seleccion="checked=\"checked\"";
				else
					$seleccion="";
				
                echo "<tr>
						<td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
						<td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
					</tr>";
            }
            ?>
    </table></td>
    <td valign="top">
    <table width="100%">
      <tr>
        <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">LIBERACIONES</div></td>
      </tr>
      <?php
                $sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='14' ORDER BY nombre ASC";
                $consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
                while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
                {
                    $cont++;
                    $nombre=$fila_privilegios['nombre'];
                    $id_objeto=$fila_privilegios['id'];
                    //Consulta por objetos privilegiados para el usuario
                    $sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";
                    $consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
                    $nfilas=mysql_num_rows($consulta_objetos);
                    if($nfilas > 0)
                        $seleccion="checked=\"checked\"";
                    else
                        $seleccion="";
                    
                    echo "<tr>
                            <td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
                            <td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
                        </tr>";
                }
                ?>
    </table></td>
  </tr>
  <tr>
    <td valign="top">
    <table width="100%">
      <tr>
        <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">CORRECCIONES</div></td>
      </tr>
      <?php
                $sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='17' ORDER BY nombre ASC";
                $consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
                while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
                {
                    $cont++;
                    $nombre=$fila_privilegios['nombre'];
                    $id_objeto=$fila_privilegios['id'];
                    //Consulta por objetos privilegiados para el usuario
                    $sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";
                    $consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
                    $nfilas=mysql_num_rows($consulta_objetos);
                    if($nfilas > 0)
                        $seleccion="checked=\"checked\"";
                    else
                        $seleccion="";
                    
                    echo "<tr>
                            <td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
                            <td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
                        </tr>";
                }
                ?>
    </table></td>
    <td valign="top">
    <table width="100%">
      <tr>
        <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">REGISTRO FOTOGRAFICO</div></td>
      </tr>
      <?php
			$sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='18' ORDER BY nombre ASC";
			$consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
			while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
			{
				$cont++;
				$nombre=$fila_privilegios['nombre'];
				$id_objeto=$fila_privilegios['id'];
				//Consulta por objetos privilegiados para el usuario
				$sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";
				$consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
				$nfilas=mysql_num_rows($consulta_objetos);
				if($nfilas > 0)
					$seleccion="checked=\"checked\"";
				else
					$seleccion="";
				
				echo "<tr>
						<td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
						<td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
					</tr>";
			}
      ?>
    </table></td>
  </tr>
  <tr>
    <td valign="top">
        <table width="100%">
          <tr>
            <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">PRE-INSPECCIONES</div></td>
          </tr>
          <?php
				$sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='15' ORDER BY nombre ASC";
				$consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
				while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
				{
					$cont++;
					$nombre=$fila_privilegios['nombre'];
					$id_objeto=$fila_privilegios['id'];
					//Consulta por objetos privilegiados para el usuario
					$sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";
					$consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
					$nfilas=mysql_num_rows($consulta_objetos);
					if($nfilas > 0)
						$seleccion="checked=\"checked\"";
					else
						$seleccion="";
					
					echo "<tr>
							<td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
							<td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
						</tr>";
				}
          ?>
        </table>
    </td>
    <td valign="top">
        <table width="100%">
          <tr>
            <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">SEGURIDAD</div></td>
          </tr>
         	<?php
				$sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='19' ORDER BY nombre ASC";
				$consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
				while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
				{
					$cont++;
					$nombre=$fila_privilegios['nombre'];
					$id_objeto=$fila_privilegios['id'];
					//Consulta por objetos privilegiados para el usuario
					$sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";
					$consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
					$nfilas=mysql_num_rows($consulta_objetos);
					if($nfilas > 0)
						$seleccion="checked=\"checked\"";
					else
						$seleccion="";
					
					echo "<tr>
							<td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
							<td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
						</tr>";
				}
            ?>
        </table>
    </td>
  </tr>
  <!-- FILA -->
  <tr>
    <td width="50%" valign="top">
      <table width="100%">
        <tr>
          <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">COURIER</div></td>
        </tr>
          <?php
                $sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='20' ORDER BY nombre ASC";
                $consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
                while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
                {
                    $cont++;
                    $nombre=$fila_privilegios['nombre'];
                    $id_objeto=$fila_privilegios['id'];
                    //Consulta por objetos privilegiados para el usuario
                    $sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";        
                    $consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
                    $nfilas=mysql_num_rows($consulta_objetos);
                    if($nfilas > 0)
                      $seleccion="checked=\"checked\"";
                    else
                      $seleccion="";

                    echo "<tr>
                            <td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
                            <td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
                          </tr>";
                }
          ?>
      </table>
    </td>
    <td width="50%" valign="top">
      <table width="100%">
        <tr>
          <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">TURNOS COURIER</div></td>
        </tr>
          <?php
                $sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='21' ORDER BY nombre ASC";
                $consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
                while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
                {
                    $cont++;
                    $nombre=$fila_privilegios['nombre'];
                    $id_objeto=$fila_privilegios['id'];
                    //Consulta por objetos privilegiados para el usuario
                    $sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";        
                    $consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
                    $nfilas=mysql_num_rows($consulta_objetos);
                    if($nfilas > 0)
                      $seleccion="checked=\"checked\"";
                    else
                      $seleccion="";

                    echo "<tr>
                            <td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
                            <td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
                          </tr>";
                }
          ?>
      </table>      
    </td>
  </tr>  
  <tr>
    <td valign="top">
      <table width="100%">
        <tr>
          <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">SISTEMA DE PALLETS</div></td>
        </tr>
          <?php
                $sql_privilegios="SELECT id,nombre FROM objeto WHERE estado = 'A' AND seccion='22' ORDER BY nombre ASC";
                $consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
                while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
                {
                    $cont++;
                    $nombre=$fila_privilegios['nombre'];
                    $id_objeto=$fila_privilegios['id'];
                    //Consulta por objetos privilegiados para el usuario
                    $sql_objetos="SELECT * FROM privilegio WHERE id_usuario='$id_usuario' AND id_objeto='$id_objeto'";        
                    $consulta_objetos=mysql_query ($sql_objetos,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
                    $nfilas=mysql_num_rows($consulta_objetos);
                    if($nfilas > 0)
                      $seleccion="checked=\"checked\"";
                    else
                      $seleccion="";

                    echo "<tr>
                            <td width=\"95%\" class=\"celda_tabla_principal celda_boton\">$nombre</td>
                            <td width=\"5%\" class=\"celda_tabla_principal celda_boton\"><input type=\"checkbox\" name=\"$cont\" value=\"$id_objeto\" $seleccion/></td>
                          </tr>";
                }
          ?>
      </table>            
    </td>
    <td valign="top">&nbsp;</td>
  </tr>

  <tr>
    <td valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
</table>
