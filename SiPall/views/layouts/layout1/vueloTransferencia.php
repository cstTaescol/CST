<p class="titulo_tab_principal">Transferencia de Pallets</p>
<p class="asterisco" align="center">Transferir</p>
<form name="formulario_guardar" id="formulario_guardar" method="POST" action="<?=base_url?>controllers/AsynController.php" class="was-validated">
  <input type="hidden" name="action" id="action" value="vueloTransferencia">
  <table align="center">
      <!-- Aerolinea -->
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea Origen</div></td>
        <td class="celda_tabla_principal celda_boton">       	
            <select name="id_aerolinea" id="id_aerolinea" tabindex="1" class="form-control" required="true" onchange="listPallet(this.value)">
              	  <option value="" selected="true">Seleccione una</option>
  	              <?php while ($fila = $aerolinea->fetch_object()): ?>
  	 				        <option value="<?=$fila->id?>"><?=$fila->nombre?></option>               
  	              <?php endwhile; ?>
            </select>    
        </td>        
      </tr>
      <!-- AerolineaDestino -->
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea Destino</div></td>
        <td class="celda_tabla_principal celda_boton">        
            <select name="id_aerolineaDestino" id="id_aerolineaDestino" class="form-control" required="true">
                  <option value="" selected="true">Seleccione una</option>
                  <?php while ($fila = $aerolineaDestino->fetch_object()): ?>
                    <option value="<?=$fila->id?>"><?=$fila->nombre?></option>               
                  <?php endwhile; ?>
            </select>    
        </td>        
      </tr>      
      <!-- Fecha del Vuelo -->
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Fecha Transferencia</div></td>        
        <td class="celda_tabla_principal celda_boton">
          <div class="form-group">   
            <label for="fecha_vuelo">Fecha de la Transferencia</label>
            <input id="fecha_vuelo" name="fecha_vuelo" type="date" class="form-control" required="true">                              
          </div> 
      </tr>         
  </table>

  <!-- Raspuesta Asyc-Adicion Selector de Maximos -->
  <div id="respuestaMaximos" align="center"></div>

  <div id="contenidoPallets" align="center">
    <div class="container">
      <h2>Pallets</h2>
      <p>Seleccione los Pallets que desea transferir <img src="<?=base_Father?>imagenes/trasbordo.jpg" width="21" height="22" border="0" align="absmiddle" /></p>            
      <!-- Raspuesta Asyc-Adicion Pallets -->
      <div id="respuesta_listPallet">
        <table class="table table-hover">
          <thead class="celda_tabla_principal">
            <tr>
              <th><div class="letreros_tabla">Seleccionar</div></th>
              <th><div class="letreros_tabla">Número de Pallet</div></th>
              <th><div class="letreros_tabla">Estado</div></th>
              <th><div class="letreros_tabla">Ubicación</div></th>             
            </tr>
          </thead>
        </table>
      </div>
      <!-- Fin Raspuesta Asyc-Adicion Pallets -->
    </div>              
  </div>

  <!--Datos Adicionales -->
  <div id="contenidoAdicionales" align="center">
    <div class="container">
      <table class="table table-hover">
                <thead class="celda_tabla_principal">
                  <tr>
                    <th><div class="letreros_tabla">Tipo de Elemento</div></th>
                    <th><div class="letreros_tabla">Disponibilidad de Elementos</div></th>
                    <th><div class="letreros_tabla">Cantidad de Elementos</div></th>
                  </tr>
                </thead>
                 <tbody>
                  <!-- Mallas -->
                  <tr>
                    <td class="celda_tabla_principal celda_boton">
                      <div class="celda_tabla_principal letreros_tabla">Mallas</div>
                    </td>   
                    <td class="celda_tabla_principal celda_boton">
                      <div class="form-group">   
                        <input id="mallas_no" name="mallas" type="radio" onclick="ocultarArea('numero_mallas')" value="0" checked="true">                            
                        <label for="mallas_no">No</label>                            
                      </div>   

                      <div class="form-group">   
                        <input id="mallas_si" name="mallas" type="radio" onclick="mostrarArea('numero_mallas')" value="1">                            
                        <label for="mallas_si">Si</label>                            
                      </div>                                                 
                    </td>    
                    <td class="celda_tabla_principal celda_boton">
                      <div id="numero_mallas" class="form-group" style="display: none;">   
                        <label for="mallas_bueno">Buen Estado</label>
                        <input id="mallas_bueno" name="mallas_bueno" type="number" class="form-control" min="0" maxlength="11" value="0" required="true" onkeypress="return numeric(event)">
                        <label for="mallas_malo">Mal Estado</label>
                        <input id="mallas_malo" name="mallas_malo" type="number" class="form-control" min="0" maxlength="11" value="0" required="true" onkeypress="return numeric(event)">           
                      </div>                                          
                    </td>   
                  </tr>                                           

                  <!-- Correas -->
                  <tr>
                    <td class="celda_tabla_principal celda_boton">
                      <div class="celda_tabla_principal letreros_tabla">Correas</div>
                    </td>   
                    <td class="celda_tabla_principal celda_boton">
                      <div class="form-group">   
                        <input id="correas_no" name="correas" type="radio" onclick="ocultarArea('numero_correas')" value="0" checked="true">                            
                        <label for="correas_no">No</label>                            
                      </div>   

                      <div class="form-group">   
                        <input id="correas_si" name="correas" type="radio" onclick="mostrarArea('numero_correas')" value="1">                            
                        <label for="correas_si">Si</label>                            
                      </div>                                                 
                    </td>    
                    <td class="celda_tabla_principal celda_boton">
                      <div id="numero_correas" class="form-group" style="display: none;">   
                        <label for="correas_bueno">Buen Estado</label>
                        <input id="correas_bueno" name="correas_bueno" type="number" class="form-control" min="0" maxlength="11" value="0" required="true" onkeypress="return numeric(event)">
                        <label for="correas_malo">Mal Estado</label>
                        <input id="correas_malo" name="correas_malo" type="number" class="form-control" min="0" maxlength="11" value="0" required="true" onkeypress="return numeric(event)">           
                      </div>                                          
                    </td>   
                  </tr>                                        

                  <!-- Plastico Base -->
                  <tr>
                    <td class="celda_tabla_principal celda_boton">
                      <div class="celda_tabla_principal letreros_tabla">Plastico Base</div>
                    </td>   
                    <td class="celda_tabla_principal celda_boton">
                      <div class="form-group">   
                        <input id="plasticobase_no" name="plasticobase" type="radio" onclick="ocultarArea('numero_plasticobase')" value="0" checked="true">                            
                        <label for="plasticobase_no">No</label>                            
                      </div>   

                      <div class="form-group">   
                        <input id="plasticobase_si" name="plasticobase" type="radio" onclick="mostrarArea('numero_plasticobase')" value="1">                            
                        <label for="plasticobase_si">Si</label>                            
                      </div>                                                 
                    </td>    
                    <td class="celda_tabla_principal celda_boton">
                      <div id="numero_plasticobase" class="form-group" style="display: none;">   
                        <label for="plasticobase_bueno">Buenos</label>
                        <input id="plasticobase_bueno" name="plasticobase_bueno" type="number" class="form-control" min="0" maxlength="11" value="0" required="true" onkeypress="return numeric(event)">
                        <label for="plasticobase_malo">Malos</label>
                        <input id="plasticobase_malo" name="plasticobase_malo" type="number" class="form-control" min="0" maxlength="11" value="0" required="true" onkeypress="return numeric(event)">           
                      </div>                                          
                    </td>   
                  </tr> 

                  <!-- Plastico Capuchon -->
                  <tr>
                    <td class="celda_tabla_principal celda_boton">
                      <div class="celda_tabla_principal letreros_tabla">Plastico Capuchon</div>
                    </td>   
                    <td class="celda_tabla_principal celda_boton">
                      <div class="form-group">   
                        <input id="plasticocapuchon_no" name="plasticocapuchon" type="radio" onclick="ocultarArea('numero_plasticocapuchon')" value="0" checked="true">                            
                        <label for="plasticocapuchon_no">No</label>                            
                      </div>   

                      <div class="form-group">   
                        <input id="plasticocapuchon_si" name="plasticocapuchon" type="radio" onclick="mostrarArea('numero_plasticocapuchon')" value="1">                            
                        <label for="plasticocapuchon_si">Si</label>                            
                      </div>                                                 
                    </td>    
                    <td class="celda_tabla_principal celda_boton">
                      <div id="numero_plasticocapuchon" class="form-group" style="display: none;">   
                        <label for="plasticocapuchon_bueno">Buen Estado</label>
                        <input id="plasticocapuchon_bueno" name="plasticocapuchon_bueno" type="number" class="form-control" min="0" maxlength="11" value="0" required="true" onkeypress="return numeric(event)">
                        <label for="plasticocapuchon_malo">Mal Estado</label>
                        <input id="plasticocapuchon_malo" name="plasticocapuchon_malo" type="number" class="form-control" min="0" maxlength="11" value="0" required="true" onkeypress="return numeric(event)">           
                      </div>                                          
                    </td>   
                  </tr> 

                  <!-- Pernos -->
                  <tr>
                    <td class="celda_tabla_principal celda_boton">
                      <div class="celda_tabla_principal letreros_tabla">Pernos</div>
                    </td>   
                    <td class="celda_tabla_principal celda_boton">
                      <div class="form-group">   
                        <input id="pernos_no" name="pernos" type="radio" onclick="ocultarArea('numero_pernos')" value="0" checked="true">                            
                        <label for="pernos_no">No</label>                            
                      </div>   

                      <div class="form-group">   
                        <input id="pernos_si" name="pernos" type="radio" onclick="mostrarArea('numero_pernos')" value="1">                            
                        <label for="pernos_si">Si</label>                            
                      </div>                                                 
                    </td>    
                    <td class="celda_tabla_principal celda_boton">
                      <div id="numero_pernos" class="form-group" style="display: none;">   
                        <label for="pernos_bueno">Buen Estado</label>
                        <input id="pernos_bueno" name="pernos_bueno" type="number" class="form-control" min="0" maxlength="11" value="0" required="true" onkeypress="return numeric(event)">
                        <label for="pernos_malo">Mal Estado</label>
                        <input id="pernos_malo" name="pernos_malo" type="number" class="form-control" min="0" maxlength="11" value="0" required="true" onkeypress="return numeric(event)">           
                      </div>                                          
                    </td>   
                  </tr> 

                  <!-- Vinipel -->
                  <tr>
                    <td class="celda_tabla_principal celda_boton">
                      <div class="celda_tabla_principal letreros_tabla">Vinipel</div>
                    </td>   
                    <td class="celda_tabla_principal celda_boton">
                      <div class="form-group">   
                        <input id="vinipel_no" name="vinipel" type="radio" onclick="ocultarArea('numero_vinipel')" value="0" checked="true">                            
                        <label for="vinipel_no">No</label>                            
                      </div>   

                      <div class="form-group">   
                        <input id="vinipel_si" name="vinipel" type="radio" onclick="mostrarArea('numero_vinipel')" value="1">                            
                        <label for="vinipel_si">Si</label>                            
                      </div>                                                 
                    </td>    
                    <td class="celda_tabla_principal celda_boton">
                      <div id="numero_vinipel" class="form-group" style="display: none;">   
                        <label for="vinipel_bueno">Buen Estado</label>
                        <input id="vinipel_bueno" name="vinipel_bueno" type="number" class="form-control" min="0" maxlength="11" value="0" required="true" onkeypress="return numeric(event)">
                        <label for="vinipel_malo">Mal Estado</label>
                        <input id="vinipel_malo" name="vinipel_malo" type="number" class="form-control" min="0" maxlength="11"value="0" required="true" onkeypress="return numeric(event)">           
                      </div>                                          
                    </td>   
                  </tr>

                  <!-- Lazos -->
                  <tr>
                    <td class="celda_tabla_principal celda_boton">
                      <div class="celda_tabla_principal letreros_tabla">Lazos</div>
                    </td>   
                    <td class="celda_tabla_principal celda_boton">
                      <div class="form-group">   
                        <input id="lazos_no" name="lazos" type="radio" onclick="ocultarArea('numero_lazos')" value="0" checked="true">                            
                        <label for="lazos_no">No</label>                            
                      </div>   

                      <div class="form-group">   
                        <input id="lazos_si" name="lazos" type="radio" onclick="mostrarArea('numero_lazos')" value="1">                            
                        <label for="lazos_si">Si</label>                            
                      </div>                                                 
                    </td>    
                    <td class="celda_tabla_principal celda_boton">
                      <div id="numero_lazos" class="form-group" style="display: none;">   
                        <label for="lazos_bueno">Buen Estado</label>
                        <input id="lazos_bueno" name="lazos_bueno" type="number" class="form-control" min="0" maxlength="11" value="0" required="true" onkeypress="return numeric(event)">
                        <label for="lazos_malo">Mal Estado</label>
                        <input id="lazos_malo" name="lazos_malo" type="number" class="form-control" min="0" maxlength="11" value="0" required="true" onkeypress="return numeric(event)">           
                      </div>                                          
                    </td>   
                  </tr>                                                                        
                 </tbody>     

                  <!-- Conectores -->
                  <tr>
                    <td class="celda_tabla_principal celda_boton">
                      <div class="celda_tabla_principal letreros_tabla">Conectores</div>
                    </td>   
                    <td class="celda_tabla_principal celda_boton">
                      <div class="form-group">   
                        <input id="conectores_no" name="conectores" type="radio" onclick="ocultarArea('numero_conectores')" value="0" checked="true">                            
                        <label for="conectores_no">No</label>                            
                      </div>   

                      <div class="form-group">   
                        <input id="conectores_si" name="conectores" type="radio" onclick="mostrarArea('numero_conectores')" value="1">                            
                        <label for="conectores_si">Si</label>                            
                      </div>                                                 
                    </td>    
                    <td class="celda_tabla_principal celda_boton">
                      <div id="numero_conectores" class="form-group" style="display: none;">   
                        <label for="conectores_bueno">Buen Estado</label>
                        <input id="conectores_bueno" name="conectores_bueno" type="number" class="form-control" maxlength="11" value="0" required="true" onkeypress="return numeric(event)">
                        <label for="conectores_malo">Mal Estado</label>
                        <input id="conectores_malo" name="conectores_malo" type="number" class="form-control"  maxlength="11" value="0" required="true" onkeypress="return numeric(event)">           
                      </div>                                          
                    </td>   
                  </tr>


                  <!-- Maderos -->
                  <tr>
                    <td class="celda_tabla_principal celda_boton">
                      <div class="celda_tabla_principal letreros_tabla">Maderos</div>
                    </td>   
                    <td class="celda_tabla_principal celda_boton">
                      <div class="form-group">   
                        <input id="maderos_no" name="maderos" type="radio" onclick="ocultarArea('numero_maderos')" value="0"checked="true">                            
                        <label for="maderos_no">No</label>                            
                      </div>   

                      <div class="form-group">   
                        <input id="maderos_si" name="maderos" type="radio" onclick="mostrarArea('numero_maderos')" value="1">                            
                        <label for="maderos_si">Si</label>                            
                      </div>                                                 
                    </td>    
                    <td class="celda_tabla_principal celda_boton">
                      <div id="numero_maderos" class="form-group" style="display: none;">   
                        <label for="maderos_bueno">Buen Estado</label>
                        <input id="maderos_bueno" name="maderos_bueno" type="number" class="form-control" min="0" maxlength="11" value="0" required="true" onkeypress="return numeric(event)">
                        <label for="maderos_malo">Mal Estado</label>
                        <input id="maderos_malo" name="maderos_malo" type="number" class="form-control" min="0" maxlength="11" value="0" required="true" onkeypress="return numeric(event)">           
                      </div>                                          
                    </td>   
                  </tr>       


                  <!-- Otros -->
                  <tr>
                    <td class="celda_tabla_principal celda_boton">
                      <div class="celda_tabla_principal letreros_tabla">Otros</div>
                    </td>   
                    <td class="celda_tabla_principal celda_boton">
                      <div class="form-group">   
                        <input id="otros_no" name="otros" type="radio" onclick="ocultarArea('numero_otros')" value="0"checked="true">                            
                        <label for="otros_no">No</label>                            
                      </div>   

                      <div class="form-group">   
                        <input id="otros_si" name="otros" type="radio" onclick="mostrarArea('cuales_otros')" value="1">                            
                        <label for="otros_si">Si</label>                            
                      </div>                                                 
                    </td>    
                    <td class="celda_tabla_principal celda_boton">
                      <div id="cuales_otros" class="form-group" style="display: none;">   
                        <label for="otros_cuales">¿Cuáles?</label>
                        <input id="otros_cuales" name="otros_cuales" type="text" class="form-control" maxlength="150">                              
                      </div>                                          
                    </td>   
                  </tr> 
        </table>            
    </div>
  </div>
  <!--Fin Datos Adicionales-->

 <!-- Raspuesta Asyc-Adicion Guardar Pallets -->
