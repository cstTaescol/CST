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
<p class="titulo_tab_principal">Buscador de Entregas Courier</p>
<form name="buscar" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
<table align="center">
  <tr>
    <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">Tipo</div></td>
    <td class="celda_tabla_principal" colspan="2"><div class="letreros_tabla">Entrega <input type="radio" name="tipo_guia" value="m" checked="checked"></div></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Por No de Entrega</div></td>
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

		
		//carga las remesas por rango de fecha
		if ($coincidencia != "")
			$opcion1="id LIKE '%$coincidencia%'";
			else
			$opcion1="";
		
		if ($rangoini != "" && $rangofin != "")
			$opcion2="fecha BETWEEN '$rangoini' AND '$rangofin'";
			else
			$opcion2="";
		
		if ($opcion1 != "") //chek al numero de registro
			{
				if ($opcion2 != "")
					$condicion=$opcion1." AND ".$opcion2;  ///chek al numero de registro  y chek al rango de fechas
				else
					$condicion=$opcion1; //chek al numero de registro unicamente
			}
			else //numero de registro en blanco
			{
				if ($opcion2 != "") //numero de registro en blanco pero chek al rango de fechas
					$condicion=$opcion2;
					else
						$condicion="Error";
			}
		if	($condicion != "Error")
			{				
				$sql="SELECT id,piezas,peso,id_guia,fecha,hora FROM courier_despacho WHERE $condicion ORDER BY fecha DESC";
				$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$nfilas=mysql_num_rows($consulta);
				if ($nfilas > 0)
				{
				echo '
				<table align="center">
					<tr>						
						<td class="celda_tabla_principal"><div class="letreros_tabla">Despacho</div></td>					
						<td class="celda_tabla_principal"><div class="letreros_tabla">No Despacho</div></td>						
						<td class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>					
						<td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
						<td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
						<td class="celda_tabla_principal"><div class="letreros_tabla">Courier</div></td>					
						<td class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
						<td class="celda_tabla_principal"><div class="letreros_tabla">Hora</div></td>
					</tr>';

				while($fila=mysql_fetch_array($consulta))
					{						
						$piezas=$fila["piezas"];						
						$peso=number_format($fila["peso"],1,",",".");						
						$fecha=$fila["fecha"];
						$hora=$fila["hora"];
						$id_guia=$fila["id_guia"];
						$id_registro=$fila["id"];

						// identificando despacho		
						$sql3="SELECT master,id_consignatario FROM guia  WHERE id ='$id_guia'";
						$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila3=mysql_fetch_array($consulta3);
						$master=$fila3["master"];	
						$id_courier=$fila3["id_consignatario"];
						
						//carga dato adicionales
						$sql2="SELECT nombre FROM couriers WHERE id='$id_courier'";
						$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila2=mysql_fetch_array($consulta2);
						$consignatario=$fila2["nombre"];
						
						echo '<tr>
								<td align="center" class="celda_tabla_principal celda_boton">
									<button type="button" onClick="document.location=\'courier_despacho_opciones.php?id_registro='.$id_registro.'\'">
										<img src="imagenes/imprimir-act.png" title="Consultar" />
									</button>
								</td>		
								<td class="celda_tabla_principal celda_boton" align="right"> '.$id_registro.' </td>
								<td class="celda_tabla_principal celda_boton"> '.$master.' </td>
								<td class="celda_tabla_principal celda_boton" align="right"> '.$piezas.' </td>
								<td class="celda_tabla_principal celda_boton" align="right"> '.$peso.' </td>
								<td class="celda_tabla_principal celda_boton"> '.$consignatario.' </td>
								<td class="celda_tabla_principal celda_boton"> '.$fecha.' </td>
								<td class="celda_tabla_principal celda_boton"> '.$hora.' </td>
							</tr>';
					}
				echo "</table>";
			}
			else
				echo '<p align="center"> <font color="red">No se encontraron resultados que coincidan</font></p>';
		}
		else
			echo '<p align="center"> <font color="red">No se encontraron resultados que coincidan</font></p>';
	}
	else
		echo '<p align="center">Seleccione una opci&oacute;n de b&uacute;squeda</p>';
?>
</body>

</html>
