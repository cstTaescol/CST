<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$totalpiezas=0;
$totalpeso=0;
$totalvolumen=0;
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
<p class="titulo_tab_principal">Reporte Personalizado</p>
<form name="buscar" method="post" action="reportes/reporte_general.php" onsubmit="return validar();" target="_blank">
  <table align="center" cellpadding="0" cellspacing="0" style="width:650px" >
    <tr>
      <td>
      <p>&nbsp;</p>
      <table align="center" style="width:650px">
        <tr>
          <td class="celda_tabla_principal"><div class="letreros_tabla">Tipo de Guia</div></td>
          <td colspan="2" bgcolor="#FFFFFF"> 
             <select name="tipoguia" id="tipoguia" tabindex="1" onchange="evaluador_master(this.value);">
                <option value="*">TODAS</option>
                <?php
					$sql="SELECT id,nombre FROM tipo_guia WHERE estado='A' ORDER BY nombre ASC";
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
          <select name="id_aerolinea" id="id_aerolinea" tabindex="5">
            <?php
				if ($id_aerolinea_user == "*")
					echo "<option value=\"*\">TODAS</option>";	
				
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
        <br />
        <div id="campos" style="opacity:1;">
           <table align="center" style="width:700px">
              <tr>
                <td width="30%" align="center" class="celda_tabla_principal">
                    <button type="button" onclick="activar_todos();" tabindex="7"><img src="imagenes/aceptar-act.png"/></button>
                    <button type="button" onclick="desactivar_todos();" tabindex="8"><img src="imagenes/aceptar-in.png"/></button>
                 </td>
                <td width="70%" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Campos</div></td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="vuelo" id="vuelo" tabindex="9"/></td>
                <td class="celda_tabla_principal celda_boton">VUELO</td>
              </tr>          
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="manifiesto" id="manifiesto" tabindex="10"/></td>
                <td class="celda_tabla_principal celda_boton">MANIFIESTO</td>
              </tr>          
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="master" id="master" tabindex="11"/></td>
                <td class="celda_tabla_principal celda_boton">MASTER</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="hija" id="hija" tabindex="12"/></td>
                <td class="celda_tabla_principal celda_boton">HIJA</td>
              </tr>          
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="piezas" id="piezas" tabindex="13"/></td>
                <td class="celda_tabla_principal celda_boton">PIEZAS</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="peso" id="peso" tabindex="14"/></td>
                <td class="celda_tabla_principal celda_boton">PESO</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="volumen" id="volumen" tabindex="15"/></td>
                <td class="celda_tabla_principal celda_boton">VOLUMEN</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="piezas_despaletizado" id="piezas_despaletizado" tabindex="16"/></td>
                <td class="celda_tabla_principal celda_boton">PIEZAS DESPALETIZADAS</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="peso_despaletizado" id="peso_despaletizado" tabindex="16"/></td>
                <td class="celda_tabla_principal celda_boton">PESO DESPALETIZADO</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="descripcion" id="descripcion" tabindex="16"/></td>
                <td class="celda_tabla_principal celda_boton">DESCRIPCION DE LA CARGA</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="verificado_piezas" id="verificado_piezas" tabindex="17"/></td>
                <td class="celda_tabla_principal celda_boton">PIEZAS VERIFICADAS</td>
              </tr>          
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="verificado_peso" id="verificado_peso" tabindex="18"/></td>
                <td class="celda_tabla_principal celda_boton">PESO VERIFICADO</td>
              </tr>          
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="destino" id="destino" tabindex="19"/></td>
                <td class="celda_tabla_principal celda_boton">DESTINO</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="despacho" id="despacho" tabindex="20"/></td>
                <td class="celda_tabla_principal celda_boton">No. DESPACHO</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="embarcador" id="embarcador" tabindex="21"/></td>
                <td class="celda_tabla_principal celda_boton">EMBARCADOR</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="consignatario" id="consignatario" tabindex="22"/></td>
                <td class="celda_tabla_principal celda_boton">CONSIGNATARIO</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="agente_carga" id="agente_carga" tabindex="23"/></td>
                <td class="celda_tabla_principal celda_boton">AGENTE DE CARGA</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="fecha_corte" id="fecha_corte" tabindex="24"/></td>
                <td class="celda_tabla_principal celda_boton">FECHA DE CORTE</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="asignacion_origen" id="asignacion_origen" tabindex="25"/></td>
                <td class="celda_tabla_principal celda_boton">ASIGNACION DE ORIGEN</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="reasignacion" id="reasignacion" tabindex="26"/></td>
                <td class="celda_tabla_principal celda_boton">REASIGNADA</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="precursor" id="precursor" tabindex="27"/></td>
                <td class="celda_tabla_principal celda_boton">PRECURSOR</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="tipo_carga" id="tipo_carga" tabindex="28"/></td>
                <td class="celda_tabla_principal celda_boton">TIPO DE CARGA</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="disposicion" id="disposicion" tabindex="29"/></td>
                <td class="celda_tabla_principal celda_boton">DISPOSICION DE CARGA</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="factura" id="factura" tabindex="30"/></td>
                <td class="celda_tabla_principal celda_boton">No. FACTURA</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="valor_factura" id="valor_factura" tabindex="31"/></td>
                <td class="celda_tabla_principal celda_boton">VALOR DE LA FACTURA</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="iva" id="iva" tabindex="30"/></td>
                <td class="celda_tabla_principal celda_boton">IVA</td>
              </tr>
              
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="fecha_factura" id="fecha_factura" tabindex="32"/></td>
                <td class="celda_tabla_principal celda_boton">FECHA DE LA FACTURA</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="facturadoa" id="facturadoa" tabindex="33"/></td>
                <td class="celda_tabla_principal celda_boton">FACTURADO A</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="cumplido_remesa" id="cumplido_remesa" tabindex="34"/></td>
                <td class="celda_tabla_principal celda_boton">CUMPLIDO REMESA</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="reg_foto_bodega" id="reg_foto_bodega" tabindex="35"/></td>
                <td class="celda_tabla_principal celda_boton">REGISTRO FOTOGRAFICO DE BODEGA</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="reg_foto_seguridad" id="reg_foto_seguridad" tabindex="36"/></td>
                <td class="celda_tabla_principal celda_boton">REGISTRO FOTOGRAFICO DE SEGURIDAD</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="reg_foto_despacho" id="reg_foto_despacho" tabindex="37"/></td>
                <td class="celda_tabla_principal celda_boton">REGISTRO FOTOGRAFICO DE DESPACHO</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="planilla_recepcion" id="planilla_recepcion" tabindex="38"/></td>
                <td class="celda_tabla_principal celda_boton">PLANILLA DE RECEPCION</td>
              </tr>
           </table>
        </div>
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


