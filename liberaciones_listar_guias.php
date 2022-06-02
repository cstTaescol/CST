<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
//Discriminacion de aerolinea de usuario TIPO 2
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND id_aerolinea = '$id_aerolinea_user'";	
//*************************************

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

//Funcion para confirmar la eliminacion de una guia
function conf_eliminar(url)
{
var respuesta=confirm('ATENCION: Confirma que ¿Desea Eliminar la Guia?');
if (respuesta)
	{
		window.location="eliminar_guia1.php?id_guia="+url;
	}
}
</script>

</head>
<body>
<?php
require("menu.php");
//Privilegios Consultar Todo el Modulo
$id_objeto=80; 
include("config/provilegios_modulo.php");  
//---------------------------

?>
<p class="titulo_tab_principal">Liberaciones</p>
<form name="buscar" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
<table width="750" align="center" class="celda_tabla_principal">
  <tr>
    <td colspan="2">
    	<div class="letreros_tabla asterisco">
        	<img src="imagenes/1.jpg" width="186" height="67" style="border-radius: 15px;" align="absmiddle" /> 
            PASO 1, Seleccione el tipo de Guia
        </div>
    </td>
  </tr>
  <tr>
    <td><div class="letreros_tabla" style="text-align:right">Consolidado</div></td>
    <td class="celda_tabla_principal"><input type="radio" name="tipo_guia" value="2" checked></td>
  </tr>
  <tr>
    <td><div class="letreros_tabla" style="text-align:right">Directa / Corrreo</div></td>
    <td class="celda_tabla_principal"><input type="radio" name="tipo_guia" value="0"></td>
  </tr>
</table>
<hr>
<table width="750" align="center" class="celda_tabla_principal">
  <tr>
    <td><img src="imagenes/2.jpg" width="186" height="67" style="border-radius: 15px;" align="absmiddle" /></td>
    <td>
    	<div class="letreros_tabla asterisco" style="text-align:left">
            PASO 2, Identifique el numero de Guia
        </div>
	</td>
  </tr>
</table>
<br>
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Por No de Guia</div></td>
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
		$tipo_guia=$_POST["tipo_guia"];
		if ($tipo_guia==2)
		{
			$tipo_guia="id_tipo_guia='2'";
			$subtipo="master";
			$detino_consulta="consulta_consolidado2.php";
		}
		else
			{
				$tipo_guia="id_tipo_guia NOT IN (2,3)";
				$subtipo="hija";
				$detino_consulta="consulta_guia.php";
			}
		
		//carga las remesas por coincidencia
		if ($coincidencia != "")
			$opcion1="$subtipo LIKE '%$coincidencia%'";
			else
			$opcion1="";
			
		//carga las remesas por rango de fecha
		if ($rangoini != "" && $rangofin != "")
			$opcion2="fecha_creacion BETWEEN '$rangoini' AND '$rangofin'";
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
				$sql="SELECT * FROM guia WHERE $condicion $sql_aerolinea AND $tipo_guia AND id_tipo_bloqueo !='8'";
				$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$nfilas=mysql_num_rows($consulta);
				if ($nfilas > 0)
				{
				echo '<table align="center">
				<tr>
					<td class="celda_tabla_principal" colspan="10" ><div class="letreros_tabla">Facturaci&oacute;n</div></td>
				</tr>
				<tr>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Consolidado</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Hija</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Consignatario</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Hora</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Ver Guia</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Facturado</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Agregar</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Modificar</div></td>

				</tr>';
		
				while($fila=mysql_fetch_array($consulta))
					{
						$id_vuelo=$fila["id_vuelo"];
						$id_aerolinea=$fila["id_aerolinea"];
						$fecha=$fila["fecha_creacion"];
						$hora=$fila["hora"];
						$consignatario=$fila["id_consignatario"];
						
						$master=$fila["master"];
						if ($master == "") 
							$master="-";
						$hija=$fila["hija"];

						if ($hija == "") 
							$hija="...";
						
						
						$id_guia=$fila["id"];
						
						if ($id_vuelo !="")
						{
							//carga dato adicionales
							$sql2="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
							$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
							$fila2=mysql_fetch_array($consulta2);
							$nvuelo=$fila2['nvuelo'];
						}
						else
							{
								$nvuelo="NO ASIGNADO";
							}
						
						
						//control de alerta de facturas creadas
						//24/08/2020
						$sql2="SELECT nfactura FROM guia_factura WHERE id_guia='$id_guia' AND estado='A'";
						$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$nfacturas=0;
						$nfacturas=mysql_num_rows($consulta2);
						if ($nfacturas != 0){
							$facturado = '<img src="imagenes/check_green.png" height="29" width="32" title="Ya Tiene Facturas Asociadas" />';		
						}
						else{
							$facturado = "No";								
						}

						//carga dato adicionales
						$sql2="SELECT nombre FROM consignatario WHERE id='$consignatario'";
						$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila2=mysql_fetch_array($consulta2);
						$consignatario=$fila2["nombre"];
						
						//carga dato adicionales
						$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
						$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila2=mysql_fetch_array($consulta2);
						$aerolinea=$fila2["nombre"];
						
						echo "<tr>
									<td class=\"celda_tabla_principal celda_boton\"> $master </td>
									<td class=\"celda_tabla_principal celda_boton\"> $hija </td>
									<td class=\"celda_tabla_principal celda_boton\"> $consignatario </td>
									<td class=\"celda_tabla_principal celda_boton\"> $aerolinea </td>
									<td class=\"celda_tabla_principal celda_boton\"> $fecha </td>
									<td class=\"celda_tabla_principal celda_boton\"> $hora </td>
									<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
										<button type=\"button\" onClick=\"document.location='$detino_consulta?id_guia=$id_guia'\">
											<img src=\"imagenes/buscar-act.png\" title=\"Consultar la informacion de la Guia\">
										</button> 
									</td>
									<td align=\"center\" class=\"celda_tabla_principal celda_boton\"> $facturado </td>
									<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
										<button onClick=\"document.location='liberaciones_crear_factura.php?id_guia=$id_guia&subtipo=$subtipo'\">
											<img src=\"imagenes/agregar-act.png\" title=\"Adicionar una Factua a esta Guia\">
										</button> 
									</td>
									<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
										<button onClick=\"document.location='liberaciones_listar_factura.php?id_guia=$id_guia&subtipo=$subtipo'\">
											<img src=\"imagenes/editar-act.png\" title=\"Modificar o Eliminar una Factur de esta Guia\">
										</button> 
									</td>																						
							   </tr>";
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
