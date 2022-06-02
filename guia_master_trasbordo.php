<?php require("config/control_tiempo.php"); ?>
<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center" style="background-color:#696">
<form name="registro_guia" action="guia_salvar.php" method="post" onsubmit="return validar();">
  <tr>
    <td><br />
      <table width="90%" border="1" cellspacing="0" cellpadding="0" align="center" style="background:#FFF">
        <tr>
          <td width="40%"><font color="#FF0000"><strong>(*)</strong></font> ADMINISTRACION ADUANERA</td>
          <td width="60%"><select name="admon_aduana" id="admon_aduana" tabindex="1">
            <option value="3">ADUANAS DE BOGOT&Aacute;</option>
          </select></td>
        </tr>
        <tr>
          <td><font color="#FF0000"><strong>(*)</strong></font> DISPOSICION DE CARGA</td>
          <td><p>
            <select name="disposicion" id="disposicion" tabindex="2" onchange="showTipoDeposito(this.value)">
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
            </p></td>
        </tr>
        <tr>
          <td><font color="#FF0000"><strong>(*)</strong></font> GUIA </td>
          <td><input name="guia" id="guia" type="text" tabindex="3" size="20" maxlength="20" onkeyup="coincidencia_guia(this.value,1)"/>
            <span id="txtguia"></span></td>
        </tr>
        <tr>
          <td><font color="#FF0000"><strong>(*)</strong></font> CANTIDAD DE HIJAS</td>
          <td><input name="cantidad_hijas" id="cantidad_hijas" type="text" tabindex="4" size="10" maxlength="10" onkeypress="return numeric(event)"/></td>
        </tr>
        <tr>
          <td><font color="#FF0000"><strong>(*)</strong></font> EMBARCADOR</td>
          <td><div id="dv_embarcador">
            <p>
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
              <input type="button" name="btn_embarcador" id="btn_embarcador" onclick="llamadasin('ajax_embarcador.php', 'dv_embarcador', 'embarcador')" value="R" />
              <a href="#" onclick="openPopup('embarcador_nuevo_popup.php','new','750','400','scrollbars=1',true)"><img src="imagenes/+.jpg" width="25" height="25" border="1" align="absmiddle" alt="Agregue un nuevo EMBARCADOR" /></a></p>
          </div></td>
        </tr>
        <tr>
          <td style="background:#696"><font color="#FF0000"><strong>(*)</strong></font> CONSIGNATARIO</td>
          <td><div id="dv_consignatario">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="25%" style="background:#696"><font size="-1">Buscar..........................</font></td>
                <td width="75%" style="background:#696">
                    	<input type="text" name="auto_consgnatario" id="auto_consgnatario" tabindex="6"/>
                        <input type="button" value="Buscar" onclick="showHint(auto_consgnatario.value)" tabindex="7" />
                  <img src="imagenes/lupa.jpg" alt="Buscar consignatario por coincidencia de Nombre" width="30" height="30" align="absmiddle" /></td>
                </tr>
              <tr>
                <td align="center"><button name="btn_consignatario" id="btn_consignatario" onclick="llamadasin('ajax_consignatario.php', 'dv_consignatario', 'consignatario')" tabindex="8"> <img src="imagenes/buscar.png" width="60" height="60" alt="Activar el selector" /> <br />
                  <strong>Seleccionar</strong> </button></td>
                <td style="background:#696"><span id="txtConsignatario"></span></td>
                </tr>
              <tr>
                <td colspan="2" style="background:#696">&nbsp;</td>
                </tr>
              </table>
            </div></td>
        </tr>
        <tr bgcolor="#CCFFFF">
          <td bgcolor="#FFFFFF"><font color="#FF0000"><strong>(*)</strong></font> DATOS DEL DEPOSITO</td>
          <td bgcolor="#FFFFFF"><div id="dv_tipo_deposito"></div>
            <div id="dv_evaluador"></div>
            <div id="dv_ciudad"></div>
            <div id="dv_deposito"></div>
            <input type="hidden" name="tipo_disposicion" id="tipo_disposicion" value=""/></td>
        </tr>
        <tr>
          <td><font color="#FF0000"><strong>(*) </strong></font>PIEZAS</td>
          <td><input name="piezas" id="digitado_piezas" type="text" size="5" maxlength="10" tabindex="9" onkeypress="return numeric(event)"/></td>
        </tr>
        <tr>
          <td><font color="#FF0000"><strong>(*)</strong></font> PESO</td>
          <td><input name="peso" type="text" size="5" maxlength="10" tabindex="10" onkeypress="return numeric2(event)"/></td>
        </tr>
        <tr>
          <td>VOLUMEN</td>
          <td><input name="volumen" type="text" size="5" maxlength="10" tabindex="11" onkeypress="return numeric2(event)"/></td>
        </tr>
        <tr>
          <td><font color="#FF0000"><strong>(*)</strong></font> DESCRIPCION</td>
          <td><input name="descripcion" type="text" id="descripcion" tabindex="12" size="50"/></td>
        </tr>
        <tr>
          <td><font color="#FF0000"><strong>(*) </strong></font>FECHA CORTE</td>
          <td><p>
            <input type="text" name="fecha_corte" id="fecha_corte" readonly="readonly"/>
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
            <p><img src="imagenes/advertencia.jpg" alt="" width="30" height="30" align="absmiddle" />Recuerde que la fecha no puede ser superior a la fecha actual </p></td>
        </tr>
        <tr>
          <td>PAIS DE ORIGEN</td>
          <td><select name="pais_origen" id="pais_origen" tabindex="14" onchange="showPais(this.value);">
            <option value="" selected="selected">Seleccione Uno</option>
            <?php
					$sql="SELECT codigo,nombre FROM pais WHERE estado='A' ORDER BY nombre";
					$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
					while($fila=mysql_fetch_array($consulta))
					{
						echo '<option value="'.$fila['codigo'].'">'.$fila['nombre'].'</option>';
					}
				?>
          </select>
            <font color="#FF0000"><strong>(*) </strong></font><br />
            <div id="dv_ciudad_origen"><font color="white">CIUDAD</font></div></td>
        </tr>
        <tr>
          <td><font color="#FF0000"><strong>(*) </strong></font>TIPO DE CARGA</td>
          <td><select name="tipo_carga" id="tipo_carga" tabindex="15">
            <option value="1" selected="selected">SUELTA</option>
            <?php
                            $sql="SELECT id,nombre FROM tipo_carga WHERE estado='A' ORDER BY nombre";
                            $consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
                            while($fila=mysql_fetch_array($consulta))
                            {
                                echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                            }
                        ?>
            </select></td>
        </tr>
        <tr>
          <td>AGENTE DE CARGA</td>
          <td><div id="dv_agente">
            <p>
              <select name="agente_carga" id="agente_carga" tabindex="16" >
                <option value="" selected="selected">Seleccione Uno</option>
                <?php
                        $sql="SELECT id,razon_social FROM agente_carga WHERE estado='A' ORDER BY razon_social";
                        $consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
                        while($fila=mysql_fetch_array($consulta))
                        {
                            echo '<option value="'.$fila['id'].'">'.substr($fila['razon_social'],0,35).'</option>';
                        }
                    ?>
              </select>
              <input type="button" name="btn_agente" id="btn_agente" onclick="llamadasin('ajax_agente_carga.php', 'dv_agente', 'agente')" value="R"/>
              <a href="#" onclick="openPopup('agente_carga_popup.php','new','750','400','scrollbars=1',true)"><img src="imagenes/+.jpg" width="25" height="25" border="1" align="absmiddle" alt="Agregue un nuevo AGENTE DE CARGA" /></a></p>
          </div></td>
        </tr>
        <tr>
          <td><font color="#FF0000"><strong>(*) </strong></font>PRECURSORES</td>
          <td>NO
            <input type="radio" name="precursor" value="N" checked="checked" tabindex="17" />
            SI
            <input type="radio" name="precursor" value="S"/></td>
          </tr>
        <tr>
          <td>FLETE</td>
          <td>$
            <input name="flete" type="text" size="15" maxlength="15" tabindex="18" onkeypress="return numeric2(event)"/></td>
        </tr>
        <tr>
          <td>OBSERVACION</td>
          <td><textarea name="observaciones" cols="40" rows="5" tabindex="19"></textarea></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="hidden" name="tipo" value="5" /></td>
        </tr>
        <tr>
          <td style="background:#696"><input type="reset" value="........ Limpiar ........" tabindex="21"/></td>
          <td style="background:#696"><input type="submit" value="........ Guardar ........" tabindex="20" /></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</form>
</table>
