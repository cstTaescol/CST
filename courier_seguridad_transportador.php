<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

$id_usuario=$_SESSION['id_usuario'];
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$id_guia=$_REQUEST['id_guia']; 

//Carga datos de la Guia
$sql="SELECT master, id_consignatario FROM guia WHERE id='$id_guia'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$master=$fila["master"];
$id_consignatario=$fila["id_consignatario"];
$impresion="";

//consultas auxiliares
$sql_aux="SELECT nombre FROM couriers WHERE id='$id_consignatario'";
$consulta_aux=mysql_query ($sql_aux,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila_aux=mysql_fetch_array($consulta_aux);
$consignatario=$fila_aux["nombre"];


//consultas auxiliares
$sql_aux="SELECT id,id_placa,nombre,no_documento FROM courier_transportador WHERE id_guia='$id_guia'";
$consulta_aux=mysql_query ($sql_aux,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila_aux=mysql_fetch_array($consulta_aux))
{
	$id=$fila_aux["id"];
	$no_documento=$fila_aux["no_documento"];
	$nombre=$fila_aux["nombre"];
	//consulta de la placa del vehiculo
	$id_placa=$fila_aux["id_placa"];
	$sql_aux2="SELECT placa FROM vehiculo_courier WHERE id='$id_placa'";
	$consulta_aux2=mysql_query($sql_aux2,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila_aux2=mysql_fetch_array($consulta_aux2);
	$placa=$fila_aux2["placa"];

	$impresion .= 	'<tr>
						<td class="celda_tabla_principal celda_boton">'.$placa .'</td>'. 
						'<td class="celda_tabla_principal celda_boton">'.$nombre .'</td>'.
						'<td class="celda_tabla_principal celda_boton">'.$no_documento .'</td>'. 
						'<td class="celda_tabla_principal celda_boton"><button type="button" onclick="procesarEliminar('.$id.')"><img src="imagenes/eliminar-act.png" title="Eliminar"></button></td>
					</tr>';
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />    
    <link rel="stylesheet" href="js/bootstrap.min.css" >
    <script src="js/jquery-3.3.1.min.js" ></script>
    <script src="js/popper.min.js" ></script>   
    <script src="js/bootstrap.min.js"></script>     
    <title>Seguridad Courier</title>
</head>
<body>
<form method="post">
	<p class="titulo_tab_principal">Datos de Veh&iacute;culos</p>
	<p class="asterisco" align="center">Gu&iacute;a: <?php echo $master?></p>
	<p class="asterisco" align="center">Courier: <?php echo $consignatario?></p>
	<table align="center">
    	<!-- VEHICULO -->
    	<tr>
        	<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Veh&iacute;culo</div></td>
            <td class="celda_tabla_principal celda_boton">            	
	            <select name="courier_idPlaca" id="courier_idPlaca" tabindex="1">
	                <option value="" >Seleccione uno</option>       
	                <?php
	                    $sql="SELECT id,placa FROM vehiculo_courier WHERE estado='A' GROUP BY placa ORDER BY placa ASC";
	                    $consulta=mysql_query ($sql,$conexion) or die ("ERROR 04: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	                    while($fila=mysql_fetch_array($consulta))
	                    {	                        
						
							echo '<option value="'.$fila['id'].'">'.$fila['placa'].'</option>';
	                    }
	                ?>
	            </select>
	            ....
                <button type="button" name="new_vehiculo" id="new_vehiculo" onclick="nueva_ventana_padre(<?php echo $id_consignatario ?>)" tabindex="2" <?php  $id_objeto=133; include("config/provilegios_objeto.php");  echo $activacion ?>> 
                    <img src="imagenes/agregar-act.png" alt="" title="Agregar" align="absmiddle"/> 
                </button>
                <script type="text/javascript">			
                    document.getElementById("courier_idPlaca").focus();
                </script> 
            </td>            
        </tr> 
        <!-- CC CONDUCTOR -->
        <tr>
        	<td class="celda_tabla_principal">
        		<div class="letreros_tabla asterisco">C&eacute;dula Conductor</div>
        	</td>
            <td class="celda_tabla_principal celda_boton">
            	<input type="text" name="courier_ccConductor" id="courier_ccConductor" tabindex="2" size="6" maxlength="9" onkeypress="return numeric(event)">            	
            </td>                 
        </tr>
        <!-- NOMBRE CONDUCTOR -->
        <tr>
        	<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Nombre de Conductor</div></td>
            <td class="celda_tabla_principal celda_boton">
            	<input type="text" name="courier_nombreConductor" id="courier_nombreConductor" tabindex="3" size="20" maxlength="30">
            </td>                 
        </tr>
    </table>
    <!-- Menu interno -->   
    <table width="450px" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="reset" name="reset" id="reset" tabindex="5">
                <img src="imagenes/descargar-act.png" title="Limpiar" />
            </button>
            <button type="button" name="guardar" id="guardar" tabindex="4" onclick="validar()">
                <img src="imagenes/guardar-act.png" title="Guardar" />
            </button>
          </td>
        </tr>
     </table> 
     <!-- Datos de los trasportadores asignados -->   
      <table width="450px" align="center">
        <tr>
        	<td class="celda_tabla_principal" colspan="2"><div class="letreros_tabla asterisco">Transportador Asignado</div></td>
        </tr>      	
        <tr>
        	<td class="celda_tabla_principal celda_boton" colspan="2">
        		<div id="usuarios_asignados">
        			<table>
	        			<tr>
	        				<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Placa</div></td>
	        				<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Nombre</div></td>
	        				<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Doc.</div></td>
	        				<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Borrar</div></td>
	        			</tr>        			
        				<?php echo $impresion ?>        			
        			</table>
        		</div>				
        	</td>
        </tr>     	
     </table>
</form>

<!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Registro Almacenado          
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          Desea AGREGAR m&aacute;s datos?
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal" onclick="location.reload()">Si</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="salida()">No</button>
        </div>
        
      </div>
    </div>
  </div>
</body>
</html>

<script language="javascript">
function abrir(url,alto,ancho)
{
	popupWin = window.open(url,'Registro_Fotografico','directories, status, scrollbars, resizable, dependent, width='+ancho+', height='+alto+', left=50, top=50')
	//popupWin = window.open('pdf_remesa.php','nombre_ventana','menubar, toolbar, location, directories, status, scrollbars, resizable, dependent, width=640, height=480, left=0, top=0')
}
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
	if (document.forms[0].courier_idPlaca.value=="")
	{
		alert("Atencion: Debe seleccionar un VEHICULO.");
		document.forms[0].courier_idPlaca.focus();
		return(false);
	}

	if (document.forms[0].courier_ccConductor.value=="")
	{
		alert("Atencion: Debe digitar la CEDULA del CONDUCTOR.");
		document.forms[0].courier_ccConductor.focus();		
		return(false);
	}
	
	if (document.forms[0].courier_nombreConductor.value=="")
	{
		alert("Atencion: Debe digitar el NOMBRE del CONDUCTOR.");
		document.forms[0].courier_nombreConductor.focus();		
		return(false);		
	}	
	guardar();
}

//Guarda el formulario
function guardar()
{
	var datosFormulario={
							placa:$("#courier_idPlaca").val(),
							cc:$("#courier_ccConductor").val(),
							nombre:$("#courier_nombreConductor").val(),
							id_guia:<?php echo $id_guia ?>
						}
	$.get("courier_seguridad_transportador_agregar.php",datosFormulario,resultadoProcesoAgregar);
}
function resultadoProcesoAgregar(datos_devueltos)
{
	var respuesta = datos_devueltos.substring(0, 5);
	var coderror = datos_devueltos.substring(6, 7);	
	switch(respuesta)
	{
		case "Error":
			if(coderror == "0")
			{
				document.location="cerrar_sesion.php";
			}
			else
			{
				alert(datos_devueltos);
			}			
		break;

		default:
			$("#myModal").modal("show");
			recarga_ventana_padre();			
		break;
	}
}

//Elimina los items seleccionados
function procesarEliminar(id)
{
	//empaquetamos los campos del formulario a enviar
	var datosFormulario={
							id_registro:id,
							id_guia:<?php echo $id_guia ?>
						}
	$.get("courier_seguridad_transportador_eliminar.php",datosFormulario,resultadoProcesoEliminar);		
}
function resultadoProcesoEliminar(datos_devueltos)
{
	var respuesta = datos_devueltos.substring(0, 5);
	var coderror = datos_devueltos.substring(6, 7);	
	switch(respuesta)
	{
		case "Error":
			if(coderror == "0")
			{
				document.location="cerrar_sesion.php";
			}
			else
			{
				alert(datos_devueltos);
			}			
		break;

		default:
			$("#usuarios_asignados").html(datos_devueltos);
			recarga_ventana_padre();
		break;
	}
}


function nueva_ventana_padre(valor)
{	
	window.opener.location="vehiculo_courier_registro.php?id_consignatario="+valor;
	self.close();
}

function recarga_ventana_padre()
{	
	window.opener.location.reload();	
}

function salida()
{	
	window.opener.location.reload();
	self.close();
}

</script>