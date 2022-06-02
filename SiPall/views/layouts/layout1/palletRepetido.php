<p class="titulo_tab_principal">Pallets Repetidos</p>
<p class="asterisco" align="center">Atención: Los siguientes pallets que registró, se se encuentran repetidos en el inventario de Bodega</p>
<form name="formulario_guardar" id="formulario_guardar" method="POST" action="<?=base_url?>controllers/AsynController.php" class="was-validated">
	<input type="hidden" name="action" id="action" value="guardarNovedadesMultiples">	
	<input type="hidden" name="cantidadRegistros" id="cantidadRegistros" value="<?=$cont?>">
	<table align="center" width="80%">
	  <tr>
	    <td class="celda_tabla_principal"><div class="letreros_tabla">Número de Pallet</div></td>
	    <td class="celda_tabla_principal"><div class="letreros_tabla">Novedad</div></td>    
	  </tr>
	  <?=$buffer?>
	</table>
	<br>
	<!--Botones de Formulario -->
	<table width="450" align="center">
	<tr>
	  <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
	</tr>
	<tr>
	  <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
	        <button type="reset" name="Limpiar">
	            <img src="<?=base_Father?>imagenes/descargar-act.png" title="Limpiar" /><br />
	          <strong>Limpiar</strong>
	        </button>

	        <button type="submit" id="btnGuardar" name="btnGuardar" title="Guardar" >
	            <img src="<?=base_Father?>imagenes/guardar-act.png"/><br />
	          <strong>Guardar</strong>
	        </button>
	  </td>
	</tr>
	</table>
	<!--Fin Botones de Formulario-->
</form>

<?php include ("modals/alertaGeneral.php"); ?>
<script language="javascript">
$(document).ready(function () {

    //Acción al Guardar una Novedad
    $("#formulario_guardar").bind("submit",function(){        
        var btnGuardar = $("#btnGuardar");
        var dvRespuesta = $("#alertaContenido");
        $.ajax({
            type: $(this).attr("method"),
            url: $(this).attr("action"),
            data:$(this).serialize(),
            beforeSend: function(){                             
                btnGuardar.attr("disabled","disabled");
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
                    dvRespuesta.html("Registro Guardado con Exito");                                        
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
                dvRespuesta.html('<h2><span style="color: red">Error:</h2></span><h4>No se pudo enviar el formulario</h4>');
            }
        });        
        return false;
    });    
});
</script>