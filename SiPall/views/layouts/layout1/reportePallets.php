<p class="titulo_tab_principal">Reporte de Pallets</p>
<p class="asterisco" align="center">Rango de Fecha</p>

<form name="formulario_buscar" id="formulario_buscar" method="POST" action="<?=base_url?>controllers/AsynController.php" class="was-validated">
	<input type="hidden" name="action" id="action" value="reportePallets">
	<table align="center">
	  <tr>
	    <td class="celda_tabla_principal"><div class="letreros_tabla">Rango de Fecha</div></td>	    
	    <td width="250px" class="celda_tabla_principal celda_boton">
	    	<div class="asterisco">Desde</div>
	    	<input type="text" name="rangoini"  id="rangoini" size="10" readonly="readonly"/>
	      	<input type="button" id="lanzador" value="..."/>
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
	      <input type="text" name="rangofin"  id="rangofin" size="10" readonly="readonly"/>
	      <input type="button" id="lanzador2" value="..."/>
	      <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
	      <!-- script que define y configura el calendario-->
	      <script type="text/javascript">
	            Calendar.setup({
	                inputField     :    "rangofin",      // id del campo de texto
	                ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
	                button         :    "lanzador2"   // el id del botón que lanzará el calendario
	            });
	        </script>
	    </td>
	  </tr>
	  <tr>
	    <td class="celda_tabla_principal"><div class="letreros_tabla">Tipo</div></td>	    
	    <td colspan="2" width="250px" class="celda_tabla_principal celda_boton">
	    	<select name="tipoReporte" id="tipoReporte"  class="form-control">
	    		<option value="recibido">Recibido</option>
	    		<option value="despachado">Despachado</option>
	    		<option value="*">Recibido y Despachado</option>	    		
	    	</select>
		</td>
	  </tr>
	</table>
	<br>
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
	            
	            <button type="submit" id="btnBuscar" name="btnBuscar">
	                <img src="<?=base_Father?>imagenes/buscar-act.png" title="Buscar" /><br />
	              	<strong>Buscar</strong>
	            </button>
		        <button type="button" id="btnReporte" name="btnReporte" onclick="accesoReporte()" disabled="disabled">
		          <img src="<?=base_Father?>imagenes/excel.jpg" width="28" title="Exportar el informe a Excel" /><br>
		          <strong>Exportar</strong>
		        </button> 	            
	      </td>
	    </tr>
	</table>
</form>    
<div id="respuesta_buscar" align="center"></div>
<?php include ("modals/alertaUCM.php"); ?>
<script language="javascript">
$(document).ready(function () {
    //Acción al Guardar una Novedad
    $("#formulario_buscar").bind("submit",function(){        
        var btnGuardar = $("#btnBuscar");
        var btnReporte = $("#btnReporte");
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
                    dvRespuesta.html(respuesta[1]);
                    btnReporte.removeAttr("disabled");
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

function accesoReporte(){
	var tipoReporte = $("#tipoReporte").val();
	var rangoini = $("#rangoini").val();
	var rangofin = $("#rangofin").val();	
	document.location='<?=base_url?>excelReports/reportePalletsRangoFecha.php?tipoReporte='+tipoReporte+'&rangoini='+rangoini+'&rangofin='+rangofin+'';
}
 </script>}
