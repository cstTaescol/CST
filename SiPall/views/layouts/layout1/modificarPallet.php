<p class="titulo_tab_principal">Modificar</p>
<p class="asterisco" align="center">Pallet</p>
<!--Menu de acciones superior -->
<table align="center">
  <tr>
    <td align="center" class="celda_tabla_principal">
      <div class="letreros_tabla">Estado </div>
      <font class="asterisco" size="+3"><i><div id="estado"><?=$estado->nombre?></div></i></font>
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Regresar</div>
          <form method="POST" action="<?=base_url?>Central/consultaPallet">
              <input type="hidden" name="pallet" value="<?=$pallet->id_pallet?>">  
              <button type="submit" title="Regresar al Pallet">
                  <img src="<?=base_Father?>imagenes/atras-act.png"/>
              </button>                                                           
          </form>  
    </td>                           
  </tr>
</table>
<br>
<form name="formulario_pallet" id="formulario_pallet" method="POST" action="<?=base_url?>controllers/AsynController.php" class="was-validated">
  <!--Campos de parametros -->
  <input type="hidden" id="id_pallet" name="id_pallet"value="<?=$pallet->id_pallet?>">
  <input type="hidden" id="action" name="action"value="guardarModificarPallet">
  <table align="center" width="80%">
    <!--Numero de Pallet -->
    <tr >
      <td width="180" class="celda_tabla_principal"><div class="letreros_tabla">Número</div></td>
      <td class="celda_tabla_principal celda_boton">
          <input type="text" class="form-control" id="numero" name="numero" placeholder="AAA555555BB"  value="<?=$pallet->numero?>" required maxlength="13">
      </td>
    </tr>
    <!--Aerolinea -->
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolínea</div></td>
      <td class="celda_tabla_principal celda_boton">
                <select class="form-control" id="aerolinea" name="aerolinea">
                    <?php while ($fila = $aerolinea->fetch_object()): ?>                    
                      <option value="<?=$fila->id?>" <?=$fila->id==$pallet->aerolinea ? "selected" : "" ?>><?=$fila->nombre?></option>
                    <?php endwhile; ?>
                </select>        
      </td>
    </tr>
    <!--Ubicacion -->
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Ubicación</div></td>
      <td class="celda_tabla_principal celda_boton">
                <select class="form-control" id="ubicacion" name="ubicacion">
                    <?php while ($fila = $ubicacion->fetch_object()): ?>                    
                      <option value="<?=$fila->id_ubicacion?>" <?=$fila->id_ubicacion==$pallet->ubicacion ? "selected" : "" ?>><?=$fila->nombre?></option>               
                    <?php endwhile; ?>
                </select>    
      </td>
    </tr>
    <!--Observacion -->
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
      <td class="celda_tabla_principal celda_boton">
        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?=$pallet->observaciones?></textarea>
      </td>
    </tr>
    <!--Contenedor Activo -->
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">¿Es contenedor Activo Refrigerante?</div></td>
      <td class="celda_tabla_principal celda_boton">
              
               <div>
                  <label for="contenedor">¿Es contenedor Activo Refrigerante?</label>
                  <div class="form-check">
                    <input type="radio" class="form-check-input" name="contenedor_activo" id="si" value="1" <?=$pallet->contenedor_activo==1 ? "checked" : "" ?>> Si                      
                  </div>
                  <div class="form-check">
                    <input type="radio" class="form-check-input" name="contenedor_activo" id="no" value="0" <?=$pallet->contenedor_activo==0 ? "checked" : "" ?>> No                 
                  </div>
                  <br>
              </div> 
      </td>
    </tr>
     <!--Estado -->
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">¿Está en buen estado?</div></td>
      <td class="celda_tabla_principal celda_boton">            
               <div>
                  <label for="buen_estado">¿Está en buen estado?</label>
                  <div class="form-check">
                    <input type="radio" class="form-check-input"  name="buen_estado" id="bueno" value="1" <?=$pallet->buen_estado==1 ? "checked" : "" ?>>Si
                  </div>
                  <div class="form-check">
                    <input type="radio" class="form-check-input"  name="buen_estado" id="malo" value="0" <?=$pallet->buen_estado==0 ? "checked" : "" ?>>No
                  </div>
                  <br>
              </div>  
      </td>
    </tr>
  </table>
  <br>

  <!--Botones de Formulario -->
  <table width="450" align="center">
      <tr>
        <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
      </tr>
      <tr>
        <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
              <button type="reset" name="Restablecer" title="Restablecer">
                  <img src="<?=base_Father?>imagenes/editar-act.png"/><br />
                <strong>Limpiar</strong>
              </button>
              
              <button type="submit" id="btnGuardarPallet" name="btnGuardarPallet" title="Guardar" >
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
    $("#formulario_pallet").bind("submit",function(){        
        var btnGuardar = $("#btnGuardarPallet");
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