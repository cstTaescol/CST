<form name="registro_guia" action="guia_salvar.php" method="post" onsubmit="return validar();">
<table width="90%" align="center" class="celda_tabla_principal">
  <tr>
    <td>
      <table width="90%" align="center">
        <tr>
          <td colspan="2" class="asterisco" align="center">Guia Consolidada</td>
        </tr>
        <tr>
          <td width="40%" class="celda_tabla_principal"><div class="letreros_tabla">No Guia</div> 
            <input name="cod_consignatario" type="hidden" id="cod_consignatario"/>
            <input name="cod_embarcador" type="hidden" id="cod_embarcador" />
            <input type="hidden" name="tipo" value="2" />
          </td>
          <td width="60%" class="celda_tabla_principal celda_boton"><font color="#FF0000"><strong>(*)</strong></font><input name="guia" id="guia" type="text" tabindex="3" size="20" maxlength="20" onkeyup="coincidencia_guia(this.value,1)"/></td>
        </tr>
        <tr>
          <td class="celda_tabla_principal"><div class="letreros_tabla">Embarcador</div></td>
          <td class="celda_tabla_principal celda_boton"><div id="dv_embarcador">
              <p><font color="#FF0000"><strong>(*)</strong></font>
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
                <button type="button" name="btn__reload_embarcador" id="btn__reload_embarcador" onclick="llamadasin('ajax_embarcador.php', 'dv_embarcador', 'embarcador')"> <img src="imagenes/recargar-act.png" alt="" title="Recargar" align="absmiddle"/></button>
                <button type="button" name="btn_embarcador" id="btn_embarcador" onclick="openPopup('embarcador_nuevo_popup.php','new','750','400','scrollbars=1',true)"> <img src="imagenes/agregar-act.png" alt="" title="Agregar" align="absmiddle"/></button>
              </p>
            </div>
            </td>
        </tr>
        <tr>
          <td  class="celda_tabla_principal"><div class="letreros_tabla">Consignatario</div></td>
          <td>
              <div id="dv_consignatario">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="25%" class="celda_tabla_principal"><font size="-1">Buscar..........................</font></td>
                    <td width="75%" class="celda_tabla_principal"><font color="#FF0000"><strong>(*)</strong></font>
                      <input type="text" name="auto_consgnatario" id="auto_consgnatario" tabindex="6"/>
                    <button type="button" onclick="showHint(auto_consgnatario.value)" tabindex="7"> <img src="imagenes/buscar-act.png" alt="" title="Buscar" align="absmiddle"/></button></td>
                    </tr>
                  <tr>
                    <td class="celda_tabla_principal" align="center"><button name="btn_consignatario" id="btn_consignatario" onclick="llamadasin('ajax_consignatario.php', 'dv_consignatario','consignatario')" tabindex="7"> <img src="imagenes/buscar.png" width="60" height="60" alt="Activar el selector" /> <br />
                      <strong>Seleccionar</strong> </button></td>
                    <td class="celda_tabla_principal celda_boton"><span id="txtConsignatario"></span></td>
                    </tr>
                  <tr>
                    <td colspan="2"  class="celda_tabla_principal celda_boton">&nbsp;</td>
                    </tr>
                  </table>
              </div>
          </td>
        </tr>
        <tr>
          <td class="celda_tabla_principal"><div class="letreros_tabla">Descripcion</div></td>
          <td class="celda_tabla_principal celda_boton"><font color="#FF0000"><strong>(*)</strong></font><input name="descripcion" type="text" id="descripcion" size="40" value="CONSOLIDADO" tabindex="11" onKeyUp="acentos(this.value,this.id)"/></td>
        </tr>
        <tr>
          <td class="celda_tabla_principal"><div class="letreros_tabla">Observacion</div></td>
          <td class="celda_tabla_principal celda_boton"><textarea name="observaciones" cols="40" rows="5" tabindex="14"></textarea></td>
        </tr>
      </table>
      </td>
  </tr>
</table>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton"><button type="reset" name="reset" id="reset"> <img src="imagenes/descargar-act.png" alt="" title="Limpiar" align="absmiddle"/></button>
        <button type="submit" name="guardar" id="guardar"> <img src="imagenes/guardar-act.png" alt="" title="Guardar" align="absmiddle"/> </button></td>
    </tr>
</table>

</form>


