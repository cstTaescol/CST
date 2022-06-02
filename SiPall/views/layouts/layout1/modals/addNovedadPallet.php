<div class="modal fade" id="addNovedadPallet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Adición de Novedad</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>     
      <form name="formulario_novedad" id="formulario_novedad" method="POST" action="<?=base_url?>controllers/AsynController.php" class="was-validated">
        <input type="hidden" name="action" id="action" value="guardarNovedadPallet">
        <input type="hidden" name="id_pallet" id="id_pallet" value="">
        <div class="modal-body">        
            <!--Numero de Pallet -->
            <div class="form-row">
              <div class="col-md-4 mb-3">
                <label for="no_pallet">Número de Pallet</label>
                <input id="no_pallet" name="no_pallet" type="text" class="form-control"  value="" readonly="true">
              </div>
            </div>

            <!--Observación -->                
            <div class="form-group">
              <label for="descripcion">Descripción</label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required="true"></textarea>
            </div>
            <div class="form-group">
              <div id="descripcion_db" class="overflow-auto"></div>                
            </div>
        </div>
        <div class="modal-footer">          
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button id="btnGuardarNovedad" type="submit" class="btn btn-success" >Guardar</button>
        </div>      
        <div id="respuesta_addNovedad" align="center"></div>
    </form>
    </div>
  </div>
</div>