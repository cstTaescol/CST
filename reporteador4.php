<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$totalpiezas=0;
$totalpeso=0;
$totalvolumen=0;

//Evaluación de tipo de reporte
if(isset($_REQUEST['tipo'])){
  $tipoReporte=$_REQUEST['tipo'];
}
else{
  $tipoReporte='F';
}

switch ($tipoReporte) {
  case 'F':
    $title='Reporte Facturaci&oacute;n';
    $action='reportes/reporte_facturacion.php';
  break;
 case 'D':
    $title='Reporte De Despachos';
    $action='reportes/reporte_despachos.php';
  break;

}


//Discriminacion de aerolinea de usuario  TIPO 1
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND id = '$id_aerolinea_user'";	
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
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal"><?php echo $title; ?></p>
<form name="buscar" method="post" action="<?php echo $action; ?>" onsubmit="return validar();" target="_blank">
  <table align="center" cellpadding="0" cellspacing="0" style="width:650px" >
    <tr>
      <td>
      <p>&nbsp;</p>
      <table align="center" style="width:650px">
        <tr>
              <td width="150" class="celda_tabla_principal"><div class="letreros_tabla">Rango de Fecha</div></td>
              <td width="250px" class="celda_tabla_principal celda_boton">
                    <div class="asterisco">Desde</div>
                    <input name="rangoini" type="text" id="rangoini" size="10" readonly="readonly"/>
                    <input type="button" id="lanzador" value="..." tabindex="4"/>
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
                <input type="button" id="lanzador2" value="..." tabindex="5"/>
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
        <tr>
          <td class="celda_tabla_principal"><div class="letreros_tabla">Aeroliena</div></td>
          <td colspan="2" bgcolor="#FFFFFF">
          <select name="id_aerolinea" id="id_aerolinea" tabindex="6">
            <?php
      				if ($id_aerolinea_user == "*"){
      				  echo '<option value="*">TODAS</option>';	
              }
              $sql="SELECT id,nombre FROM aerolinea WHERE estado='A' AND importacion = TRUE $sql_aerolinea ORDER BY nombre ASC";      				      				
      				$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
      				while($fila=mysql_fetch_array($consulta))
      				{
      					echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
      				}
      			?>
          </select>
          </td>
        </tr>
      </table>
      </td>
    </tr>
  </table>
<div id="menuguardar" style="position:relative; width:400px; top:10px;  margin-left: auto;margin-right: auto;">
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
</div>

</form>						
<p>&nbsp;</p>
</body>
</html>
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

