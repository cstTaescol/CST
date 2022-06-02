<div class="modal fade" id="reubicacionPallet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Re-ubicación de Pallet</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>     
      <form name="formulario_reubicacion" id="formulario_reubicacion" method="POST" action="<?=base_url?>controllers/AsynController.php" class="was-validated">
        <input type="hidden" name="action" id="action" value="guardarReubicacionPallet">
        <input type="hidden" name="id_pallet_Reubicacion" id="id_pallet_Reubicacion" value="">
        <div class="modal-body">        
            <!--Numero de Pallet -->
            <div class="form-row">
              <div class="col-md-4 mb-3">
                <label for="no_pallet_reubicacion">Número de Pallet</label>
                <input id="no_pallet_reubicacion" name="no_pallet_reubicacion" type="text" class="form-control"  value="" readonly="true">
              </div>
            </div>

            <!--Aerolinea -->                
            <div class="form-group">
              <label for="reubicacion">Mover a la posición:</label>              
              <select name="reubicacion" id="reubicacion" class="form-control" required="true">
                    <option value="" selected="true">Seleccione una</option>
                    <?php while ($fila = $ubicacionReubicacion->fetch_object()): ?>
                      <option value="<?=$fila->id_ubicacion?>"><?=$fila->nombre?></option>               
                    <?php endwhile; ?>
              </select> 
            </div>  
        </div>
        <div class="modal-footer">          
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button id="btnGuardarReubicacion" type="submit" class="btn btn-success" >Guardar</button>
        </div>      
        <div id="respuesta_ReubicacionPallet" align="center"></div>
    </form>
    </div>
  </div>
</div>