<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$totalpiezas=0;
$totalpeso=0;
$totalvolumen=0;
//Discriminacion de aerolinea de usuario TIPO 2
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND id_aerolinea = '$id_aerolinea_user'";	
//*************************************

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
    var respuesta=confirm('ATENCION: Confirma que Desea Eliminar la Guia?');
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
?>
<p class="titulo_tab_principal">Buscador de Guias</p>
<form name="buscar" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
<table align="center">
  <tr>
    <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">Tipo de Guia</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Hija <input type="radio" name="tipo_guia" value="h" checked></div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Master <input type="radio" name="tipo_guia" value="m"></div></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Por No de Guia</div></td>
    <td class="celda_tabla_principal celda_boton"><input type="checkbox" name="coincidencia" id="coincidencia" value="1"></td>
    <td colspan="2" class="celda_tabla_principal"><input type="text" name="txtcoincidencia" onKeyDown="activar1();"></td>
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
		$tguia=$_POST["tipo_guia"];
		if ($tguia=="m")
		{
			$tipo_guia="id_tipo_guia='2'";
			$subtipo="master";
			$habilitacion="disabled";
			$url2="consulta_consolidado2.php";
		}
		else
			{
				$tipo_guia="id_tipo_guia NOT IN (2)";
				$subtipo="hija";
				$habilitacion="";
				$url2="consulta_guia.php";
			}

		
		//carga las remesas por rango de fecha
		if ($coincidencia != "")
			$opcion1="$subtipo LIKE '%$coincidencia%'";
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
				$sql="SELECT * FROM guia WHERE $condicion AND $tipo_guia AND id_tipo_bloqueo $sql_aerolinea";
				$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$nfilas=mysql_num_rows($consulta);
				if ($nfilas > 0)
				{
				echo '
				<table align="center">
				<tr>
					<td class="celda_tabla_principal"><div class="letreros_tabla">...</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Modificar</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Eliminar</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Consolidado</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Vuelo</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Hora</div></td>
				</tr>';

				//Privilegios modificar
				$id_objeto=72; 
				include("config/provilegios_objeto.php");  
				$activacion1=$activacion;
				//Privilegios Eliminar
				$id_objeto=71; 
				include("config/provilegios_objeto.php");  
				$activacion2=$activacion;

				while($fila=mysql_fetch_array($consulta))
					{
						include("config/evaluador_inconsistencias.php");
						$totalpiezas=$totalpiezas+$piezas;
						$totalpeso=$totalpeso+$peso;
						$totalvolumen=$totalvolumen+$volumen;
						$peso=number_format($peso,2,",",".");
						$volumen=number_format($volumen,2,",",".");
						$master=$fila["master"];
						if ($tguia=="h")
						{
							$id_vuelo=$fila["id_vuelo"];
							$hija=$fila["hija"];
							include("config/master.php");
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
						}
						else
							{
								$id_vuelo="*";
								$hija="-";
								$peso="-";
								$volumen="-";
								$piezas="-";
								$nvuelo="-";
							}
						$id_aerolinea=$fila["id_aerolinea"];
						$fecha=$fila["fecha_creacion"];
						$hora=$fila["hora"];

						$id_guia=$fila["id"];
						
						//carga dato adicionales
						$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
						$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila2=mysql_fetch_array($consulta2);
						$aerolinea=$fila2["nombre"];
						
						echo "<tr>
								<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
									<button type=\"button\" onClick=\"document.location='$url2?id_guia=$id_guia'\">
										<img src=\"imagenes/buscar-act.png\" title=\"Consultar\" />
									</button>
								</td>
								<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
									<button type=\"button\" onClick=\"document.location='modificar_guia1.php?id_guia=$id_guia'\" $activacion1>
										<img src=\"imagenes/settings-act.png\" title=\"Modificar\" />
									</button>
								</td>
								<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
									<button type=\"button\" onClick=\"conf_eliminar($id_guia);\" $habilitacion $activacion2>
										<img src=\"imagenes/eliminar-act.png\" title=\"Eliminar\" />
									</button> 
								</td>
								<td class=\"celda_tabla_principal celda_boton\"> $master </td>
								<td class=\"celda_tabla_principal celda_boton\"> $hija </td>
								<td class=\"celda_tabla_principal celda_boton\"> $piezas </td>
								<td class=\"celda_tabla_principal celda_boton\"> $peso </td>
								<td class=\"celda_tabla_principal celda_boton\"> $volumen </td>
								<td class=\"celda_tabla_principal celda_boton\"> $aerolinea </td>
								<td class=\"celda_tabla_principal celda_boton\"> $nvuelo </td>
								<td class=\"celda_tabla_principal celda_boton\"> $fecha </td>
								<td class=\"celda_tabla_principal celda_boton\"> $hora </td>
						</tr>";
					}
				echo "</table>
					<hr>
					<strong>Total de Piezas:</strong>$totalpiezas<br>
					<strong>Total de Peso:</strong>".number_format($totalpeso,2,",",".")."<br>
					<strong>Total de Volumen:</strong>".number_format($totalvolumen,2,",",".")."<br>					
					";
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