function activar_todos()
{
	document.getElementById('hija').checked=true;
	document.getElementById('master').checked=true;
	document.getElementById('piezas').checked=true;
	document.getElementById('peso').checked=true;
	document.getElementById('volumen').checked=true;
	document.getElementById('piezas_despaletizado').checked=true;
	document.getElementById('peso_despaletizado').checked=true;	
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
	document.getElementById('facturadoa').checked=true;
	document.getElementById('verificado_piezas').checked=true;
	document.getElementById('verificado_peso').checked=true;
	document.getElementById('cumplido_remesa').checked=true;
	document.getElementById('reg_foto_bodega').checked=true;
	document.getElementById('reg_foto_seguridad').checked=true;
	document.getElementById('reg_foto_despacho').checked=true;
	document.getElementById('planilla_recepcion').checked=true;
				
}

function desactivar_todos()
{
	document.getElementById('hija').checked=false;
	document.getElementById('master').checked=false;
	document.getElementById('piezas').checked=false;
	document.getElementById('peso').checked=false;
	document.getElementById('volumen').checked=false;
	document.getElementById('piezas_despaletizado').checked=false;
	document.getElementById('peso_despaletizado').checked=false;		
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
	document.getElementById('facturadoa').checked=false;
	document.getElementById('verificado_piezas').checked=false;
	document.getElementById('verificado_peso').checked=false;
	document.getElementById('cumplido_remesa').checked=false;
	document.getElementById('reg_foto_bodega').checked=false;
	document.getElementById('reg_foto_seguridad').checked=false;
	document.getElementById('reg_foto_despacho').checked=false;
	document.getElementById('planilla_recepcion').checked=false;		
}

function evaluador_master(tipo_guia)
{
	if (tipo_guia == 2)
	{
		$('campos').set('morph',{
			duration: 1000, //velocidad
			transition: 'back:out'			
		});
		$('campos').morph({
						  opacity:0
						  });
		$('menuguardar').set('morph',{
			duration: 1000, //velocidad
			transition: 'back:out'			
		});
		$('menuguardar').morph({
						  top:-1550 + 'px'
						  });
		
		//$('menuguardar').setStyle('top',-750);
			
	}
	else
		{
			$('campos').set('morph',{
				duration: 1000, //velocidad
				transition: 'back:out'			
			});
			$('campos').morph({
							  opacity:1
							  });
			var posicion_contenedor=$('campos').getStyle('top');
			$('menuguardar').set('morph',{
				duration: 1000, //velocidad
				transition: 'back:out'			
			});
			$('menuguardar').morph({
							  top:10 + 'px'
							  });			
		}
		
}
</script>

