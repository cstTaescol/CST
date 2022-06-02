<p class="titulo_tab_principal">Informacion</p>
<p class="asterisco" align="center">Vuelo</p>
<table align="center">
  <tr>
    <td align="center" class="celda_tabla_principal">
      <div class="letreros_tabla">Tipo </div>
      <font class="asterisco" size="+3"><i><?=$estado->nombre?></i></font>
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Exportar</div><br>
      <button onclick="document.location='<?=base_url?>/excelReports/reporteVuelo.php?id=<?=$id_vuelo?>'" <?=$privilegioRepVuelo?>>
          <img src="<?=base_Father?>imagenes/excel.jpg" width="33" title="Exportar el informe a Excel" />
      </button>     
    </td>    
</table>
<br>
<table align="center">
  <tr >
    <td width="180" class="celda_tabla_principal"><div class="letreros_tabla">Número</div></td>
    <td class="celda_tabla_principal celda_boton"><?=$vuelo->no_vuelo?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolínea</div></td>
    <td class="celda_tabla_principal celda_boton"><?=$aerolinea->nombre?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Matrícula</div></td>
    <td class="celda_tabla_principal celda_boton"><?=$vuelo->matricula?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha del Vuelo</div></td>
    <td class="celda_tabla_principal celda_boton"><?=$vuelo->fecha_vuelo?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha de Creación</div></td>
    <td class="celda_tabla_principal celda_boton"><?=$vuelo->fechahora_creacion?></td>
  </tr>
</table>
<br>
<p class="asterisco" align="center">Pallets del Vuelo</p>
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Número Pallet</div></td>    
    <td class="celda_tabla_principal"><div class="letreros_tabla">En Buen Estado?</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Contenedor Activo?</div></td>
  </tr>
  <?=$buffer?>
</table>
<br>
<p class="asterisco" align="center">Artículos del Vuelo</p>
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Elemento</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Buenos</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Malos</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Total</div></td>    
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Mallas</div></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->mallas_bueno?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->mallas_malo?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$mayasTotal?></td>   
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Correas</div></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->correas_bueno?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->correas_malo?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$correasTotal?></td>   
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Plastico Base</div></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->plasticobase_bueno?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->plasticobase_malo?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$plasticobaseTotal?></td>   
  </tr>  
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Plastico Capuchón</div></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->plasticocapuchon_bueno?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->plasticocapuchon_malo?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$plasticocapuchonTotal?></td>   
  </tr> 
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Pernos</div></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->pernos_bueno?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->pernos_malo?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$pernosTotal?></td>   
  </tr> 
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Vinipel</div></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->vinipel_bueno?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->vinipel_malo?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vinipelTotal?></td>   
  </tr> 
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Lazos</div></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->lazos_bueno?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->lazos_malo?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$lazosTotal?></td>   
  </tr> 
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Conectores</div></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->conectores_bueno?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->conectores_malo?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$conectoresTotal?></td>   
  </tr> 
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Maderos</div></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->maderos_bueno?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->maderos_malo?></td>
    <td align="right" class="celda_tabla_principal celda_boton"><?=$maderosTotal?></td>   
  </tr>      
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Otros</div></td>
    <td colspan="3" align="right" class="celda_tabla_principal celda_boton"><?=$vuelo->otros_cuales?></td>    
  </tr>                     
</table>



