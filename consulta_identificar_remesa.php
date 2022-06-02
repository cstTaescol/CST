<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<html>
<head>
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
?>
<p class="titulo_tab_principal">Buscador de Remesas</p>
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
				echo "<table align=\"center\">
				<tr>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Ver</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Modificar</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Eliminar</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Cumplido</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">No.</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Deposito</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Conductor</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Vehiculo</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Fecha</div></td>
				</tr>";
				//Privilegios modificar
				$id_objeto=90; 
				include("config/provilegios_objeto.php");  
				$activacion1=$activacion;
				//Privilegios Eliminar
				$id_objeto=91; 
				include("config/provilegios_objeto.php");  
				$activacion2=$activacion;
		
				while($fila=mysql_fetch_array($consulta))
					{
						$id_remesa=$fila["id"];
						$id_vehiculo=$fila["id_vehiculo"];
						$id_conductor=$fila["id_conductor"];
						$id_deposito=$fila["id_deposito"];
						$fecha=$fila["fecha"];
						$foto_cumplido=$fila["foto_cumplido"];
						if ($foto_cumplido == "")
							$foto_cumplido="imagen_no_disponible.jpg";
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
						$sql2="SELECT g.id_aerolinea,c.* FROM guia g LEFT JOIN carga_remesa c ON g.id=c.id_guia WHERE c.id_remesa='$id_remesa'";
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
											<button onClick=\"abrir('pdf_remesa.php?id_remesa=$id_remesa')\">
												<img src=\"imagenes/imprimir-act.png\" title=\"Impresion\" />
											</button> 
										</td>
										<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
											<button onClick=\"document.location='modificar_remesa1.php?id_remesa=$id_remesa'\" $activacion1>
												<img src=\"imagenes/settings-act.png\" title=\"Modificar\" />
											</button> 
										</td>
										<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
											<button onClick=\"conf_eliminar($id_remesa);\" $activacion2>
												<img src=\"imagenes/eliminar-act.png\" title=\"Eliminar\" />
											</button> 
										</td>
										<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
											<button onClick=\"abrir('fotos/cumplidos/$foto_cumplido')\">
												<img src=\"imagenes/editar-act.png\" title=\"Consultar Cumplido\" />
											</button> 
										</td>
										<td class=\"celda_tabla_principal celda_boton\"> $id_remesa </td>
										<td class=\"celda_tabla_principal celda_boton\"> $deposito </td>
										<td class=\"celda_tabla_principal celda_boton\"> $conductor </td>
										<td class=\"celda_tabla_principal celda_boton\"> $id_vehiculo </td>
										<td class=\"celda_tabla_principal celda_boton\"> $fecha </td>
								</tr>";
							}
						}
						else{
								echo "<tr>
										<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
											<button onClick=\"abrir('pdf_remesa.php?id_remesa=$id_remesa')\">
												<img src=\"imagenes/imprimir-act.png\" title=\"Impresion\" />
											</button>  										
										</td>
										<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
											<button onClick=\"document.location='modificar_remesa1.php?id_remesa=$id_remesa'\" $activacion1>
												<img src=\"imagenes/settings-act.png\" title=\"Modificar\" />
											</button> 
										</td>
										<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
											<button onClick=\"conf_eliminar($id_remesa);\" $activacion2>
												<img src=\"imagenes/eliminar-act.png\" title=\"Eliminar\" />
											</button> 
										</td>
										<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
											<button onClick=\"abrir('fotos/cumplidos/$foto_cumplido')\">
												<img src=\"imagenes/editar-act.png\" title=\"Consultar Cumplido\" />
											</button> 
										</td>
										<td class=\"celda_tabla_principal celda_boton\"> $id_remesa </td>
										<td class=\"celda_tabla_principal celda_boton\"> $deposito </td>
										<td class=\"celda_tabla_principal celda_boton\"> $conductor </td>
										<td class=\"celda_tabla_principal celda_boton\"> $id_vehiculo </td>
										<td class=\"celda_tabla_principal celda_boton\"> $fecha </td>
								</tr>";							
							}
						//*************************************
					}
				echo "</table>";
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
