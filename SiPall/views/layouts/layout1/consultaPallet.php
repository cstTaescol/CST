<p class="titulo_tab_principal">Informacion</p>
<p class="asterisco" align="center">Pallet</p>
<table align="center">
  <tr>
    <td align="center" class="celda_tabla_principal">
      <div class="letreros_tabla">Estado </div>
      <font class="asterisco" size="+3"><i><div id="estado"><?=$estado->nombre?></div></i></font>
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Des-Activar</div>
      <button type="button" title="Activar o Desactivar el Pallet" onclick="prepareCambioEstado(<?=$id_pallet?>)" <?=$privilegioDesactivar?>>
          <img src="<?=base_Father?>imagenes/aceptar-act.png"/>
    </button>     
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Modificar</div>
      <form method="POST" action="<?=base_url?>Central/modificarPallet">
        <input type="hidden" name="pallet" value="<?=$pallet->id_pallet?>"> 
        <button type="submit" title="Modificar" <?=$privilegioModificar?>>
            <img src="<?=base_Father?>imagenes/settings-act.png"/>
        </button>     
      </form>
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Historial</div>      
    <button type="button" title="Mostrar el Historial" onclick="prepareFormHistorial(<?=$id_pallet?>)" <?=$privilegioHistorial?>>
          <img src="<?=base_Father?>imagenes/buscar-act.png"/>
    </button>         
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Novedad</div>           
        <button type="button" title="Ver y Agregar Novedades" data-toggle="modal" data-target="#addNovedadPallet" onclick="prepareFormNovedad(<?=$id_pallet?>)" <?=$privilegioNovedad?>>
          <img src="<?=base_Father?>imagenes/alerta-act.png">
        </button>
    </td>
  </tr>
</table>
<br>
<table align="center" width="80%">
  <tr >
    <td width="180" class="celda_tabla_principal"><div class="letreros_tabla">Número</div></td>
    <td class="celda_tabla_principal celda_boton"><?=$pallet->numero?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolínea</div></td>
    <td class="celda_tabla_principal celda_boton"><?=$aerolinea->nombre?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Ubicación</div></td>
    <td class="celda_tabla_principal celda_boton"><?=$ubicacion->nombre?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
    <td class="celda_tabla_principal celda_boton"><?=$pallet->observaciones?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">¿Es contenedor Activo Refrigerante?</div></td>
    <td class="celda_tabla_principal celda_boton"><?=$contenedorActivo?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">¿Está en buen estado?</div></td>
    <td class="celda_tabla_principal celda_boton"><?=$buenEstado?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha de Creación</div></td>
    <td class="celda_tabla_principal celda_boton"><?=$pallet->fechahora_creacion?></td>
  </tr>
</table>

<?php include ("modals/historialPallet.php"); ?>
<?php include ("modals/addNovedadPallet.php"); ?>
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
                    $("#descripcion").val("");
                    dvRespuesta.html(respuesta[2]);
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
                dvRespuesta.html('<h2><span style="color: red">Error:</h2></span><h4>No se pudo enviar el formulario</h4>');
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

function prepareFormHistorial(id_pallet){       
    $("#respuesta_historialPallet").html("");
    $("#historialPallet").modal("show");
    $.get("<?=base_url?>controllers/AsynController.php",{action:"consultaHistorialPallet", id:id_pallet},datosProcesadosFormHistorial);
}

function datosProcesadosFormHistorial(datos_devueltos){
    var respuesta = datos_devueltos.split("|*|");
    switch(respuesta[0]){
      case "error":
        $("#respuesta_historialPallet").html(respuesta[1]);                
      break;

      case "tupla":        
        $("#no_palletHistorial").val(respuesta[1]);
        $("#respuesta_historialPallet").html(respuesta[2]);
      break;      
    }          
}

function prepareCambioEstado(id_pallet){       
    $("#alertaContenido").html('<div align="center"><img src="<?=base_Father?>imagenes/cargando_new.gif" title="Procesando" /></div>');
    $("#alerta").modal("show");
    $.get("<?=base_url?>controllers/AsynController.php",{action:"activarPallet", id:id_pallet},datosProcesadosCambioEstado);
}

function datosProcesadosCambioEstado(datos_devueltos){
    var respuesta = datos_devueltos.split("|*|");
    switch(respuesta[0]){
      case "error":
        $("#alertaContenido").html(respuesta[1]);                
      break;

      case "tupla":                
        $("#estado").html(respuesta[1]);
        $("#alertaContenido").html(respuesta[2]);
      break;      
    }          
}

</script>