<div id="respuestaFrmGuardar" align="center"></div>
<!-- Fin Raspuesta Asyc-Adicion Pallets -->

<!--Botones de Formulario -->
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" name="Regresar" title="Regresar" onclick="document.location='<?=base_url?>'" >
                <img src="<?=base_Father?>imagenes/atras-act.png"/><br />
              <strong>Regresar</strong>
            </button>
            
            <button type="submit" id="btnGuardarVuelo" name="btnGuardarVuelo" title="Guardar" >
                <img src="<?=base_Father?>imagenes/guardar-act.png"/><br />
              <strong>Guardar</strong>
            </button>
      </td>
    </tr>
</table>
<!--Fin Botones de Formulario-->
</form>

<?php include ("modals/alertaGeneral.php"); ?>
<?php include ("modals/palletNuevo.php"); ?>


<script language="javascript">
$(document).ready(function () {

    //Acción al Guardar el Formulario General
    $("#formulario_guardar").bind("submit",function(){        
        var btnGuardar = $("#btnGuardarVuelo");
        var dvRespuesta = $("#respuestaFrmGuardar");
        $.ajax({
            type: $(this).attr("method"),
            url: $(this).attr("action"),
            data:$(this).serialize(),
            beforeSend: function(){             
                btnGuardar.prop('disabled', true);
                dvRespuesta.html('<img src="<?=base_Father?>imagenes/cargando_new.gif" align="absmiddle">');
                $("#alerta").modal("show");
            },
            success: function(data){
                var respuesta = data.split("|*|");
                switch(respuesta[0]){
                  case "error":
                    dvRespuesta.html(respuesta[1]);
                  break;

                  case "tupla":                    
                    dvRespuesta.html(respuesta[1]);                    
                  break; 

                  case "url":                                        
                    dvRespuesta.html("Registro Guardado con Exito<br>Cierre esta ventana y espere a ser redireccionado");                                        
                    $("#alertaCerrar").click(function(){document.location=respuesta[1];});
                    $("#alertaX").click(function(){document.location=respuesta[1];});                    
                    $("#alerta").modal("show");                    
                  break; 

                  default:                    
                    dvRespuesta.html(data);
                  break;                               
                }
            },
            complete:function(data){                
                //btnGuardar.prop('disabled', false);
            },             
            error: function(data){
                dvRespuesta.html('<h2><span style="color: red">Error:</h2></span><h4>No se pudo enviar el formulario</h4>');
            }
        });        
        return false;
    });      
});


