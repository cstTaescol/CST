<!-- paginador de registros -->
<form>
<table align="center">
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">P&aacute;gina Actual</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Seleccione la P&aacute;gina</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Estado</div></td>
  	</tr>
    <tr>
      <td class="celda_tabla_principal celda_boton"><?php echo $pagina/100 ?></td>
      <?php
      	//Calculo de Pagina Anterior.
      	if ($pagina==0){
			$pagina_anterior= 0;
		}
		else {
			$pagina_anterior= $pagina - 100;
		}		
		//Calculo de proxima Pagina.
		$pagina_proxima= $pagina + 100;
		
		//Calculo de Tablas Activa
		if ($estado_tabla == "A")
		{
			$checkActivo='checked="checked"';
			$checkInactivo='';
		}
		else
			{
				$checkActivo='';
				$checkInactivo='checked="checked"';				
			}
      ?>
      <td class="celda_tabla_principal celda_boton">
            <button type="button" onclick="cambiar_pagina(<?php echo $pagina_anterior ?>)"> < </button>
	      	<select name="pagina" id="pagina" onchange="cambiar_pagina(this.value)">
				<option value="0">Seleccione Una</option>
				<?php echo $impresion_paginador ?>
			</select>
			<button type="button" onclick="cambiar_pagina(<?php echo $pagina_proxima ?>)"> > </button>
	  </td>
      <td class="celda_tabla_principal celda_boton">
      	<input type="radio" name="estado_tabla" id="estado_tabla" <?php echo $checkActivo; ?> value="A" onchange="cambiar_pagina(<?php echo $pagina ?>)" />Activo<br />
        <input type="radio" name="estado_tabla" id="estado_tabla" <?php echo $checkInactivo; ?> value="I" onchange="cambiar_pagina(<?php echo $pagina ?>)"/>Inactivo<br />
      </td>  
  	</tr>  	
</table>
</form>
<!-- fin paginador de registros -->