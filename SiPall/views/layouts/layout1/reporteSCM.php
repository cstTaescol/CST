<p class="titulo_tab_principal">Reporte por Aerolinea</p>
<p class="asterisco" align="center">SCM</p>

<table align="center">
  <!-- Aerolinea -->
  <tr>
    <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
    <td class="celda_tabla_principal celda_boton">       	
        <select name="id_aerolinea" id="id_aerolinea" tabindex="1" class="form-control" required="true" onchange="listPallet(this.value)">
          	  <option value="" selected="true">Seleccione una</option>
	              <?php while ($fila = $aerolinea->fetch_object()): ?>
	 				        <option value="<?=$fila->id?>"><?=$fila->nombre?></option>               
	              <?php endwhile; ?>
        </select>    
    </td>        
  </tr>     
</table>

<div id="contenidoPallets" align="center">
<div class="container">
  <h2>Informe SCM</h2>           
  <!-- Raspuesta Asyc-Adicion Pallets -->
  <div id="respuesta_listPallet" style="background-color: #ffffff;" align="left">
    <table class="table table-hover">
      <thead class="celda_tabla_principal">
        <tr>
          <th><div class="letreros_tabla">Seleccione una aerolínea</div></th>          
        </tr>
      </thead>
    </table>
  </div>
  <!-- Fin Raspuesta Asyc-Adicion Pallets -->
</div>              
</div>



<script language="javascript">
function listPallet(key){    
  $.get("<?=base_url?>controllers/AsynController.php",{action:"crearSCM", id:key},datosProcesados_listPallet);             
}

function datosProcesados_listPallet(datos_devueltos){
    var respuesta = datos_devueltos.split("|*|");
    switch(respuesta[0]){
      case "error":
        $("#respuesta_listPallet").html(respuesta[1]);                
      break;

      default:        
 		     $("#respuesta_listPallet").html(datos_devueltos);
      break;      
    }      
}
</script>