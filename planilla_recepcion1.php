<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$cont=0;
?>
<html>
<head>
	<style>
        .titulo_add{
            color:#FFF;
        }
		.opaco_ie { 
 			filter: alpha(opacity=30);
		}
    </style>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-Hoja de estilos del calendario -->
	<!-- librería principal del calendario -->
	<link rel="stylesheet" type="text/css" media="all" href="js/calendar-color.css" title="win2k-cold-1" />
	<script type="text/javascript" src="js/calendar.js"></script>
	
	<!-- librería para cargar el lenguaje deseado -->
	<script type="text/javascript" src="js/calendar-es.js"></script>
	
	<!-- librería que declara la función Calendar.setup, que ayuda a generar un calendario en unas pocas líneas de código -->
	<script type="text/javascript" src="js/calendar-setup.js"></script>
	<script language="javascript">
	function abrir(url)
	{
		popupWin = window.open(url,'','directories, status, scrollbars, resizable, dependent, width=640, height=480, left=100, top=100')
		//  popupWin = window.open('pdf_remesa.php','nombre_ventana','menubar, toolbar, location, directories, status, scrollbars, resizable, dependent, width=640, height=480, left=0, top=0')
	}
	//Activador automatico de los checksboxs segun el caso de solicitud.
	function activar1()
	{
		document.getElementById('coincidencia').checked=true
	}
	function activar2()
	{
		document.getElementById('rango').checked=true
	}
	//Funcion para confirmar la eliminacion de una remesa
	function conf_eliminar(url)
	{
	var respuesta=confirm('ATENCION: Confirma que ¿Desea Eliminar la Remesa?,  Las GUIAS despachadas seran Retornadas al INVENTARIO');
	if (respuesta)
		{
			window.location="eliminar_remesa1.php?id_remesa="+url;
		}
	}
	</script>
</head>
<body>
<?php
require("menu.php");
//Privilegios Consultar Todo el Modulo
$id_objeto=101; 
include("config/provilegios_modulo.php");  
//---------------------------
?>
<p class="titulo_tab_principal">Planilla de Recepcion</p>
<form name="buscar" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Por No de Despacho</div></td>
    <td class="celda_tabla_principal"><input type="checkbox" name="coincidencia" id="coincidencia" value="1"></td>
    <td colspan="2" class="celda_tabla_principal"><input type="text" name="txtcoincidencia" onKeyDown="activar1();"></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Por Rango de Fecha</div></td>
    <td class="celda_tabla_principal"><input type="checkbox" name="rango" id="rango" value="1"></td>
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
                <img src="imagenes/descargar-act.png" title="Limpiar" /><br />
              <strong>Limpiar</strong>
            </button>
            
            <button name="Buscar" type="submit">
                <img src="imagenes/buscar-act.png" title="Buscar" /><br />
              <strong>Buscar</strong>
            </button>
      </td>
    </tr>
