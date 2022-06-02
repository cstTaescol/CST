<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$tabla=$_GET['tabla'];
switch ($tabla)
{
	case "aerolinea":
		//Privilegios Consultar Todo el Modulo
		$id_objeto=3; 
		include("config/provilegios_modulo.php");  
		//---------------------------	
		$titulo="AEROLINEA";
		$evento_agregar="aerolinea_registro.php";
		$evento_listar="listar_aerolinea.php";
	break;

	case "agente_carga":
		//Privilegios Consultar Todo el Modulo
		$id_objeto=6; 
		include("config/provilegios_modulo.php");  
		//---------------------------	
		$titulo="AGENTE DE CARGA";
		$evento_agregar="agente_carga.php";
		$evento_listar="listar_agente_carga.php";
	break;

	case "conductor":
		//Privilegios Consultar Todo el Modulo
		$id_objeto=9; 
		include("config/provilegios_modulo.php");  
		//---------------------------	
		$titulo="CONDUCTOR";
		$evento_agregar="conductor_registro.php";
		$evento_listar="listar_conductor.php";
	break;

	case "consignatario":
		//Privilegios Consultar Todo el Modulo (UNICAMENTE PARA LOS COORDINADORES)
		$id_objeto=110; 
		include("config/provilegios_modulo.php");  
		//---------------------------	
		$titulo="CONSIGNATARIO";
		$evento_agregar="consignatario_nuevo.php";
		$evento_listar="listar_consignatario.php";
	break;

	case "deposito":
		//Privilegios Consultar Todo el Modulo
		$id_objeto=15; 
		include("config/provilegios_modulo.php");  
		//---------------------------	
		$titulo="DEPOSITO";
		$evento_agregar="deposito_nuevo.php";
		$evento_listar="listar_deposito.php";
	break;

	case "embarcador":
		//Privilegios Consultar Todo el Modulo
		$id_objeto=18; 
		include("config/provilegios_modulo.php");  
		//---------------------------	
		$titulo="EMBARCADOR";
		$evento_agregar="embarcador_nuevo.php";
		$evento_listar="listar_embarcador.php";
	break;

	case "feriados":
		//Privilegios Consultar Todo el Modulo
		$id_objeto=21; 
		include("config/provilegios_modulo.php");  
		//---------------------------	
		$titulo="DIAS FERIADOS";
		$evento_agregar="fecha_nuevo.php";
		$evento_listar="listar_feriados.php";
	break;

	case "movilizacion":
		//Privilegios Consultar Todo el Modulo
		$id_objeto=24; 
		include("config/provilegios_modulo.php");  
		//---------------------------	
		$titulo="MOVILIZACION - (IN)MOVILIZACION";
		$evento_agregar="movilizacion.php";
		$evento_listar="listar_movilizacion.php";
	break;

	case "rutas":
		//Privilegios Consultar Todo el Modulo
		$id_objeto=27; 
		include("config/provilegios_modulo.php");  
		//---------------------------	
		$titulo="RUTAS";
		$evento_agregar="ruta_nuevo.php";
		$evento_listar="listar_ruta.php";
	break;

	case "transportador":
		//Privilegios Consultar Todo el Modulo
		$id_objeto=30; 
		include("config/provilegios_modulo.php");  
		//---------------------------	
		$titulo="TRANSPORTADOR";
		$evento_agregar="transportador_registro.php";
		$evento_listar="listar_transportador.php";
	break;

	case "vehiculos":
		//Privilegios Consultar Todo el Modulo
		$id_objeto=33; 
		include("config/provilegios_modulo.php");  
		//---------------------------	
		$titulo="VEHICULOS";
		$evento_agregar="vehiculo_registro.php";
		$evento_listar="listar_vehiculo.php";
	break;

	case "vehiculos_courier":
		//Privilegios Consultar Todo el Modulo
		$id_objeto=133; 
		include("config/provilegios_modulo.php");  
		//---------------------------	
		$titulo="VEHICULOS COURIER";
		$evento_agregar="vehiculo_courier_registro.php";
		$evento_listar="listar_vehiculo_courier.php";
	break;

	case "couriers":
		//Privilegios Consultar Todo el Modulo
		$id_objeto=121; 
		include("config/provilegios_modulo.php");  
		//---------------------------	
		$titulo="COURIERS";
		$evento_agregar="courier_nuevo.php";
		$evento_listar="listar_couriers.php";
	break;

	case "courier_funcionario":
		//Privilegios Consultar Todo el Modulo
		$id_objeto=135; 
		include("config/provilegios_modulo.php");  
		//---------------------------	
		$titulo="FUNCIONARIOS DEL COURIER";
		$evento_agregar="courier_funcionario_registro_parametrica.php";
		$evento_listar="listar_courier_funcionario.php";
	break;

	case "courier_funcionario_entidad":
		//Privilegios Consultar Todo el Modulo
		$id_objeto=135; 
		include("config/provilegios_modulo.php");  
		//---------------------------	
		$titulo="FUNCIONARIOS";
		$evento_agregar="courier_funcionario_registro_parametrica_entidad.php";
		$evento_listar="listar_courier_funcionario_entidad.php";
	break;

}
?>
<html>
<head>
<style type="text/css">
<!--
.ROJO {
	color: #F00;
}
-->
</style>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Consulta Tablas Parametricas</p>
<p align="center"><font size="+1" color="#99CC33"><strong><?php echo $titulo ?></strong></font></p>
<table width="610"  border="0" align="center">
  <tr>
    <td class="celda_tabla_principal celda_boton" width="250" height="123" align="center" valign="middle">
    	<button onClick="document.location='<?php echo $evento_agregar ?>';">
        	<img src="imagenes/agregar-act.png" title="Agregar nuevo registro">
         </button>    
    </td>
    <td class="celda_tabla_principal celda_boton" width="250" height="123" align="center" valign="middle">
    	<button onClick="document.location='<?php echo $evento_listar ?>';">
        	<img src="imagenes/buscar-act.png" alt="Consultar registros">
        </button>
	</td>
  </tr>
  <tr>
    <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">Agregar</div></td>
    <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">Consultar</div></td>  </tr>
</table>
<br>
<p align="left">&nbsp;</p>
<p align="left">&Eacute;stas consultas pueden tornarse demoradas dependiendo de la cantidad de registros de la base de datos.</p>
<p align="left"> <strong class="ROJO">POR FAVOR ESPERE</strong> una vez seleccione la opci&oacute;n.</p>
</body>
</html>