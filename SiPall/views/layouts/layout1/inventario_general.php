<p class="titulo_tab_principal">Inventario</p>
<p class="asterisco" align="center">Pallets</p>
<!-- Raspuesta Asyc-Adicion Pallets -->
<div id="respuesta">
  <table class="table table-hover">
    <thead class="celda_tabla_principal">
      <tr>        
        <th class="celda_tabla_principal"><div class="letreros_tabla">Acciones</div></th>
        <th class="celda_tabla_principal"><div class="letreros_tabla">Número de Pallet</div></th>
        <th class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></th>
        <th class="celda_tabla_principal"><div class="letreros_tabla">No Vuelo</div></th>
        <th class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></th>
        <th class="celda_tabla_principal"><div class="letreros_tabla">Ubicación</div></th>
        <th class="celda_tabla_principal"><div class="letreros_tabla">Buen Estado</div></th>  
        <th class="celda_tabla_principal"><div class="letreros_tabla">Contenedor Activo</div></th>        
        <th class="celda_tabla_principal"><div class="letreros_tabla">Días en Bodega</div></th>        
      </tr>      
    </thead>        
  <?=$buffer?>  
  <thead class="celda_tabla_principal">    
    <tr>        
      <th class="celda_tabla_principal"><div class="letreros_tabla">Acciones</div></th>
      <th class="celda_tabla_principal"><div class="letreros_tabla">Número de Pallet</div></th>
      <th class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></th>
      <th class="celda_tabla_principal"><div class="letreros_tabla">No Vuelo</div></th>
      <th class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></th>
      <th class="celda_tabla_principal"><div class="letreros_tabla">Ubicación</div></th>
      <th class="celda_tabla_principal"><div class="letreros_tabla">Buen Estado</div></th>  
      <th class="celda_tabla_principal"><div class="letreros_tabla">Contenedor Activo</div></th>        
      <th class="celda_tabla_principal"><div class="letreros_tabla">Días en Bodega</div></th>        
    </tr>   
  </thead>    
  </table>
</div>

<table>
  <thead class="celda_tabla_principal">
    <tr>        
      <th colspan="11" class="celda_tabla_principal"><div class="letreros_tabla">Totales</div></th>
    </tr>    
    <tr>        
      <th class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></th>
      <th class="celda_tabla_principal"><div class="letreros_tabla">No.Pallets</div></th>
      <th class="celda_tabla_principal"><div class="letreros_tabla">Mallas</div></th>
      <th class="celda_tabla_principal"><div class="letreros_tabla">Correas</div></th>
      <th class="celda_tabla_principal"><div class="letreros_tabla">Plastico Base</div></th>
      <th class="celda_tabla_principal"><div class="letreros_tabla">Plastico Capuchon</div></th>  
      <th class="celda_tabla_principal"><div class="letreros_tabla">Pernos</div></th>        
      <th class="celda_tabla_principal"><div class="letreros_tabla">Vinipel</div></th>        
      <th class="celda_tabla_principal"><div class="letreros_tabla">Lazos</div></th>        
      <th class="celda_tabla_principal"><div class="letreros_tabla">Conectores</div></th>
      <th class="celda_tabla_principal"><div class="letreros_tabla">Maderos</div></th>
    </tr>        
  </thead>
  <?=$bufferArticulos?>    
</table>


<?php include ("modals/addNovedadPallet.php"); ?>
<?php //include ("modals/addTransferenciaPallet.php"); ?>
<?php include ("modals/addReubicacion.php");?>
<?php include ("modals/alertaGeneral.php"); ?>


<script language="javascript">
$(document).ready(function () {

    //Acción al Guardar una Novedad
    $("#formulario_novedad").bind("submit",function(){        
        var btnGuardar = $("#btnGuardarNovedad");
        var dvRespuesta = $("#respuesta_addNovedad");
        $.ajax({
            type: $(this).attr("method"),
            url: $(this).attr("action"),
            data:$(this).serialize(),
            beforeSend: function(){             
                btnGuardar.val("Guardando...");
                btnGuardar.attr("disabled","disabled");
                dvRespuesta.html('<img src="<?=base_Father?>imagenes/cargando_new.gif" align="absmiddle">');
            },
            complete:function(data){
                btnGuardar.val("Guardado");                
            },
            success: function(data){
                //$("#respuesta_addNovedad").html(data);              
                btnGuardar.removeAttr("disabled"); 
                var respuesta = data.split("|*|");                
                switch(respuesta[0]){
                  case "error":
                    dvRespuesta.html(respuesta[1]);
                  break;

                  case "tupla":
                    $("#descripcion").val("");
                    dvRespuesta.html(respuesta[2]);
                  break;   

                  default:                    
                    dvRespuesta.html(data);
                  break;

                }                
                
            },
            error: function(data){
                dvRespuesta.html('<h2><span style="color: red">Error:</h2></span><h4>No se pudo enviar el formulario</h4>');
            }
        });        
        return false;
    });    


    //Acción al Reubicar el Pallet
    $("#formulario_reubicacion").bind("submit",function(){        
        var btnGuardar = $("#btnGuardarReubicacion");
        var dvRespuesta = $("#respuesta_ReubicacionPallet");

        $.ajax({
            type: $(this).attr("method"),
            url: $(this).attr("action"),
            data:$(this).serialize(),
            beforeSend: function(){                             
                btnGuardar.attr("disabled","disabled");
                dvRespuesta.html('<img src="<?=base_Father?>imagenes/cargando_new.gif" align="absmiddle">');
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
                    $("#addTransferenciaPallet").modal("hide");
                    $("#alertaContenido").html("Registro Guardado con Exito");                                        
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
                btnGuardar.removeAttr("disabled");
            },                         
            error: function(data){
                $("#respuesta_addTransferenciaPallet").html('<h2><span style="color: red">Error:</h2></span><h4>No se pudo enviar el formulario</h4>');
            }
        });        
        return false;
    });    
});


function prepareFormNovedad(id_pallet){
    $("#formulario_novedad")[0].reset();
    $("#respuesta_addNovedad").html("");
    $("#id_pallet").val(id_pallet);
    $.get("<?=base_url?>controllers/AsynController.php",{action:"consultaNovedadPallet", id:id_pallet},datosProcesadosFormNovedad);
}

function datosProcesadosFormNovedad(datos_devueltos){
    var respuesta = datos_devueltos.split("|*|");
    switch(respuesta[0]){
      case "error":
        $("#respuesta_addNovedad").html(respuesta[1]);        
        //desactivar boton de guardar
      break;

      case "tupla":        
        $("#no_pallet").val(respuesta[1]);
        $("#respuesta_addNovedad").html(respuesta[2]);
      break;      
    }     
}

function prepareFormReubicacion(id_pallet){    
    $("#formulario_reubicacion")[0].reset();
    $("#respuesta_ReubicacionPallet").html("");
    $("#id_pallet_Reubicacion").val(id_pallet);
    $.get("<?=base_url?>controllers/AsynController.php",{action:"reubicacionPallet", id:id_pallet},datosProcesadosFormReubicacion);
}

function datosProcesadosFormReubicacion(datos_devueltos){
    var respuesta = datos_devueltos.split("|*|");
    switch(respuesta[0]){
      case "error":
        $("#respuesta_ReubicacionPallet").html(respuesta[1]);                
      break;

      case "tupla":        
        $("#no_pallet_reubicacion").val(respuesta[1]);        
      break;      
    }     
}
</script>