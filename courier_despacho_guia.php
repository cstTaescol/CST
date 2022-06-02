<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script src="js/srcNewCalendar/js/jscal2.js"></script>
    <script src="js/srcNewCalendar/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/steel/steel.css" />	
</head>
<body>
<?php
	require("menu.php");
	$id_objeto=56;
	include("config/provilegios_modulo.php");
	$id_guia=$_REQUEST['id_guia']; 
	//Carga datos de la Guia
	$sql="SELECT master, courier_dato_inicio, courier_id_linea, piezas, peso, id_aerolinea, id_consignatario, courier_idPlaca, courier_ccConductor, courier_nombreConductor FROM guia WHERE id='$id_guia'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$master=$fila["master"]; 
	$ccConductor=$fila["courier_ccConductor"];
	$nombreConductor=$fila["courier_nombreConductor"]; 

	//Formato fecha hora
	$courier_dato_inicio=$fila["courier_dato_inicio"];
	$fecha_inicio=substr($courier_dato_inicio,0,10);
	$hora_inicio=substr($courier_dato_inicio,11,2);
	$minutos_inicio=substr($courier_dato_inicio,14,2);
	$datoBdInicio= str_replace("-", "", $fecha_inicio).$hora_inicio.$minutos_inicio;  

	//Consulta adicional
	$id_aerolinea=$fila["id_aerolinea"];
	$sql2="SELECT nombre FROM aerolinea WHERE id ='$id_aerolinea'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$aerolinea=$fila2['nombre'];

	//Consulta adicional
	$courier_id_linea=$fila["courier_id_linea"];
	$sql2="SELECT nombre FROM courier_linea WHERE id ='$courier_id_linea'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$linea=$fila2['nombre'];

	//Consulta adicional
	$id_consignatario=$fila["id_consignatario"];
	$sql2="SELECT nombre FROM consignatario WHERE id ='$id_consignatario'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$consignatario=$fila2['nombre'];

	//Consulta adicional
	$courier_idPlaca=$fila["courier_idPlaca"];
	$sql2="SELECT placa FROM vehiculo_courier WHERE id ='$courier_idPlaca'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$placa=$fila2['placa'];

	//Consulta adicional
	$funcionarios="";
	$sql2="SELECT f.nombre FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia c ON f.id = c.id_funcionario WHERE c.id_guia ='$id_guia' AND f.id_entidad = '1' ORDER BY f.nombre ASC";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 6: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila2=mysql_fetch_array($consulta2))
	{
	$funcionarios .= $fila2['nombre'] . "<br>";
	}

	//Consulta adicional
	$piezasAprehesion=0;
	$pesoAprehesion=0;
	$sql2="SELECT piezas, peso FROM guia WHERE id_tipo_guia='6' AND master='$id_guia'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 6: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila2=mysql_fetch_array($consulta2))
	{
		$piezasAprehesion += $fila2['piezas'];
		$pesoAprehesion += $fila2['peso'];
	}
	$piezas=$fila["piezas"] - $piezasAprehesion;
	$peso=$fila["peso"] - $pesoAprehesion;   
