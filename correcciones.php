<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
	.blanco{
		color:#FF0000;
	}
	div{
		background-color:#FFFFFF;
	}
	.tonomenu{
		border:solid #0066FF;
	}

</style>
</head>
<body>
<?php
require("menu.php");

?>
<p class="titulo_tab_principal">Correcciones</p>
<table height="250px" width="80%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30%">
    	<div id="contenedor1" style="width:95%">
	    	<div id="opcion1" onmousemove="acciones(this.id)" onclick="cargar(this.id)" onmouseout="restablecer(this.id)" title="Clic para ver la descripcion" class="tonomenu" >
	    		- Datos de Inconsistencias
	    	</div>

	    	<div id="opcion2" onmousemove="acciones(this.id)" onclick="cargar(this.id)" onmouseout="restablecer(this.id)" title="Clic para ver la descripcion" class="tonomenu" >
	    		- Reemplazo de Cumplidos
	    	</div>

	    	<div id="opcion3" onmousemove="acciones(this.id)" onclick="cargar(this.id)" onmouseout="restablecer(this.id)" title="Clic para ver la descripcion" class="tonomenu">
   		  		- Volumen de Vuelo
   			</div>	

	    	<div id="opcion4" onmousemove="acciones(this.id)" onclick="cargar(this.id)" onmouseout="restablecer(this.id)" title="Clic para ver la descripcion" class="tonomenu">
   		  		- Gu&iacute;a Despachada
   			</div>	

	    	<div id="opcion5" onmousemove="acciones(this.id)" onclick="cargar(this.id)" onmouseout="restablecer(this.id)" title="Clic para ver la descripcion" class="tonomenu">
   		  		- Adici&oacute;n de Gu&iacute;as
   			</div>	


    	</div>
    </td>
    <td width="70%" style="background-color: #DDDDDD;border:solid #0066FF;">
    	<div id="informacion" style="background-color: #DDDDDD">
    		En esta secci&oacute;n encontrar&aacute; la descripci&oacute;n de la correcci&oacute;n que desea realizar. Adicionalmente podr&aacute; iniciar el proceso de correcci&oacute;n del item seleccionado 
    	</div>
    </td>
  </tr>
</p>
</body>
</html>
<script language="JavaScript">
	function acciones(identificador){
		//alert('identificador');
		//$(identificador).setStyle('background-color','#99CCFF');
		$(identificador).setStyles({
		    color: '#FF0000',
		    'background-color':'#DDDDDD',
		    cursor:'help'
		});
	}	
	
	function cargar(identificador){	
		//Cargar Contenido de cada opcion
		switch(identificador){
			case ('opcion1'):
				$('informacion').innerHTML='<p align="center"><strong>DATOS DE INCONSISTENCIAS</strong></p><br>En esta secci&oacute;n usted podr&aacute; corregir las gu&iacute;as que ubieran finalizado por inconsistencias y registren alg&uacute;n error en las cantidades de piezas, peso, faltantes o reales.<br><p align="center"><button name="iniciar" type="button" onclick="document.location=\'consulta_guia_buscar.php?origen=correccion_inconsistencias\'" <?php  $id_objeto=95; include("config/provilegios_objeto.php");  echo $activacion ?>> <img src="imagenes/settings-act.png" width="45" height="43" title="Iniciar proceso" /><br>Iniciar</button><button name="cancelar" type="button" onclick="document.location=\'base.php\'"><img src="imagenes/cancelar-act.png" width="45" height="43" title="Cancelar" /><br>Cancelar</button></p>';
			break;
			case ('opcion2'):
				$('informacion').innerHTML='<p align="center"><strong>REEMPLAZAR CUMPLIDO</strong></p><br>En esta secci&oacute;n usted podr&aacute; corregir el registro erroneo de un cumplido de una remesa.<br><p align="center"><button name="iniciar" type="button" onclick="document.location=\'correccion_cumplido_remesa.php\'" <?php  $id_objeto=102; include("config/provilegios_objeto.php");  echo $activacion ?>> <img src="imagenes/settings-act.png" width="45" height="43" title="Iniciar proceso" /><br>Iniciar</button><button name="cancelar" type="button" onclick="document.location=\'base.php\'"><img src="imagenes/cancelar-act.png" width="45" height="43" title="Cancelar" /><br>Cancelar</button></p>';
			break;
			case ('opcion3'):
				$('informacion').innerHTML='<p align="center"><strong>VOLUMEN DE VUELO</strong></p><br>En esta secci&oacute;n usted podr&aacute; corregir el valor registrado en el volumen de las gu&iacute;as de un vuelo.<br><p align="center"><button name="iniciar" type="button" onclick="document.location=\'consulta_identificar_vuelo_corregir.php?tipo=volumen\'" <?php  $id_objeto=152; include("config/provilegios_objeto.php");  echo $activacion ?>> <img src="imagenes/settings-act.png" width="45" height="43" title="Iniciar proceso" /><br>Iniciar</button><button name="cancelar" type="button" onclick="document.location=\'base.php\'"><img src="imagenes/cancelar-act.png" width="45" height="43" title="Cancelar" /><br>Cancelar</button></p>';
			break;	
			case ('opcion4'):
				$('informacion').innerHTML='<p align="center"><strong>GUIA DESPACHADA</strong></p><br>En esta secci&oacute;n usted podr&aacute; corregir los  valores registrados en una gu&iacute;a, despues de haber sido despachada.<br><br><strong>NOTA IMPORTANTE:</strong>La modificaci&oacute;n de la gu&iacute;a, generar&aacute; una anotaci&oacute;n en el documento de despacho.<br><p align="center"><button name="iniciar" type="button" onclick="document.location=\'consulta_guia_buscar.php?origen=correccion_postdespacho\'" <?php  $id_objeto=153; include("config/provilegios_objeto.php");  echo $activacion ?>> <img src="imagenes/settings-act.png" width="45" height="43" title="Iniciar proceso" /><br>Iniciar</button><button name="cancelar" type="button" onclick="document.location=\'base.php\'"><img src="imagenes/cancelar-act.png" width="45" height="43" title="Cancelar" /><br>Cancelar</button></p>';
			break;
			case ('opcion5'):
				$('informacion').innerHTML='<p align="center"><strong>ADICION DE GUIAS</strong></p><br>En esta secci&oacute;n usted podr&aacute; adicionar gu&iacute;as, despues de haber finalizado el vuelo por inconsistencias. Las nuevas gu&iacute;as quedar&aacute;n en estado activo en el inventario.<br><p align="center"><button name="iniciar" type="button" onclick="document.location=\'consulta_identificar_vuelo_corregir.php?tipo=addGuia\'" <?php  $id_objeto=154; include("config/provilegios_objeto.php");  echo $activacion ?>> <img src="imagenes/settings-act.png" width="45" height="43" title="Iniciar proceso" /><br>Iniciar</button><button name="cancelar" type="button" onclick="document.location=\'base.php\'"><img src="imagenes/cancelar-act.png" width="45" height="43" title="Cancelar" /><br>Cancelar</button></p>';
			break;
		}
	}

	function restablecer(identificador){
		$(identificador).setStyles({
		    color: '#000000',
		    'background-color':'#FFFFFF'
		});		
	}	

</script>

