<div class="modal fade" id="addTransferenciaPallet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Adición de Novedad</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>     
      <form name="formulario_transferencia" id="formulario_transferencia" method="POST" action="<?=base_url?>controllers/AsynController.php" class="was-validated">
        <input type="hidden" name="action" id="action" value="guardarTransferenciaPalletIndividual">
        <input type="hidden" name="id_pallet_transferencia" id="id_pallet_transferencia" value="">
        <div class="modal-body">        
            <!--Numero de Pallet -->
            <div class="form-row">
              <div class="col-md-4 mb-3">
                <label for="no_pallet_transferencia">Número de Pallet</label>
                <input id="no_pallet_transferencia" name="no_pallet_transferencia" type="text" class="form-control"  value="" readonly="true">
              </div>
            </div>

            <!--Aerolinea -->                
            <div class="form-group">
              <label for="descripcion">Transferir hacia la Aerolinea:</label>              
              <select name="id_aerolinea_transferir" id="id_aerolinea_transferir" class="form-control" required="true">
                    <option value="" selected="true">Seleccione una</option>
                    <?php while ($fila = $aerolineaTransferencia->fetch_object()): ?>
                      <option value="<?=$fila->id?>"><?=$fila->nombre?></option>               
                    <?php endwhile; ?>
              </select> 
            </div>  
        </div>
        <div class="modal-footer">          
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button id="btnGuardarTransferir" type="submit" class="btn btn-success" >Guardar</button>
        </div>      
        <div id="respuesta_addTransferenciaPallet" align="center"></div>
    </form>
    </div>
  </div>
</div>