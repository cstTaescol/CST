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
	document.getElementById('coincidencia').checked=true;
}
function activar2()
{
	document.getElementById('rango').checked=true;
}

//Funcion para confirmar la eliminacion de una remesa
function conf_eliminar(url)
{
var respuesta=confirm('ATENCION: Confirma que ¿Desea Eliminar el DESPACHO?,  Las GUIAS despachadas seran Retornadas al INVENTARIO');
if (respuesta)
	{
		window.location="eliminar_hall1.php?id_registro="+url;
	}
}
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p align="center"><font color="#0033CC" size="+2"><strong>CONSULTA DE DESPACHOS HALL </strong></font><img src="imagenes/buscar.png" width="30" height="30" align="absmiddle"></p>
<form name="buscar" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
<table width="70%" border="1" align="center" cellspacing="0" bordercolorlight="#000000">
  <tr>
    <td width="33%">Por Coincidencia de No.</td>
    <td width="3%"><input type="checkbox" name="coincidencia" id="coincidencia" value="1" tabindex="1"></td>
    <td colspan="2"><input type="text" name="txtcoincidencia" onKeyDown="activar1();" tabindex="2"></td>
  </tr>
  <tr>
    <td>Por Rango de fecha</td>
    <td><input type="checkbox" name="rango" id="rango" value="1" tabindex="3"></td>
    <td width="37%" bgcolor="#99CCCC">
    Desde 
    <input name="rangoini" type="text" id="rangoini" size="10" readonly="readonly"/>
      <input type="button" id="lanzador" value="..." onFocus="activar2();" tabindex="4"/>
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
    <td width="27%" bgcolor="#FFCC99">Hasta
      <input name="rangofin" type="text" id="rangofin" size="10" readonly="readonly"/>
      <input type="button" id="lanzador2" value="..." onFocus="activar2();" tabindex="5"/>
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
<table width="70%" border="1" align="center" cellspacing="0" bordercolorlight="#000000">
  <tr>
    <td width="100%" align="center" >
     <button type="reset" name="cancelar" tabindex="7">
    	<img src="imagenes/limpiar.jpg" width="50" ><br>
    	<strong>Cancelar</strong>
     </button>
    <button type="submit" name="enviar" tabindex="6">
    	<img src="imagenes/lupa.jpg" width="59" ><br>
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
				$sql="SELECT * FROM hall WHERE $condicion AND estado ='A'";
				$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$nfilas=mysql_num_rows($consulta);
				if ($nfilas > 0)
				{
				echo "<table align=\"center\" border=\"1\" bordercolor=\"#0000FF\" cellspacing=\"8\" cellpadding=\"2\">";
				echo "
				<tr>
					<td bgcolor=\"#66CCFF\"><strong>Info</strong></td>
					<td bgcolor=\"green\"><strong>Modificar</strong></td>
					<td bgcolor=\"red\"><strong>Eliminar</strong></td>
					<td bgcolor=\"#99CCCC\" align=\"center\"><strong>GUIA</strong></td>
					<td bgcolor=\"#99CCCC\" align=\"center\"><strong>AEROLINEA</strong></td>
					<td bgcolor=\"#99CCCC\" align=\"center\"><strong>FECHA</strong></td>
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
						$id_guia=$fila["id_guia"];
						$id_registro=$fila["id"];
						$fecha=$fila["fecha"];
						//carga dato adicioenales
						$sql2="SELECT g.hija, a.nombre FROM guia g LEFT JOIN aerolinea a ON g.id_aerolinea = a.id  WHERE g.id='$id_guia' $sql_aerolinea";
						$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila2=mysql_fetch_array($consulta2);
						$guia=$fila2['hija'];
						$aerolinea=$fila2['nombre'];
						if ($guia != "")
						{
							echo "<tr>
									<td align=\"center\"><button onClick=\"abrir('pdf_hall.php?id_registro=$id_registro')\">i</button> </td>
									<td align=\"center\"><button onClick=\"document.location='modificar_hall1.php?id_registro=$id_registro'\" $activacion1>O</button> </td>
									<td align=\"center\"><button  onClick=\"conf_eliminar($id_registro);\" $activacion2>X</button> </td>								
									<td> $guia </td>
									<td> $aerolinea </td>
									<td> $fecha </td>
							</tr>";
						}
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
