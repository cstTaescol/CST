<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

$id_guia=$_GET['id_guia'];
$subtipo=$_GET['subtipo'];
$cont=0;
$totalpiezas=0;
$totalpeso=0;
$totalvolumen=0;
$resultados1="";
if ($subtipo == "master")
{
	$sql="SELECT hija, piezas, peso, volumen, piezas_inconsistencia, peso_inconsistencia, volumen_inconsistencia FROM guia WHERE master='$id_guia'";
}
else
{
	$sql="SELECT hija, piezas, peso, volumen, piezas_inconsistencia, peso_inconsistencia, volumen_inconsistencia FROM guia WHERE id='$id_guia'";
}

$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila=mysql_fetch_array($consulta))
{
	$cont++;
	include("config/evaluador_inconsistencias.php");
	$hija=$fila["hija"];
	$totalpiezas=$totalpiezas+$piezas;
	$totalpeso=$totalpeso+$peso;
	$totalvolumen=$totalvolumen+$volumen;
	$peso=number_format($peso,1,",",".");
	$resultados1=$resultados1.'
	<tr>
		<td class="celda_tabla_principal celda_boton">'.$cont.'</td>
		<td class="celda_tabla_principal celda_boton">'.$hija.'</td>
		<td class="celda_tabla_principal celda_boton">'.$piezas.'</td>
		<td class="celda_tabla_principal celda_boton">'.$peso.'</td>
	</td>
	';
}
	
	
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
//Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 
// funcion para validar
function validar()
{	
	if (document.forms[0].fecha.value=="")
	{
		alert("Atencion: Se requiere la FECHA de la FACTURA");
		document.forms[0].lanzador.focus();
		return(false);
	}
	if (document.forms[0].nfactura.value=="")
	{
		alert("Atencion: Se requiere el NUMERO de la FACTURA");
		document.forms[0].nfactura.focus();
		return(false);
	}
	if (document.forms[0].valor.value=="")
	{
		alert("Atencion: Se requiere el VALOR de la FACTURA");
		document.forms[0].valor.focus();
		return(false);
	}	

	if (document.forms[0].iva.value=="")
	{
		alert("Atencion: Se requiere el VALOR del IVA");
		document.forms[0].iva.focus();
		return(false);
	}	
}
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Liberaciones</p>
<form name="liberaciones" method="post" action="liberaciones_guardar_factura.php" onSubmit="return validar();">
		<table width="400" align="center" >
		  <tr>
		  	<td colspan="2"><span class="asterisco">Ingrese los datos relacionados a la Facturaci&oacute;n de esta Gu&iacute;a. </span></td>
		  </tr>
		  <tr>
			<td class="celda_tabla_principal"><div class="letreros_tabla">Fecha Factura</div></td>
			<td class="celda_tabla_principal celda_boton">
            	<input type="hidden" name="subtipo" value="<?php echo $subtipo ?>">
                <input type="hidden" name="metodo" value="crear">
				<input name="fecha" type="text" id="fecha" size="10" readonly="readonly" tabindex="8"/>
				<input type="button" id="lanzador" value="..." tabindex="1"/>
                <span class="asterisco">(*)	</span>              <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
				<!-- script que define y configura el calendario-->
				<script type="text/javascript">
					Calendar.setup({
						inputField     :    "fecha",      // id del campo de texto
						ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
						button         :    "lanzador"   // el id del botón que lanzará el calendario
					});
				</script>			
			<script>document.forms[0].lanzador.focus();</script>			</td>
		  </tr>
		  <tr>
			<td class="celda_tabla_principal"><div class="letreros_tabla">No. Factura</div></td>
			<td class="celda_tabla_principal celda_boton"><input name="nfactura" type="text" id="nfactura" tabindex="2" maxlength="10">
		    <span class="asterisco">(*)</span><input type="hidden" name="id_guia" value="<?php echo $id_guia ?>"></td>
		  </tr>
		  <tr>
			<td class="celda_tabla_principal"><div class="letreros_tabla">Valor</div></td>
			<td class="celda_tabla_principal celda_boton"><input name="valor" type="text" id="valor" tabindex="3" onKeyPress="return numeric(event)" maxlength="10">
		    <span class="asterisco">(*)</span></td>
		  </tr>
		  <tr>
			<td class="celda_tabla_principal"><div class="letreros_tabla">I.V.A.</div></td>
			<td class="celda_tabla_principal celda_boton"><input name="iva" type="text" id="iva" tabindex="3" onKeyPress="return numeric(event)" maxlength="10">
		    <span class="asterisco">(*)</span></td>
		  </tr>
          
		  <tr>
		    <td class="celda_tabla_principal"><div class="letreros_tabla">Facturado a:</div></td>
		    <td class="celda_tabla_principal celda_boton"><input name="facturadoa" type="text" id="facturadoa" tabindex="4" maxlength="50"></td>
	      </tr>
	  </table>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
                <button name="Limpiar" type="reset" tabindex="6">
                    <img src="imagenes/descargar-act.png" title="Limpiar" />
                </button>
                
                <button name="Buscar" type="submit" tabindex="5">
                    <img src="imagenes/guardar-act.png" title="Guardar" />
                </button>
          </td>
        </tr>
    </table>     
</form>
<p align="center" class="asterisco">Gu&iacute;as Asociadas:</p>
<table width="470" align="center">
  <tr>
    <td width="33" class="celda_tabla_principal"><div class="letreros_tabla">No.</div></td>
    <td width="204" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td width="68" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td width="137" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
  </tr>
  <tr>
  <?php echo $resultados1 ?>  </tr>
</table>
<hr>
<p align="left">
	<strong>Total de Piezas:</strong><?php echo $totalpiezas ?><br>
	<strong>Total de Peso:</strong><?php echo number_format($totalpeso,2,",",".") ?><br>
	<strong>Total de Volumen:</strong><?php echo number_format($totalvolumen,2,",",".") ?><br>
</p>
</body>
</html>