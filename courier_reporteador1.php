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
    <!-- jquery -->
    <link rel="stylesheet" href="js/bootstrap.min.css" >
    <script src="js/jquery-3.3.1.min.js" ></script>
    <script src="js/popper.min.js" ></script>   
    <script src="js/bootstrap.min.js"></script> 

    <!--Hoja de estilos del calendario -->
    <link rel="stylesheet" type="text/css" media="all" href="js/calendar-color.css" title="win2k-cold-1" />
    <script type="text/javascript" src="js/calendar.js"></script>
    <script type="text/javascript" src="js/calendar-es.js"></script>
    <script type="text/javascript" src="js/calendar-setup.js"></script>
</head>
<body>
<?php
  require("menu.php");
?>
<p class="titulo_tab_principal">Reporte Personalizado</p>
<form name="buscar" method="post" action="reportes/courier_reporte_general.php" onsubmit="return validar();" target="_blank">
  <table align="center" cellpadding="0" cellspacing="0" style="width:650px" >
    <tr>
      <td>
      <p>&nbsp;</p>
      <table align="center" style="width:650px">
        <tr>
          <td class="celda_tabla_principal"><div class="letreros_tabla">Tipo de Guia</div></td>
          <td colspan="2" bgcolor="#FFFFFF"> &nbsp;
             <select name="tipoguia" id="tipoguia" tabindex="1" onchange="evaluador_master(this.value);">
                <option value="*">TODAS</option>
	          </select>
          </td>
          </tr>
        <tr>
              <td width="150" class="celda_tabla_principal"><div class="letreros_tabla">Rango de Fecha</div></td>
              <td width="250px" class="celda_tabla_principal celda_boton">
                    <div class="asterisco">Desde</div>
                    <input name="rangoini" type="text" id="rangoini" size="10" readonly="readonly"/>
                    <input type="button" id="lanzador" value="..." tabindex="2"/>
                    <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
                    <!-- script que define y configura el calendario-->
                    <script type="text/javascript">
                      Calendar.setup(
                      {
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
                <input type="button" id="lanzador2" value="..." tabindex="3"/>
                <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
                <!-- script que define y configura el calendario-->
                <script type="text/javascript">
                  Calendar.setup(
                  {
                      inputField     :    "rangofin",      // id del campo de texto
                      ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                      button         :    "lanzador2"   // el id del botón que lanzará el calendario
                  });
                </script>
            </td>
        </tr>
      </table>
        <br />
        <div id="campos">
           <table align="center" style="width:700px">
              <tr>
                <td width="30%" align="center" class="celda_tabla_principal">
                    <button type="button" onclick="activar_todos();" tabindex="4"><img src="imagenes/aceptar-act.png"/></button>
                    <button type="button" onclick="desactivar_todos();" tabindex="5"><img src="imagenes/aceptar-in.png"/></button>
                 </td>
                <td width="70%" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Campos</div></td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="nguia" id="nguia" tabindex="6"/></td>
                <td class="celda_tabla_principal celda_boton">No. GUIA</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal">
                  <input type="checkbox" value="1" name="cantidad_hijas" id="cantidad_hijas" tabindex="7"/>
                  <img src="imagenes/alerta-act.png" data-toggle="tooltip" title="Atenci&oacute;n: Incluir este campo puede hacer demorada la Consulta">
                </td>
                <td class="celda_tabla_principal celda_boton">CANTIDAD DE APREHENSIONES</td>
              </tr>          
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="p1168" id="p1168" tabindex="8"/></td>
                <td class="celda_tabla_principal celda_boton">PLANILLA 1168</td>
              </tr>                        
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="courier" id="courier" tabindex="8"/></td>
                <td class="celda_tabla_principal celda_boton">COURIER</td>
              </tr>          
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="piezas" id="piezas" tabindex="9"/></td>
                <td class="celda_tabla_principal celda_boton">PIEZAS</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="peso" id="peso" tabindex="10"/></td>
                <td class="celda_tabla_principal celda_boton">PESO</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="linea" id="linea" tabindex="11"/></td>
                <td class="celda_tabla_principal celda_boton">LINEA USADA</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="vehiculos" id="vehiculos" tabindex="12"/></td>
                <td class="celda_tabla_principal celda_boton">VEHICULOS USADOS</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="conductores" id="conductores" tabindex="13"/></td>
                <td class="celda_tabla_principal celda_boton">CONDUCTORES USADOS</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="personalDian" id="personalDian" tabindex="14"/></td>
                <td class="celda_tabla_principal celda_boton">PERSONAL ASIGNADO - DIAN</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="personalTaescol" id="personalTaescol" tabindex="15"/></td>
                <td class="celda_tabla_principal celda_boton">PERSONAL ASIGNADO - TAESCOL</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="personalPolfa" id="personalPolfa" tabindex="16"/></td>
                <td class="celda_tabla_principal celda_boton">PERSONAL ASIGNADO - POLFA</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="personalInvima" id="personalInvima" tabindex="17"/></td>
                <td class="celda_tabla_principal celda_boton">PERSONAL ASIGNADO - INVIMA</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="personalIca" id="personalIca" tabindex="18"/></td>
                <td class="celda_tabla_principal celda_boton">PERSONAL ASIGNADO - ICA</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="personalOtros" id="personalOtros" tabindex="19"/></td>
                <td class="celda_tabla_principal celda_boton">PERSONAL ASIGNADO - OTROS</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="personalCourier" id="personalCourier" tabindex="20"/></td>
                <td class="celda_tabla_principal celda_boton">PERSONAL ASIGNADO - COURIER</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="datos_llegada" id="datos_llegada" tabindex="22"/></td>
                <td class="celda_tabla_principal celda_boton">FECHA LLEGADA</td>
              </tr>          
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="datos_inicio" id="datos_inicio" tabindex="23"/></td>
                <td class="celda_tabla_principal celda_boton">FECHA INICIO REVISION</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="datos_fin" id="datos_fin" tabindex="24"/></td>
                <td class="celda_tabla_principal celda_boton">FECHA FIN REVISION</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="datos_despacho" id="datos_despacho" tabindex="25"/></td>
                <td class="celda_tabla_principal celda_boton">FECHA DOC. DESPACHO</td>
              </tr>
              <tr>
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="no_despacho" id="no_despacho" tabindex="27"/></td>
                <td class="celda_tabla_principal celda_boton">No. DOC. DESPACHO</td>
              </tr>               
              <tr>              
                <td align="center" valign="middle" class="celda_tabla_principal"><input type="checkbox" value="1" name="facturacion" id="facturacion" tabindex="28"/></td>
                <td class="celda_tabla_principal celda_boton">FACTURACION</td>
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
            <button type="button" tabindex="34" onclick="document.location='courier_consulta_reportes.php'">
                <img src="imagenes/al_principio-act.png" title="Atras" />
            </button>
            <button type="reset" tabindex="33">
                <img src="imagenes/descargar-act.png" title="Limpiar" />
            </button>
            <button type="submit" tabindex="32">
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
$(document).ready(function()
{
  $('[data-toggle="tooltip"]').tooltip(); 
});

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
	document.getElementById('nguia').checked=true;
  document.getElementById('p1168').checked=true;
  document.getElementById('courier').checked=true;
	document.getElementById('piezas').checked=true;
  document.getElementById('peso').checked=true;
	document.getElementById('linea').checked=true;
	document.getElementById('vehiculos').checked=true;	
	document.getElementById('conductores').checked=true;
	document.getElementById('personalDian').checked=true;
  document.getElementById('personalTaescol').checked=true;
  document.getElementById('personalPolfa').checked=true;
  document.getElementById('personalInvima').checked=true;
  document.getElementById('personalIca').checked=true;
  document.getElementById('personalOtros').checked=true;
  document.getElementById('personalCourier').checked=true;
	document.getElementById('cantidad_hijas').checked=true;	
	document.getElementById('datos_llegada').checked=true;
	document.getElementById('datos_inicio').checked=true;
	document.getElementById('datos_fin').checked=true;
	document.getElementById('datos_despacho').checked=true;
	document.getElementById('no_despacho').checked=true;	
	document.getElementById('facturacion').checked=true;
}

function desactivar_todos()
{  
  document.getElementById('nguia').checked=false;
  document.getElementById('p1168').checked=false;
  document.getElementById('courier').checked=false;
  document.getElementById('piezas').checked=false;
  document.getElementById('peso').checked=false;
  document.getElementById('linea').checked=false;
  document.getElementById('vehiculos').checked=false;  
  document.getElementById('conductores').checked=false;
  document.getElementById('personalDian').checked=false;
  document.getElementById('personalTaescol').checked=false;
  document.getElementById('personalPolfa').checked=false;
  document.getElementById('personalInvima').checked=false;
  document.getElementById('personalIca').checked=false;
  document.getElementById('personalOtros').checked=false;
  document.getElementById('personalCourier').checked=false;  
  document.getElementById('cantidad_hijas').checked=false; 
  document.getElementById('datos_llegada').checked=false;
  document.getElementById('datos_inicio').checked=false;
  document.getElementById('datos_fin').checked=false;
  document.getElementById('datos_despacho').checked=false;
  document.getElementById('no_despacho').checked=false;  
  document.getElementById('facturacion').checked=false;  	
}
</script>

