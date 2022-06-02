<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$totalpiezas=0;
$totalpeso=0;
$totalvolumen=0;
$total_facturado=0;
$cont=0;
$buffer_master="i";
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
?>
<p class="titulo_tab_principal">Buscador de Guias</p>
<form name="buscar" method="post" action="consulta_guias_facturadas.php">
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Por No de Guia</div></td>
    <td class="celda_tabla_principal"><input type="checkbox" name="coincidencia" id="coincidencia" value="1"></td>
    <td colspan="2" class="celda_tabla_principal"><input type="text" name="txtcoincidencia" onKeyDown="activar1();"></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Por fecha de FACTURACION</div></td>
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
		$tipo_guia="id_tipo_guia NOT IN (2)";
		$habilitacion="";
		$url2="consulta_guia.php";
		
		//carga las remesas por rango de fecha
		if ($coincidencia != "")
			$opcion1="hija LIKE '%$coincidencia%'";
			else
			$opcion1="";
		
		if ($rangoini != "" && $rangofin != "")
			$opcion2="fecha_factura BETWEEN '$rangoini' AND '$rangofin'";
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
				$sql="SELECT * FROM guia WHERE $condicion AND id_tipo_guia !='2' AND nfactura IS NOT NULL ORDER BY master ASC";
				$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$nfilas=mysql_num_rows($consulta);
				if ($nfilas > 0)
				{
				echo "<table align=\"center\">
				<tr>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">...</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Consolidado</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Guia</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Piezas</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Peso</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Aerolinea</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Vuelo</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Fecha Facturado</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Facturado A</div></td>
					<td class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Valor</div></td>
				</tr>";
		
				while($fila=mysql_fetch_array($consulta))
					{
						include("config/evaluador_inconsistencias.php");
						$totalpiezas=$totalpiezas+$piezas;
						$totalpeso=$totalpeso+$peso;
						$totalvolumen=$totalvolumen+$volumen;
						$peso=number_format($peso,2,",",".");
						$volumen=number_format($volumen,2,",",".");
						$fecha=$fila["fecha_factura"];
						$id_guia=$fila["id"];
						$hija=$fila["hija"];
						$master=$fila["master"];
						$facturadoa=$fila["facturadoa"];
						if ($facturadoa == "")
						{
							//carga dato adicionales
							$id_consignatario=$fila["id_consignatario"];
							$sql2="SELECT nombre FROM consignatario WHERE id='$id_consignatario'";
							$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
							$fila2=mysql_fetch_array($consulta2);
							$facturadoa=$fila2['nombre'];							
						}
						
						$id_tipo_guia=$fila["id_tipo_guia"];
						if ($id_tipo_guia == 1 or $id_tipo_guia == 4) //GUIAS DIRECTAS Y DE CORREO
						{
							$valor_factura=$fila["valor_factura"];
							$total_facturado=$total_facturado+$valor_factura;
							$valor_factura=number_format($valor_factura,0,",",".");

						}
						
						if ($id_tipo_guia == 3) //GUIAS HIJA
						{
							$valor_factura=$fila["valor_factura"];
							$valor_factura="M=".number_format($valor_factura,0,",",".");
							if ($buffer_master == $master)
								$total_facturado=$total_facturado+$valor_factura;
							//echo "$buffer_master == $master";
						}
						$buffer_master=$master;
						include("config/master.php");

						//carga dato adicionales
						$id_vuelo=$fila["id_vuelo"];
						$sql2="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
						$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila2=mysql_fetch_array($consulta2);
						$nvuelo=$fila2['nvuelo'];
						
						//carga dato adicionales
						$id_aerolinea=$fila["id_aerolinea"];
						$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
						$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila2=mysql_fetch_array($consulta2);
						$aerolinea=$fila2["nombre"];
						
						echo "<tr>
								<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
									<button onClick=\"document.location='$url2?id_guia=$id_guia'\">
										<img src=\"imagenes/buscar-act.png\" title=\"Descripcion\" />		
									</button> 
								</td>
								<td class=\"celda_tabla_principal celda_boton\"> $master </td>
								<td class=\"celda_tabla_principal celda_boton\"> $hija </td>
								<td align=\"right\" class=\"celda_tabla_principal celda_boton\"> $piezas </td>
								<td align=\"right\" class=\"celda_tabla_principal celda_boton\"> $peso </td>
								<td class=\"celda_tabla_principal celda_boton\"> $aerolinea </td>
								<td class=\"celda_tabla_principal celda_boton\"> $nvuelo </td>
								<td class=\"celda_tabla_principal celda_boton\"> $fecha </td>
								<td class=\"celda_tabla_principal celda_boton\"> $facturadoa </td>
								<td align=\"right\" class=\"celda_tabla_principal celda_boton\"> $valor_factura </td>
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
