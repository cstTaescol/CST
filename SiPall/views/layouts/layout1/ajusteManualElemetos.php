<p class="titulo_tab_principal">Ajuste</p>
<p class="asterisco" align="center">Items de Aerolineas</p>
<form name="formulario_guardar" id="formulario_guardar" method="POST" action="<?=base_url?>controllers/AsynController.php" class="was-validated">
  <input type="hidden" name="action" id="action" value="guardarAjusteManualElementos">
  <table align="center">
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
  <br>
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

            <button name="Limpiar" type="reset">
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