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


function activar_todos()
{
	document.getElementById('hija').checked=true;
	document.getElementById('master').checked=true;
	document.getElementById('piezas').checked=true;
	document.getElementById('peso').checked=true;
	document.getElementById('volumen').checked=true;
	document.getElementById('descripcion').checked=true;
	document.getElementById('destino').checked=true;
	document.getElementById('despacho').checked=true;	
	document.getElementById('vuelo').checked=true;
	document.getElementById('manifiesto').checked=true;
	document.getElementById('embarcador').checked=true;
	document.getElementById('consignatario').checked=true;
	document.getElementById('agente_carga').checked=true;	
	document.getElementById('fecha_corte').checked=true;
	document.getElementById('asignacion_origen').checked=true;
	document.getElementById('reasignacion').checked=true;
	document.getElementById('precursor').checked=true;
	document.getElementById('tipo_carga').checked=true;
	document.getElementById('disposicion').checked=true;
	document.getElementById('factura').checked=true;
	document.getElementById('valor_factura').checked=true;
	document.getElementById('iva').checked=true;
	document.getElementById('fecha_factura').checked=true;
}

function desactivar_todos()
{
	document.getElementById('hija').checked=false;
	document.getElementById('master').checked=false;
	document.getElementById('piezas').checked=false;
	document.getElementById('peso').checked=false;
	document.getElementById('volumen').checked=false;
	document.getElementById('descripcion').checked=false;
	document.getElementById('destino').checked=false;
	document.getElementById('despacho').checked=false;	
	document.getElementById('vuelo').checked=false;
	document.getElementById('manifiesto').checked=false;
	document.getElementById('embarcador').checked=false;
	document.getElementById('consignatario').checked=false;
	document.getElementById('agente_carga').checked=false;	
	document.getElementById('fecha_corte').checked=false;
	document.getElementById('asignacion_origen').checked=false;
	document.getElementById('reasignacion').checked=false;
	document.getElementById('precursor').checked=false;
	document.getElementById('tipo_carga').checked=false;
	document.getElementById('disposicion').checked=false;
	document.getElementById('factura').checked=false;
	document.getElementById('valor_factura').checked=false;
	document.getElementById('iva').checked=false;
	document.getElementById('fecha_factura').checked=false;
}
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p align="center"><font color="#0066FF" size="+2"><strong>REPORTES </strong></font> <img src="imagenes/yast_systemprotocol.png" width="50" height="50" align="absmiddle" /></p>
<form name="buscar" method="post" action="reportes/reporte_estado.php" onsubmit="return validar();" target="_blank">
  <table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" bgcolor="#0066FF" bordercolor="#0033CC" >
    <tr>
      <td>
      <p>&nbsp;</p>
      <table width="70%" border="1" align="center" cellspacing="0" bordercolorlight="#000000">
        <tr>
          <td bgcolor="#CCCCCC">ESTADO DE LAS GUIAS</td>
          <td colspan="2" bgcolor="#FFFFFF"> 
             <select name="estado" id="estado" tabindex="1">
                <?php
					$sql="SELECT id,nombre FROM tipo_bloqueo_guia WHERE estado='A' ORDER BY nombre ASC";
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
          <td width="33%" bgcolor="#CCCCCC">RANGO DE FECHA</td>
          <td width="37%" bgcolor="#99CCCC"> Desde
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
        </script></td>
          <td width="27%" bgcolor="#FFCC99">Hasta
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
        </script></td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">AEROLINEA</td>
          <td colspan="2" bgcolor="#FFFFFF">
          <select name="id_aerolinea" id="id_aerolinea" tabindex="5">
            <option value="*">TODAS</option>
            <?php
			$sql="SELECT id,nombre FROM aerolinea WHERE estado='A' ORDER BY nombre ASC";
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
        <br />
        <table width="70%" border="1" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="20%" align="center" bgcolor="#CCCCCC">
            	<button type="button" onclick="activar_todos();" tabindex="7"><img src="imagenes/chek-green.jpg" width="20" height="20" /></button>
              	<button type="button" onclick="desactivar_todos();" tabindex="8"><img src="imagenes/chek-gris.jpg" width="20" height="20" /></button>
             </td>
            <td width="80%" align="center" bgcolor="#CCCCCC"><strong><em>CAMPOS</em></strong></td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="hija" id="hija" tabindex="9"/></td>
            <td bgcolor="#FFFFFF">HIJA</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="master" id="master" tabindex="10"/></td>
            <td bgcolor="#FFFFFF">MASTER</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="piezas" id="piezas" tabindex="11"/></td>
            <td bgcolor="#FFFFFF">PIEZAS</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="peso" id="peso" tabindex="12"/></td>
            <td bgcolor="#FFFFFF">PESO</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="volumen" id="volumen" tabindex="13"/></td>
            <td bgcolor="#FFFFFF">VOLUMEN</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="descripcion" id="descripcion" tabindex="14"/></td>
            <td bgcolor="#FFFFFF">DESCRIPCION DE LA CARGA</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="destino" id="destino" tabindex="15"/></td>
            <td bgcolor="#FFFFFF">DESTINO</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="despacho" id="despacho" tabindex="16"/></td>
            <td bgcolor="#FFFFFF">No. DESPACHO</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="vuelo" id="vuelo" tabindex="18"/></td>
            <td bgcolor="#FFFFFF">VUELO</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="manifiesto" id="manifiesto" tabindex="19"/></td>
            <td bgcolor="#FFFFFF">MANIFIESTO</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="embarcador" id="embarcador" tabindex="20"/></td>
            <td bgcolor="#FFFFFF">EMBARCADOR</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="consignatario" id="consignatario" tabindex="21"/></td>
            <td bgcolor="#FFFFFF">CONSIGNATARIO</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="agente_carga" id="agente_carga" tabindex="22"/></td>
            <td bgcolor="#FFFFFF">AGENTE DE CARGA</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="fecha_corte" id="fecha_corte" tabindex="23"/></td>
            <td bgcolor="#FFFFFF">FECHA DE CORTE</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="asignacion_origen" id="asignacion_origen" tabindex="25"/></td>
            <td bgcolor="#FFFFFF">ASIGNACION DE ORIGEN</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="reasignacion" id="reasignacion" tabindex="26"/></td>
            <td bgcolor="#FFFFFF">REASIGNADA</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="precursor" id="precursor" tabindex="27"/></td>
            <td bgcolor="#FFFFFF">PRECURSOR</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="tipo_carga" id="tipo_carga" tabindex="28"/></td>
            <td bgcolor="#FFFFFF">TIPO DE CARGA</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="disposicion" id="disposicion" tabindex="29"/></td>
            <td bgcolor="#FFFFFF">DISPOSICION DE CARGA</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="factura" id="factura" tabindex="30"/></td>
            <td bgcolor="#FFFFFF">No. FACTURA</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="valor_factura" id="valor_factura" tabindex="30"/></td>
            <td bgcolor="#FFFFFF">VALOR DE LA FACTURA</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="iva" id="iva" tabindex="30"/></td>
            <td bgcolor="#FFFFFF">IVA</td>
          </tr>
          <tr>
            <td align="center" valign="middle" bgcolor="#CCCCCC"><input type="checkbox" value="1" name="fecha_factura" id="fecha_factura" tabindex="31"/></td>
            <td bgcolor="#FFFFFF">FECHA DE LA FACTURA</td>
          </tr>
        </table>
        <br />
        <table width="70%" border="1" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center" valign="middle" bgcolor="#FFFFFF"> 
                 <button name="anterior" onclick="document.location='base.php'" tabindex="33">
                    <img src="imagenes/back.jpg" width="69" height="68" />
                 </button>   
                
                <button type="reset" name="cancelar" tabindex="32">
                    <img src="imagenes/limpiar.jpg" width="51" ><br>
                    <strong>Limpiar</strong>
                </button>   
                <button type="submit" name="enviar" tabindex="31">
                    <img src="imagenes/next.jpg" width="69" height="68" />
                </button>
            </td>
          </tr>
        </table>
      <p>&nbsp;</p></td>
    </tr>
  </table>
  <p><br />
</p>
</form>						
</body>
</html>
