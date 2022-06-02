<div class="modal fade" id="addPallet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Adición de Pallet</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>     
      <form name="formulario_pallet" id="formulario_pallet" method="POST" action="<?=base_url?>controllers/AsynController.php" class="was-validated">
        <input type="hidden" name="action" id="action" value="addPalletSession">
        <div class="modal-body">        
            <!--Numero de Pallet -->
            <div class="form-row">
              <div class="col-md-4 mb-3">
                <label for="prefijo">Número de Pallet</label>
                <input type="text" class="form-control" id="prefijo" name="prefijo" placeholder="ABC" value="" required maxlength="3" autofocus="true">
              </div>
              <div class="col-md-4 mb-3">
                <label for="identificador">&nbsp;</label>
                <input type="text" class="form-control" id="identificador" name="identificador" placeholder="555555" value="" required maxlength="6" onkeypress="return numeric(event)">
              </div>
              <div class="col-md-4 mb-3">
                <label for="sufijo">&nbsp;</label>
                <input type="text" class="form-control" id="sufijo" name="sufijo" placeholder="AA" value="" required maxlength="6">                    
              </div>
            </div>

             <!--Estado -->
             <div>
              <label for="buen_estado">¿Está en buen estado?</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="buen_estado" id="bueno" value="1" checked="true">Si
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="buen_estado" id="malo" value="0">No
              </div>
              <br>
            </div>                  

            <!--Ubicacion -->
            <div class="form-group">
              <label for="ubicacion">Ubicación</label>
              <select class="form-control" id="ubicacion" name="ubicacion">
                  <?php while ($fila = $ubicacion->fetch_object()): ?>
                    <option value="<?=$fila->id_ubicacion?>"><?=$fila->nombre?></option>               
                  <?php endwhile; ?>
              </select>
            </div>

             <!--Contenedor Activo -->
             <div>
              <label for="contenedor">¿Es contenedor Activo Refrigerante?</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="contenedor_activo" id="si" value="1"> Si                  
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="contenedor_activo" id="no" value="0" checked="true"> No                 
              </div>
              <br>
            </div>  

            <!--Observación -->    
            <div class="form-group">
              <label for="observacion">Observación</label>
              <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
            </div>                     
        </div>
        <div class="modal-footer">          
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button id="btnGuardarPallet" type="submit" class="btn btn-success" >Guardar</button>
        </div>      
    </form>
    </div>
  </div>
</div>