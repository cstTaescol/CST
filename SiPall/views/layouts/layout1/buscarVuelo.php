<p class="titulo_tab_principal">Buscador</p>
<p class="asterisco" align="center">Vuelos</p>

<form name="formulario_buscar" id="formulario_buscar" method="POST" action="<?=base_url?>controllers/AsynController.php" class="was-validated">
	<input type="hidden" name="action" id="action" value="buscarVuelo">
	<table align="center">
	  <tr>
	    <td class="celda_tabla_principal"><div class="letreros_tabla">Por No de Vuelo</div></td>
	    <td class="celda_tabla_principal celda_boton"><input type="checkbox" name="coincidencia" id="coincidencia" value="1"></td>
	    <td colspan="2" class="celda_tabla_principal"><input type="text" name="txtcoincidencia" onKeyDown="activar1();" maxlength="13"></td>
	  </tr>
	  <tr>
	    <td class="celda_tabla_principal"><div class="letreros_tabla">Rango de Fecha</div></td>
	    <td class="celda_tabla_principal celda_boton"><input type="checkbox" name="rango" id="rango" value="1"></td>
	    <td width="250px" class="celda_tabla_principal celda_boton">
	    	<div class="asterisco">Desde</div>
	    	<input name="rangoini" type="text" id="rangoini" size="10" readonly="readonly"/>
	      	<input type="button" id="lanzador" value="..." onFocus="activar2()" />
	        <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
	        <!-- script que define y configura el calendario-->
	        <script type="text/javascript">
	            Calendar.setup({
	                inputField     :    "rangoini",      // id del campo de texto
	                ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
	                button         :    "lanzador"   // el id del botón que lanzará el calendario
	            });
				
	        </script>
		</td>
	    <td width="250px" class="celda_tabla_principal celda_boton">
	       <div class="asterisco">Hasta</div>
	      <input name="rangofin" type="text" id="rangofin" size="10" readonly="readonly"/>
	      <input type="button" id="lanzador2" value="..." onFocus="activar2()"/>
	      <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
	      <!-- script que define y configura el calendario-->
	      <script type="text/javascript">
	            Calendar.setup({
	                inputField     :    "rangofin",      // id del campo de texto
	                ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
	                button         :    "lanzador2"   // el id del botón que lanzará el calendario
	            });
	        </script></td>
	  </tr>
	</table>
	<table width="450" align="center">
	    <tr>
	      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
	    </tr>
	    <tr>
	      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
	            <button name="Limpiar" type="reset">
	                <img src="<?=base_Father?>imagenes/descargar-act.png" title="Limpiar" /><br />
	              <strong>Limpiar</strong>
	            </button>
	            
	            <button id="btnBuscar" name="btnBuscar" type="submit">
	                <img src="<?=base_Father?>imagenes/buscar-act.png" title="Buscar" /><br />
	              <strong>Buscar</strong>
	            </button>
	      </td>
	    </tr>
	</table>
</form>    
<div id="respuesta_buscar" align="center"></div>
<script language="javascript">
$(document).ready(function () {

    //Acción al Guardar una Novedad
    $("#formulario_buscar").bind("submit",function(){        
        var btnGuardar = $("#btnBuscar");
        var dvRespuesta = $("#respuesta_buscar");
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


function activar1()
{
    document.getElementById('coincidencia').checked=true
}
function activar2()
{
    document.getElementById('rango').checked=true
}    
 </script>