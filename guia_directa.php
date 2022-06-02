<form name="registro_guia" action="guia_salvar.php" method="post" onsubmit="return validar();">
<table width="90%" align="center" class="celda_tabla_principal">
    <td>
    <table width="90%" align="center">
          <tr>
            <td class="asterisco" colspan="2" align="center">Guia Directa</td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Administracion Aduanera</div></td>
            <td class="celda_tabla_principal celda_boton">
            	<font color="#FF0000"><strong>(*)</strong></font>
                <select name="admon_aduana" id="admon_aduana" tabindex="1">
                  <option value="3">ADUANAS DE BOGOT&Aacute;</option>
                </select>
            </td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Disposicion de Carga</div></td>
            <td class="celda_tabla_principal celda_boton"><p>
              <font color="#FF0000"><strong>(*)</strong></font>
              <select name="disposicion" id="disposicion" tabindex="3" onchange="showTipoDeposito(this.value)">
                <option value="">Seleccione Uno</option>
                <?php
						$sql="SELECT id,nombre FROM disposicion_cargue WHERE estado='A' ORDER BY nombre";
						$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
						while($fila=mysql_fetch_array($consulta))
						{
							echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
						}
					?>
              </select>
              <script>document.getElementById("disposicion").focus();</script>
              <input name="cod_disposicion" type="hidden" id="cod_disposicion" />
              <input name="cod_embarcador" type="hidden" id="cod_embarcador" />
              <input name="cod_consignatario" type="hidden" id="cod_consignatario" />
              <input name="cod_deposito" type="hidden" id="cod_deposito" />
              <input name="cod_cabotaje" type="hidden" id="cod_cabotaje" />
              <input name="cod_departamento_destino" type="hidden" id="cod_departamento_destino"  value="11" />
              <input name="cod_ciudad_destino" type="hidden" id="cod_ciudad_destino" value="11001" />
              <input name="cod_ciudad_embarcadora" type="hidden" id="cod_ciudad_embarcadora" />
              <input name="asignacion_directa" type="hidden" id="asignacion_directa" value="S" />
            </p>
            </td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">No Guia</div></td>
            <td class="celda_tabla_principal celda_boton">
            	<font color="#FF0000"><strong>(*)</strong></font>
            	<input name="guia" id="guia" type="text" tabindex="4" size="20" maxlength="20" onkeyup="coincidencia_guia(this.value,1)" onfocus="extender_sesion()"/>            	<span id="txtguia"></span>
           </td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Embarcador</div></td>
            <td class="celda_tabla_principal celda_boton" width="60%"><div id="dv_embarcador">
                <font color="#FF0000"><strong>(*)</strong></font>
                <select name="embarcador" id="embarcador" tabindex="5" onfocus="pasar_embarcador(this.value);" onchange="pasar_embarcador(this.value);">
                  <option value="" selected="selected">Seleccione Uno</option>
                  <?php
                            $sql="SELECT id,nombre FROM embarcador WHERE estado='A' ORDER BY nombre";
                            $consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
                            while($fila=mysql_fetch_array($consulta))
                            {
                                echo '<option value="'.$fila['id'].'">'.substr($fila['nombre'],0,35).'</option>';
                            }
                        ?>
                </select>
                
                <button type="button" name="btn__reload_embarcador" id="btn__reload_embarcador" onclick="llamadasin('ajax_embarcador.php', 'dv_embarcador', 'embarcador')">
                    <img src="imagenes/recargar-act.png" title="Recargar" align="absmiddle"/>
                </button>
                <button type="button" name="btn_embarcador" id="btn_embarcador" onClick="openPopup('embarcador_nuevo_popup.php','new','750','400','scrollbars=1',true)">
                    <img src="imagenes/agregar-act.png" title="Agregar" align="absmiddle"/>
                </button>
            </div>
            </td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Consignatario</div></td>
            <td class="celda_tabla_principal celda_boton">
            <div id="dv_consignatario">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="25%"  class="celda_tabla_principal"><font size="-1">Buscar..........................</font></td>
                    <td width="75%"  class="celda_tabla_principal">
                    	<font color="#FF0000"><strong>(*)</strong></font>
                        <input type="text" name="auto_consgnatario" id="auto_consgnatario" tabindex="6"/>
                        <button type="button" onclick="showHint(auto_consgnatario.value)" tabindex="7">
                        	<img src="imagenes/buscar-act.png" title="Buscar" align="absmiddle"/>
                     	</button>
                     </td>
                  </tr>
                  <tr>
                    <td class="celda_tabla_principal" align="center">
                   	  	<button name="btn_consignatario" id="btn_consignatario" onclick="llamadasin('ajax_consignatario.php', 'dv_consignatario', 'consignatario')" tabindex="8">
                        	<img src="imagenes/buscar.png" width="60" height="60" alt="Activar el selector" />
                        	<br />
                        	<strong>Seleccionar</strong>
                        </button>
                     </td>
                    <td class="celda_tabla_principal celda_boton"><span id="txtConsignatario"></span></td>
                  </tr>
                  <tr>
                  	<td colspan="2" class="celda_tabla_principal celda_boton" >&nbsp;</td>
                  </tr>
                </table>
            </div>
            </td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Datos del Deposito</div></td>
            <td class="celda_tabla_principal celda_boton">
               	<div id="dv_tipo_deposito"></div>
                <div id="dv_evaluador"></div>
                <div id="dv_ciudad"></div>
            	<div id="dv_deposito"></div>
            	<input type="hidden" name="tipo_disposicion" id="tipo_disposicion" value=""/>
            </td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
            <td class="celda_tabla_principal celda_boton"><font color="#FF0000"><strong>(*)</strong></font><input name="piezas" id="digitado_piezas" type="text" size="5" maxlength="10" tabindex="9" onKeyPress="return numeric(event)"/></td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
            <td class="celda_tabla_principal celda_boton"><font color="#FF0000"><strong>(*)</strong></font><input name="peso" type="text" size="5" maxlength="10" tabindex="10" onKeyup="numeric2(this)" onblur="numeric2(this)"/></td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
            <td class="celda_tabla_principal celda_boton"><font color="#FF0000"><strong>(*)</strong></font><input name="volumen" type="text" size="5" maxlength="10" tabindex="11" onKeyup="numeric2(this)" onblur="numeric2(this)"/></td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Descripcion</div></td>
            <td class="celda_tabla_principal celda_boton"><font color="#FF0000"><strong>(*)</strong></font><input name="descripcion" type="text" id="descripcion" tabindex="12" size="50" onKeyUp="acentos(this.value,this.id)"/></td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha de corte</div></td>
            <td class="celda_tabla_principal celda_boton"><p>
              <font color="#FF0000"><strong>(*)</strong></font>
              <input type="text" name="fecha_corte" id="fecha_corte" readonly/>
              <input type="button" id="lanzador" value="..." tabindex="13"/>
              <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
              <!-- script que define y configura el calendario-->
              <script type="text/javascript">
                    Calendar.setup({
                        inputField     :    "fecha_corte",      // id del campo de texto
                        ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                        button         :    "lanzador"   // el id del botón que lanzará el calendario
                    });
                </script>
            </p>
              <p><img src="imagenes/alerta-act.png" alt="" width="33" height="29" align="absmiddle" /> Recuerde que la fecha no puede ser superior a la fecha actual </p></td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Tipo de Carga</div></td>
            <td class="celda_tabla_principal celda_boton">
                <font color="#FF0000"><strong>(*)</strong></font>
                <select name="tipo_carga" id="tipo_carga" tabindex="14">
                  <option value="1" selected="selected">SUELTA</option>
                  <?php
                            $sql="SELECT id,nombre FROM tipo_carga WHERE estado='A' ORDER BY nombre";
                            $consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
                            while($fila=mysql_fetch_array($consulta))
                            {
                                echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                            }
                        ?>
                </select>
            </td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Requiere Cuarto Fr&iacute;o</div></td>
            <td class="celda_tabla_principal celda_boton">
               	NO <input type="radio" name="cuarto_frio" value="N" checked="checked" tabindex="15" />
              	SI <input type="radio" name="cuarto_frio" value="S" tabindex="16"/>
                <img src="imagenes/ice.fw.png" width="33" height="29"  align="absmiddle" />
            </td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Precursores</div></td>
            <td class="celda_tabla_principal celda_boton">
            	NO <input type="radio" name="precursor" value="N" checked="checked" tabindex="15" />
              	SI <input type="radio" name="precursor" value="S" tabindex="16"/>
            </td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Agente de Carga</div></td>
            <td class="celda_tabla_principal celda_boton">
            <div id="dv_agente">
              <p>
                <font color="#FF0000"><strong>(*)</strong></font>
                <select name="agente_carga" id="agente_carga" tabindex="17" >
                  <option value="" selected="selected">Seleccione Uno</option>
                  <?php
                        $sql="SELECT id,razon_social FROM agente_carga WHERE estado='A' ORDER BY razon_social";
                        $consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
                        while($fila=mysql_fetch_array($consulta))
                        {
                            echo '<option value="'.$fila['id'].'">'.$fila['razon_social'].'</option>';
                        }
                    ?>
                </select>
                <button type="button" name="btn__reload_acarga" id="btn__reload_acarga" onclick="llamadasin('ajax_agente_carga.php', 'dv_agente', 'agente')">
                    <img src="imagenes/recargar-act.png" title="Recargar" align="absmiddle"/>
                </button>
                <button type="button" name="btn_acarga" id="btn_acarga" onClick="openPopup('agente_carga_popup.php','new','750','400','scrollbars=1',true)">
                    <img src="imagenes/agregar-act.png" title="Agregar" align="absmiddle"/>
                </button>
            </div>
            </td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Flete</div></td>
            <td class="celda_tabla_principal celda_boton">$
              <input name="flete" type="text" size="15" maxlength="15" tabindex="18" onKeyPress="return numeric2(event)"/></td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
            <td class="celda_tabla_principal celda_boton"><textarea name="observaciones" cols="40" rows="5" tabindex="19"></textarea></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="hidden" name="tipo" value="1" /></td>
          </tr>
            <table width="450" align="center">
                <tr>
                  <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
                </tr>
                <tr>
                  <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
                    <button type="reset" name="reset" id="reset">
                        <img src="imagenes/descargar-act.png" title="Limpiar" />
                    </button>
                    <button type="submit" name="guardar" id="guardar">
                        <img src="imagenes/guardar-act.png" title="Guardar" />
                    </button>
                  </td>
                </tr>
             </table>    
    <p>&nbsp;</p>
  </tr>
</table>
</form>
<script language="javascript">
function extender_sesion()
{
	tiempo_sesion = 100000;
}
</script>