</table>
</form>
<?php
if (isset($_POST["coincidencia"]) or isset($_POST["rango"]))
	{
		$coincidencia=$_POST["txtcoincidencia"];
		$rangoini=$_POST["rangoini"];
		$rangofin=$_POST["rangofin"];
		//carga las remesas por rango de fecha
		if ($coincidencia != "")
			$opcion1="id LIKE '%$coincidencia%'";
			else
			$opcion1="";
		
		if ($rangoini != "" && $rangofin != "")
			$opcion2="fecha BETWEEN '$rangoini' AND '$rangofin'";
			else
			$opcion2="";
		
		if ($opcion1 != "") //chek al numero de guia
			{
				if ($opcion2 != "")
					$condicion=$opcion1." AND ".$opcion2;  ///chek al numero de guia  y chek al rango de fechas
				else
					$condicion=$opcion1; //chek al numero de guia unicamente
			}
			else //numero de guia en blanco
			{
				if ($opcion2 != "") //numero de guia en blanco pero chek al rango de fechas
					$condicion=$opcion2;
					else
						$condicion="Error";
			}
		if	($condicion != "Error")
			{
				$sql="SELECT * FROM remesa WHERE $condicion AND (estado ='A' OR estado ='C')";
				$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$nfilas=mysql_num_rows($consulta);
				if ($nfilas > 0)
				{
				echo '
				<form method="post" id="guardar_datos">
				<table align="center">
				<tr>
					<td colspan="7">
						<div id="respuesta" class="opaco_ie" style="position:relative;opacity:0.3; background-color:#FFF; width:100%; height:30px">
						</div>
					</td>
				</tr>
				<tr>
					<td class="celda_tabla_principal" style="background-color:yellow"><div class="letreros_tabla">No. Planilla</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Remesa</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Ver</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Deposito</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Conductor</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Vehiculo</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
				</tr>';
	
				while($fila=mysql_fetch_array($consulta))
					{
						$cont++;	
						$id_registro=$fila["id"];
						$id_vehiculo=$fila["id_vehiculo"];
						$id_conductor=$fila["id_conductor"];
						$id_deposito=$fila["id_deposito"];
						$fecha=$fila["fecha"];
						$planilla_recepcion=$fila["planilla_recepcion"];
						if ($planilla_recepcion == "")
							$planilla_recepcion="";
						//carga dato adicioenales
						$sql2="SELECT nombre FROM conductor WHERE id='$id_conductor'";
						$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila2=mysql_fetch_array($consulta2);
						$conductor=$fila2['nombre'];
						//carga dato adicioenales
						$sql2="SELECT nombre FROM deposito WHERE id='$id_deposito'";
						$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila2=mysql_fetch_array($consulta2);
						$deposito=$fila2['nombre'];
						
						//Discriminacion de aerolinea de usuario TIPO 5	********************		
						$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
						$sql2="SELECT g.id_aerolinea,c.* FROM guia g LEFT JOIN carga_remesa c ON g.id=c.id_guia WHERE c.id_remesa='$id_registro'";
						$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila2=mysql_fetch_array($consulta2);
						$id_aerolinea=$fila2['id_aerolinea'];
						
						//Verificamos permiso de administrador o permiso de aerolinea
						if ($id_aerolinea_user != "*")
						{
							if($id_aerolinea_user == $id_aerolinea)
							{
								echo "<tr>
										<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
											<input type=\"text\" id=\"planilla_recepcion$cont\" name=\"planilla_recepcion$cont\" value=\"$planilla_recepcion\">
											<input type=\"hidden\" id=\"id_remesa_$cont\" name=\"id_remesa_$cont\" value=\"$id_registro\">
										</td>
										<td class=\"celda_tabla_principal celda_boton\">  $id_registro </td>
										<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
											<button type=\"button\" onClick=\"abrir('pdf_remesa.php?id_remesa=$id_registro')\">
												<img src=\"imagenes/buscar-act.png\" title=\"Buscar\" />
											</button>
										</td>
										<td class=\"celda_tabla_principal celda_boton\">  $deposito </td>
										<td class=\"celda_tabla_principal celda_boton\"> $conductor </td>
										<td class=\"celda_tabla_principal celda_boton\"> $id_vehiculo </td>
										<td class=\"celda_tabla_principal celda_boton\"> $fecha </td>
								</tr>";
							}
						}
						else{
								echo "<tr>
										<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
											<input type=\"text\" id=\"planilla_recepcion$cont\" name=\"planilla_recepcion$cont\" value=\"$planilla_recepcion\">
											<input type=\"hidden\" id=\"id_remesa_$cont\" name=\"id_remesa_$cont\" value=\"$id_registro\">
										</td>
										<td class=\"celda_tabla_principal celda_boton\">  $id_registro </td>										
										<td align=\"center\" class=\"celda_tabla_principal celda_boton\"> 
											<button type=\"button\" onClick=\"abrir('pdf_remesa.php?id_remesa=$id_registro')\">
												<img src=\"imagenes/buscar-act.png\" title=\"Buscar\" />
											</button>
										</td>
										<td class=\"celda_tabla_principal celda_boton\"> $deposito </td>
										<td class=\"celda_tabla_principal celda_boton\"> $conductor </td>
										<td class=\"celda_tabla_principal celda_boton\"> $id_vehiculo </td>
										<td class=\"celda_tabla_principal celda_boton\"> $fecha </td>
								</tr>";							
							}
						//*************************************						
					}
				?>
                <input type="hidden" id="nregistros" name="nregistros" value="<?php echo $cont ?>">
                <table width="450" align="center">
                    <tr>
                      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
                    </tr>
                    <tr>
                      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
                            <button name="Limpiar" type="reset">
                                <img src="imagenes/descargar-act.png" title="Limpiar" />
                            </button>
                            
                            <button name="guardar" id="guardar"  onClick="guardar_form()">
                                <img src="imagenes/guardar-act.png" title="Buscar" />
                            </button>
                      </td>
                    </tr>
                </table>
				</form>
			<?php
			}
			else
				echo "<p align=\"center\"> <font color=\"red\">No se encontraron resultados que coincidan</font></p>";
		}
		else
			echo "<p align=\"center\"> <font color=\"red\">No se encontraron resultados que coincidan</font></p>";
	}
	else
		echo "<p align=\"center\">Seleccione una opci&oacute;n de b&uacute;squeda</p>";
?>
</body>
</html>
<script language="JavaScript">
function guardar_form()
{
	var peticion = new Request(
	{
		url: 'planilla_recepcion_salvar.php',
		method: 'post',
		onRequest: function()
		{
			
			mostrar_div($('respuesta'));
			$('respuesta').innerHTML='<p align="center">Procesando...<image src="imagenes/cargando.gif"></p>';
			$('guardar').disabled=true;
		},			
		onSuccess: function(responseText)
		{
			alert(responseText);
			$('respuesta').innerHTML='<p align="center">Proceso Finalizado</p>';
			document.location='base.php';
			$('guardar').disabled=false;
		},
		onFailure: function()
		{
			$('respuesta').innerHTML='<p align="center">Error al guardar, Intente de nuevo...</p>';
			$('guardar').disabled=false;
		}
	}
	);
	peticion.send($('guardar_datos'));
}

function mostrar_div(id_div)
{
	id_div.set('morph',{ 
	duration: 200, 
	transition: 'linear'
	});
	id_div.morph({
		'opacity': 1 
	});
}
</script>