function listPallet(key){    
  $.get("<?=base_url?>controllers/AsynController.php",{action:"listPalletAero", id:key},datosProcesados_listPallet);
   cleanForm();
   maximos(key);
}

function datosProcesados_listPallet(datos_devueltos){
    if(datos_devueltos){
      $("#respuesta_listPallet").html(datos_devueltos);       
    }
    else{
      $("#respuesta_listPallet").html("No se obtuvieron datos");       
    }      
}

function cleanForm(){        
    $("#formulario_pallet")[0].reset();
    $("#respuestaFrmGuardar").html("");
    $("#respuestaMaximos").html("");    
}

function maximos(key){ 
  $.get("<?=base_url?>controllers/AsynController.php",{action:"totalesArticulosAerolinea", id:key},datosProcesados_maximos);  
}

function datosProcesados_maximos(datos_devueltos){
    if(datos_devueltos){        
      var dvRespuesta = $("#respuestaMaximos");
      var respuesta = datos_devueltos.split("|*|");
      switch(respuesta[0]){
        case "error":
          dvRespuesta.html(respuesta[1]);
        break;

        case "tupla":                              
          $("#mallas_bueno").attr("max",respuesta[1]);
          $("#mallas_malo").attr("max",respuesta[1]);          

          $("#correas_bueno").attr("max",respuesta[2]);
          $("#correas_malo").attr("max",respuesta[2]);          

          $("#plasticobase_bueno").attr("max",respuesta[3]);
          $("#plasticobase_malo").attr("max",respuesta[3]);          

          $("#plasticocapuchon_bueno").attr("max",respuesta[4]);
          $("#plasticocapuchon_malo").attr("max",respuesta[4]);          

          $("#pernos_bueno").attr("max",respuesta[5]);
          $("#pernos_malo").attr("max",respuesta[5]);          

          $("#vinipel_bueno").attr("max",respuesta[6]);
          $("#vinipel_malo").attr("max",respuesta[6]);          

          $("#lazos_bueno").attr("max",respuesta[7]);
          $("#lazos_malo").attr("max",respuesta[7]);          

          $("#conectores_bueno").attr("max",respuesta[8]);
          $("#conectores_malo").attr("max",respuesta[8]);          

          $("#maderos_bueno").attr("max",respuesta[9]);
          $("#maderos_malo").attr("max",respuesta[9]);
        break; 

        default:                    
          dvRespuesta.html(datos_devueltos);
        break;                               
      }
   
    }
    else{
      dvRespuesta.html("No se obtuvieron datos");       
    }      
}

</script>