?>
<p class="titulo_tab_principal">Despacho de Guia</p>
<p class="asterisco" align="center">Gu&iacute;a: <?php echo $master?></p>
<form name="guardar_datos" id="guardar_datos" method="post">
  <table align="center">
      <!-- Calendario -->
      <tr>
        <td align="left" class="celda_tabla_principal">
        	<div class="letreros_tabla">
                	Datos de Finalizaci&oacute;n<br />
                    <img src="imagenes/linea_courier.png" height="50" width="50">                    
        	</div>
        </td>
        <td class="celda_tabla_principal celda_boton">
		      <table>
				<tr>
		          <td colspan="4" id="cont"></td>
		        </tr>
		        <tr>
		          <td>
		            <input style="text-align: center" readonly="true" name="date" id="date" size="14"/>
		          </td>
		          <td>
		            <input style="text-align: center" readonly="true" name="hour" id="hour" size="2"/>
		          </td>
		          <td>:</td>
		          <td>
		            <input style="text-align: center" readonly="true" name="minute" id="minute" size="2"/>
		            <img src="imagenes/eliminar-act.png" valign="middle" title="Eliminar" onclick="limpiarCampos()">
		          </td>
		        </tr>
		      </table>        	
        </td>
      </tr>
      <!-- Observaciones -->            
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
        <td class="celda_tabla_principal celda_boton"><textarea cols="33" rows="5" tabindex="10" name="observaciones" id="observaciones"></textarea></td>
      </tr>      
      <!-- Aerolinea -->
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
        <td class="celda_tabla_principal celda_boton"><?php echo $aerolinea ?></td>
      </tr>      
      <!-- Consignatario -->
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Consignatario</div></td>
        <td class="celda_tabla_principal celda_boton"><?php echo $consignatario ?></td>
      </tr>      
      <!-- Linea -->
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">L&iacute;nea</div></td>
        <td class="celda_tabla_principal celda_boton"><?php echo $linea ?></td>
      </tr>      
      <!-- Piezas -->
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
        <td class="celda_tabla_principal celda_boton"><input type="hidden" name="piezas" id="piezas" value="<?php echo $piezas ?>"><?php echo $piezas ?></td>
      </tr>      
      <!-- Peso -->
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
        <td class="celda_tabla_principal celda_boton"><input type="hidden" name="peso" id="peso" value="<?php echo $peso ?>"><?php echo $peso ?></td>
      </tr>      
      <!-- Entregado a -->
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Entregado A:</div></td>
        <td class="celda_tabla_principal celda_boton">
        	Nombre:<?php echo $nombreConductor ?><br>
        	CC:<?php echo $ccConductor ?><br>
        	Placa:<?php echo $placa ?><br>        	
        </td>
      </tr>      
      <!-- Funcionarios Dian  -->
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Funcionarios Dian:</div></td>
        <td class="celda_tabla_principal celda_boton"><?php echo $funcionarios ?></td>
      </tr>      
    </table>	
    <table align="center">
        <tr>
            <td align="center" valign="middle" colspan="2">
                <div id="respuesta" class="opaco_ie" style="position:relative; background-image:url(imagenes/background.png);width:100%; height:30px"></div>
            </td>
        </tr>
    </table>
    <input type="hidden" name="id_guia" id="id_guia" value="<?php echo $id_guia ?>"/>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" tabindex="13" title="Atras" onclick="document.location='courier_inventario.php'">
                <img src="imagenes/al_principio-act.png" title="Atras" />
            </button>
            <button type="reset" name="reset" id="reset" tabindex="12" title="Limpiar">
                <img src="imagenes/descargar-act.png" title="Limpiar" />
            </button>
            <button type="button" name="guardar" id="guardar" tabindex="11" title="Guardar" onClick="return validar();">
                <img src="imagenes/guardar-act.png" title="Guardar" />
            </button>
          </td>
        </tr>
    </table>
</form>
</body>
</html>

