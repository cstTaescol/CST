<p class="titulo_tab_principal">Reportes</p>
<p class="asterisco" align="center">Selector de Reportes</p>	
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">SCM</div></td>
    <td class="celda_tabla_principal celda_boton">Reporte la cantidad de pallet en Bodega de una aerolínea en específico</td>
    <td class="celda_tabla_principal celda_boton">
            <button id="btnBuscar" name="btnBuscar" type="button" onclick="document.location='<?=base_url?>Central/reporteSCM'" <?=$privilegioRepSCM?>>
                <img src="<?=base_Father?>imagenes/check_blue.png" title="Acceder" /><br />                 
            </button>
	</td>	
  </tr>  

  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">UCM</div></td>
    <td class="celda_tabla_principal celda_boton">Reporte creado a partir de un vuelo en un rango de fecha</td>
    <td class="celda_tabla_principal celda_boton">
            <button id="btnBuscar" name="btnBuscar" type="button" onclick="document.location='<?=base_url?>Central/reporteUCM'" <?=$privilegioRepUCM?>>
                <img src="<?=base_Father?>imagenes/check_blue.png" title="Acceder" /><br />	              	
            </button>
	</td>	
  </tr>

  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Vuelo</div></td>
    <td class="celda_tabla_principal celda_boton">Consulta a partir un número de vuelo</td>
    <td class="celda_tabla_principal celda_boton">
            <button id="btnBuscar" name="btnBuscar" type="button" onclick="document.location='<?=base_url?>Central/buscarVuelo'" <?=$privilegioBuscarVuelo?>>
                <img src="<?=base_Father?>imagenes/check_blue.png" title="Acceder" /><br />	              	
            </button>
	 </td>	
  </tr>

  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Pallets</div></td>
    <td class="celda_tabla_principal celda_boton">Reporte de Pallets en un rango de fecha</td>
    <td class="celda_tabla_principal celda_boton">
            <button id="btnBuscar" name="btnBuscar" type="button" onclick="document.location='<?=base_url?>Central/reportePallets'" <?=$privilegioReportePallets?>>
                <img src="<?=base_Father?>imagenes/check_blue.png" title="Acceder" /><br />                 
            </button>
   </td>  
  </tr>

  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Inventario</div></td>
    <td class="celda_tabla_principal celda_boton">Reporte de Pallets en el Inventario</td>
    <td class="celda_tabla_principal celda_boton">
            <button id="btnBuscar" name="btnBuscar" type="button" onclick="document.location='<?=base_url?>excelReports/reporteInventario.php'" <?=$privilegioReporteInventario?>>
                <img src="<?=base_Father?>imagenes/check_blue.png" title="Acceder" /><br />                 
            </button>
   </td>  
  </tr>

</table>

