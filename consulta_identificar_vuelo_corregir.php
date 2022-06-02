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
<!-- función que permite abrir ventanas emergentes con las propiedades deseadas -->
function openPopup(url,name,w,h,props,center){
	l=18;t=18
	if(center){l=(screen.availWidth-w)/2;t=(screen.availHeight-h)/2}
	url=url.replace(/[ ]/g,'%20')
	popup=window.open(url,name,'left='+l+',top='+t+',width='+w+',height='+h+',scrollbars=1'+((props)?','+props:''))
	props=props||''
	if(props.indexOf('fullscreen')!=-1){popup.moveTo(0,0);popup.resizeTo(screen.width,screen.height)}
	popup.focus()
}
</script>


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

</script>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Buscador de Vuelos</p>
<form name="buscar" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
<input type="hidden" name="tipo" id="tipo" value="<?php echo $_REQUEST['tipo']; ?>">
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Vuelo</div></td>
    <td class="celda_tabla_principal"><input type="checkbox" name="coincidencia" id="coincidencia" value="1" tabindex="1"></td>
    <td colspan="2" class="celda_tabla_principal celda_boton"><input type="text" name="txtcoincidencia" onKeyDown="activar1();" tabindex="2"></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Por Rango de Fecha</div></td>
    <td class="celda_tabla_principal"><input type="checkbox" name="rango" id="rango" value="1" tabindex="3"></td>
    <td width="250px" class="celda_tabla_principal celda_boton">
		<div class="asterisco">Desde</div>
        <input name="rangoini" type="text" id="rangoini" size="10" readonly/>
        <input type="button" id="lanzador" value="..." onFocus="activar2();" onMouseUp="activar2();" tabindex="4" />
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
      <input name="rangofin" type="text" id="rangofin" size="10" readonly/>
      <input type="button" id="lanzador2" value="..." onFocus="activar2();" onMouseUp="activar2();" tabindex="5" />
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
</table>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
        
        <button type="button" tabindex="14" onClick="document.location='correcciones.php'">
            <img src="imagenes/al_principio-act.png" title="Atras" />
        </button>
        <button type="reset" tabindex="13">
            <img src="imagenes/descargar-act.png" title="Limpiar" />
        </button>
        <button type="submit" tabindex="12">
            <img src="imagenes/al_final-act.png" title="Continuar" />
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
		$tipo=$_POST["tipo"];
		
		//carga las remesas por rango de fecha
		if ($coincidencia != "")
			$opcion1="nvuelo LIKE '%$coincidencia%'";
			else
			$opcion1="";
		
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
				
				$parametroadicional="";
				//Direccionamiento a al desarrollo especifico por el tipo de opcion recibida
				switch($tipo)
				{
					case "volumen":
						$archivo_destino='correccion_vuelo_volumen.php';
					break;

					case "addGuia":
						$archivo_destino='guia_registro.php';		
						$parametroadicional='&addGuia=true';				
					break;
				}
							
				$sql="SELECT * FROM vuelo WHERE $condicion $sql_aerolinea ORDER BY fecha_creacion DESC";
				$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$nfilas=mysql_num_rows($consulta);
				if ($nfilas > 0)
				{
				echo '<table align="center" >
				<tr>
					<td class="celda_tabla_principal"><div class="letreros_tabla">No. Vuelo</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Revisar</div></td>
				</tr>';
		
				while($fila=mysql_fetch_array($consulta))
					{
						$nvuelo=$fila["nvuelo"];
						$id_vuelo=$fila["id"];
						$id_aerolinea=$fila["id_aerolinea"];
						$fecha_creacion=$fila["fecha_creacion"];
						//carga dato adicioenales
						$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
						$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila2=mysql_fetch_array($consulta2);
						$aerolinea=$fila2['nombre'];
						echo '<tr>
								<td class="celda_tabla_principal celda_boton">'. $nvuelo .'</td>
								<td class="celda_tabla_principal celda_boton">'. $fecha_creacion .'</td>
								<td class="celda_tabla_principal celda_boton">'. $aerolinea .'</td>
								<td align="center"  class="celda_tabla_principal celda_boton" > 
									<button type="button" 
										onclick="document.location=\''.$archivo_destino.'?id_vuelo='.$id_vuelo.'&aerolinea='.$id_aerolinea.$parametroadicional.'\'">
										<img src="imagenes/buscar-act.png" title="Ver" />
									</button>								
								</td>
							</tr>';
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