<script language="javascript">
function validar()
{		
	if (document.forms[0].date.value=="")
	{
		alert("Atencion: Debe ingresar una FECHA de FINALIZACION de la revision");
		document.getElementById("date").focus();
		return(false);
	}
	var aerolinea="<?php echo $aerolinea ?>";
	var consignatario="<?php echo $consignatario ?>";
	var linea="<?php echo $linea ?>";
	var nombreConductor="<?php echo $nombreConductor ?>";
	var piezas="<?php echo $piezas ?>";
	var peso="<?php echo $peso ?>";
	
	if ((aerolinea == "" ) || (consignatario == "" ) || (linea == "" ) || (nombreConductor == "" ) || (piezas == "" ) || (peso == "" ))
	{
		alert("Atencion: Debe completar todos los datos para poder finalizar el despacho (Aerolinea, Consignatario, Linea, Piezas, Peso y Datos de Conductor)");
		document.location='courier_inventario.php';
		return(false);		
	}

  //Validacion de fechas anteriores
  switch(document.forms[0].hour.value)
  {
    case "0":
      document.forms[0].hour.value="00";
    break;

    case "1":
      document.forms[0].hour.value="01";
    break;

    case "2":
      document.forms[0].hour.value="02";
    break;

    case "3":
      document.forms[0].hour.value="03";
    break;    

    case "4":
      document.forms[0].hour.value="04";
    break;    

    case "5":
      document.forms[0].hour.value="05";
    break;    

    case "6":
      document.forms[0].hour.value="06";
    break;    

    case "7":
      document.forms[0].hour.value="07";
    break;    

    case "8":
      document.forms[0].hour.value="08";
    break;    

    case "9":
      document.forms[0].hour.value="09";
    break;    
  }

  if(document.forms[0].minute.value == "0" )
    document.forms[0].minute.value="00";
  else if(document.forms[0].minute.value == "5")
    document.forms[0].minute.value="05";

  var valorFinalizacion=document.forms[0].date.value + document.forms[0].hour.value + document.forms[0].minute.value;
  var datoFinalizacion =parseInt(valorFinalizacion.replace(/-/gi, ""));
  var datoBdInicio = parseInt(<?php echo $datoBdInicio; ?>);
  
  if(datoBdInicio > datoFinalizacion)
  {
    alert("Atencion: No puede ingresar unos DATOS DE FINALIZACION anteriores a los DATOS DE INICIO (<?php echo $courier_dato_inicio ?>)");
    return(false);
  }

  //Procedimiento de Guardado
	guardar_form();		
}

function guardar_form()
{
	var peticion = new Request(
	{
		url: 'courier_despacho_salvar.php',
		method: 'post',
		onRequest: function()
		{			
			mostrar_div($('respuesta'));
			$('respuesta').innerHTML='<p align="center">Procesando...<image src="imagenes/cargando.gif"></p>';
			$('guardar').disabled=true;
			$('reset').disabled=true;			
		},			

    onSuccess: function(responseText)
    {      
      var respuesta=responseText;
      var arreglos = respuesta.split("|¡¡|");
      var accion = eval(arreglos[0]);
      var coderror = eval(arreglos[1]);      
      var mensaje = arreglos[3];
      switch(accion)
      {
        case 0: //Error por Exit() en consulta BD
          $('respuesta').innerHTML='<p align="center">Error Codigo '+ coderror + mensaje+'</p>';
          $('guardar').disabled=false;
          $('reset').disabled=false;          
        break;

        case 1: //Registro Exitoso   
          var id_registro = eval(arreglos[2]);       
          $('respuesta').innerHTML='<p align="center">Proceso Finalizado</p>';
          alert('Proceso Finalizado con exito');
          document.location='courier_despacho_opciones.php?id_registro='+id_registro;
          $('guardar').disabled=false;
          $('reset').disabled=false;
        break;

        case 2: //Error por agregar usuario que revisan el proceso de esta guia
          $('respuesta').innerHTML='<p align="center">Error Codigo '+ coderror + mensaje+'</p>';
          alert(mensaje);
          $('guardar').disabled=false;
          $('reset').disabled=false;          
        break;
      }
    },
		onFailure: function()
		{
			$('respuesta').innerHTML='<p align="center">Error al guardar, Intente de nuevo...</p>';
			$('guardar').disabled=false;
			$('reset').disabled=false;
		}
	}
	);
	peticion.send($('guardar_datos'));
}



function mostrar_div(id_div)
{
	id_div.set('morph',{ 
	duration: 200, 
	transition: 'linear'
	});
	id_div.morph({
		'opacity': 1 
	});
}

// Calendario.
function updateFields(cal) 
{
          var date = cal.selection.get();
          if (date) {
                  date = Calendar.intToDate(date);
                  document.getElementById("date").value = Calendar.printDate(date, "%Y-%m-%d");
          }
          document.getElementById("hour").value = cal.getHours();
          document.getElementById("minute").value = cal.getMinutes();
};

Calendar.setup({
          cont         : "cont",
          showTime     : 12,
          onSelect     : updateFields,
          onTimeChange : updateFields
}); 

//Limpiar datos de calendario
function limpiarCampos()
{
  document.forms[0].date.value="";
  document.forms[0].hour.value="";
  document.forms[0].minute.value="";
}

</script>
