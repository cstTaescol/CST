<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$totalpiezas=0;
$totalpeso=0;
$totalvolumen=0;
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
// funcion para validar
function validar()
{	
	if (document.forms[0].rangoini.value=="")
	{
		alert("Atencion: Se requiere una FECHA INICIAL para el reporte");
		document.forms[0].lanzador.focus();
		return(false);
	}

	if (document.forms[0].rangofin.value=="")
	{
		alert("Atencion: Se requiere una FECHA FINAL para el reporte");
		document.forms[0].lanzador2.focus();
		return(false);
	}
}
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Reporte Transportador</p>
<form name="buscar" method="post" action="reportes/reporte_transportador.php" onsubmit="return validar();" target="_blank">
      <table align="center">
        <tr>
          <td class="celda_tabla_principal"><div class="letreros_tabla">Transportador</div></td>
          <td colspan="2" class="celda_tabla_principal"> 
             <select name="id_transportador" id="id_transportador" tabindex="1">
                <option value="*">TODOS</option>
                <?php
					$sql="SELECT id,nombre FROM transportador WHERE estado='A' ORDER BY nombre ASC";
					$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
					while($fila=mysql_fetch_array($consulta))
					{
						echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
					}
    	        ?>
	          </select>
          </td>
          </tr>
        <tr>
             <td class="celda_tabla_principal"><div class="letreros_tabla">Por Rango de Fecha</div></td>
             <td width="250px" class="celda_tabla_principal celda_boton">
                <div class="asterisco">Desde</div>
                <input name="rangoini" type="text" id="rangoini" size="10" readonly="readonly"/>
                <input type="button" id="lanzador" value="..." onfocus="activar2()" tabindex="4"/>
                <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
                <!-- script que define y configura el calendario-->
                <script type="text/javascript">
                Calendar.setup({
                    inputField     :    "rangoini",      // id del campo de texto
                    ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                    button         :    "lanzador"   // el id del botón que lanzará el calendario
                });
                document.getElementById('lanzador').focus();
            	</script>
            </td>
            <td width="250px" class="celda_tabla_principal celda_boton">
                <div class="asterisco">Hasta</div>
                <input name="rangofin" type="text" id="rangofin" size="10" readonly="readonly"/>
                <input type="button" id="lanzador2" value="..." onfocus="activar2()" tabindex="5"/>
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
            <button type="button" tabindex="14" onclick="document.location='consulta_reportes.php'">
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
</body>
</html>
