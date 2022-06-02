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
$sql_aux="SELECT fg.id,f.nombre,f.no_documento FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia fg ON fg.id_funcionario = f.id WHERE fg.id_guia='$id_guia' AND fg.tipo='C' AND f.id_consignatario='$id_consignatario'";
$consulta_aux=mysql_query ($sql_aux,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila_aux=mysql_fetch_array($consulta_aux))
{
	$id=$fila_aux["id"];
	$no_documento=$fila_aux["no_documento"];
	$nombre=$fila_aux["nombre"];

	$impresion .= 	'<tr>
						<td class="celda_tabla_principal celda_boton">'.$nombre .'</td>'.
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
	<p class="titulo_tab_principal">Datos de Funcionario</p>
	<p class="asterisco" align="center">Gu&iacute;a: <?php echo $master?></p>
	<p class="asterisco" align="center">Courier: <?php echo $consignatario?></p>
	<table align="center">
    	<!-- Funcionario Existente -->
    	<tr>
        	<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Funcionario</div></td>
            <td class="celda_tabla_principal celda_boton">            	
	            <select name="dato_preexistente" id="dato_preexistente" tabindex="1">
	                <option value="" >Seleccione uno</option>       
	                <?php
	                    $sql="SELECT id,nombre FROM courier_funcionario WHERE id_consignatario='$id_consignatario' AND estado='A'";
	                    $consulta=mysql_query ($sql,$conexion) or die ("ERROR 04: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	                    while($fila=mysql_fetch_array($consulta))
	                    {	                        
						
							echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
	                    }
	                ?>
	            </select>
	            ....
                <button type="button" name="agregar" id="agregar" onclick="agregarPreexistente()" tabindex="2"> 
                    <img src="imagenes/check_blue.png" alt="" title="Agregar" align="absmiddle"/> 
                </button>
                <script type="text/javascript">			
                    document.getElementById("dato_preexistente").focus();
                </script> 
            </td>            
        </tr> 
        <!-- NUEVO FUNCIONARIO -->
        <tr>
        	<td class="celda_tabla_principal" colspan="2">
        		<div class="letreros_tabla asterisco">Nuevo Funcionario</div>
        	</td>
		</tr>        
        <!-- CC  -->
        <tr>
        	<td class="celda_tabla_principal">
        		<div class="letreros_tabla asterisco">C&eacute;dula</div>
        	</td>
            <td class="celda_tabla_principal celda_boton">
            	<input type="text" name="cc" id="cc" tabindex="2" size="6" maxlength="13" onkeypress="return numeric(event)">            	
            </td>                 
        </tr>
        <!-- NOMBRE  -->
        <tr>
        	<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Nombre</div></td>
            <td class="celda_tabla_principal celda_boton">
            	<input type="text" name="nombre" id="nombre" tabindex="3" size="20" maxlength="50">
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
        	<td class="celda_tabla_principal" colspan="2"><div class="letreros_tabla asterisco">Funcionario Asignado</div></td>
        </tr>      	
        <tr>
        	<td class="celda_tabla_principal celda_boton" colspan="2">
        		<div id="usuarios_asignados">
        			<table>
	        			<tr>
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
          <h4 class="modal-title">Registro Procesado          
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          Datos Procesados Exitosamente
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal" onclick="location.reload()">Aceptar</button>
          <!--<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="salida()">No</button>-->
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


//Agrega el dato Preexistente
function agregarPreexistente()
{
	if (document.forms[0].dato_preexistente.value=="")
	{
		alert("Atencion: Debe SELECCIONAR un funcionario  o crear uno NUEVO.");
		document.forms[0].dato_preexistente.focus();				
	}	
	else
	{
		var datosFormulario={
								id_funcionario:$("#dato_preexistente").val(),
								id_guia:<?php echo $id_guia ?>
							}
		$.get("courier_seguridad_funcionario_agregarPreexistente.php",datosFormulario,resultado_agregarPreexistente);		
	}
}
function resultado_agregarPreexistente(datos_devueltos)
{
	var respuesta = datos_devueltos.substring(0, 5);
	var coderror = datos_devueltos.substring(6, 7);
	//alert(respuesta);
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
		break;
	}
}

// funcion para validar
function validar()
{	
	if (document.forms[0].cc.value=="")
	{
		alert("Atencion: Debe digitar la CEDULA.");
		document.forms[0].cc.focus();		
		return(false);
	}
	
	if (document.forms[0].nombre.value=="")
	{
		alert("Atencion: Debe digitar el NOMBRE.");
		document.forms[0].nombre.focus();		
		return(false);		
	}	
	guardar();
}

//Guarda el formulario
function guardar()
{
	var datosFormulario={							
							cc:$("#cc").val(),
							nombre:$("#nombre").val(),
							id_consignatario:<?php echo $id_consignatario ?>,
							id_guia:<?php echo $id_guia ?>
						}
	$.get("courier_seguridad_funcionario_agregar.php",datosFormulario,resultadoProcesoguardar);
}
function resultadoProcesoguardar(datos_devueltos)
{
	var respuesta = datos_devueltos.substring(0, 5);
	var coderror = datos_devueltos.substring(6, 7);
	//alert(respuesta);
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
	$.get("courier_seguridad_funcionario_eliminar.php",datosFormulario,resultadoProcesoEliminar);		
}
function resultadoProcesoEliminar(datos_devueltos)
{
	var respuesta = datos_devueltos.substring(0, 5);
	var coderror = datos_devueltos.substring(6, 7);
	//alert(respuesta);
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