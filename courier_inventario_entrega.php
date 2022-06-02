<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" href="js/bootstrap.min.css" >
    <script src="js/jquery-3.3.1.min.js" ></script>
    <script src="js/popper.min.js" ></script>   
    <script src="js/bootstrap.min.js"></script>     
    <title>Inventario de Guias por Entregar</title>	
</head>
<body>
<?php
	require("menu.php");
	$id_objeto=137;
	include("config/provilegios_modulo.php");

	//Evaluación de filtro en la cuadrícula
	$filtro='courier_id_linea';
	$colorLinea='style="background-color: #FFCC00"';
	$colorCourier='';					
	if(isset($_REQUEST['filtro']))
	{
		switch ($_REQUEST['filtro']) {
			case 'courier_id_linea':
				$filtro='courier_id_linea';
				$colorLinea='style="background-color: #FFCC00"';
				$colorCourier='';
			break;
			
			case 'id_consignatario':
				$filtro='id_consignatario';
				$colorLinea='';
				$colorCourier='style="background-color: #FFCC00"';				
			break;

			default:
				$filtro='courier_id_linea';
				$colorLinea='style="background-color: #FFCC00"';
				$colorCourier='';				
			break;
		}
	}

	//Filtro por casilla de búsqueda
	if(isset($_REQUEST['busqueda']))
	{
		$busqueda=$_REQUEST['busqueda'];
		$sql="SELECT * FROM guia WHERE id_tipo_guia ='5' AND courier_id_linea !='NULL' AND (id_tipo_bloqueo='1' OR id_tipo_bloqueo='3') AND master LIKE '%$busqueda%'";	
	}
	else
	{
		$sql="SELECT * FROM guia WHERE id_tipo_guia ='5' AND courier_id_linea !='NULL' AND (id_tipo_bloqueo='1' OR id_tipo_bloqueo='3')  ORDER BY $filtro ASC";	
	}
 	$impresion=""; 	
	$consulta=mysql_query($sql,$conexion) or die ("ERROR 01: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
	{						
		$id_guia=$fila['id'];
		$id_tipo_bloqueo=$fila['id_tipo_bloqueo'];
		$id_linea=$fila['courier_id_linea'];
		$id_consignatario=$fila['id_consignatario'];
		$piezas=$fila["piezas"]; 
		$peso=$fila["peso"];		

		//**consulta Auxiliar
		$sql2="SELECT nombre FROM courier_linea WHERE id='$id_linea'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$linea=($fila2['nombre'] == "") ? "Sin Asignar" : $fila2['nombre']; 

		//consultas auxiliares Nombre de Funcionario registrado por Seguridad
		$sql_aux="SELECT fg.id,f.nombre,f.no_documento FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia fg ON fg.id_funcionario = f.id WHERE fg.id_guia='$id_guia' AND fg.tipo='C' AND f.id_consignatario='$id_consignatario'";
		$consulta_aux=mysql_query ($sql_aux,$conexion) or die ("ERROR 03: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_aux=mysql_fetch_array($consulta_aux);
		$nombre_funcionario=$fila_aux["nombre"];		  

		//Revision de disponibilidad o no de finalizacion
		if($id_tipo_bloqueo == 3)
		{
			$img_revision='check_green.png';
			$estado_revision='';
		}
		else
		{
			$img_revision='error.png';
			$estado_revision='disabled="disabled"';
		}		
	
		//**consulta Auxiliar
		$sql2="SELECT nombre FROM couriers WHERE id='$id_consignatario'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 04: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$consignatario=$fila2['nombre'];

		//Identificaion de los privilegios de los botones de acciones
		$id_objeto=139; 
		include("config/provilegios_objeto.php");  
		$actBtn1=$activacion;

		$id_objeto=138; 
		include("config/provilegios_objeto.php");  
		$actBtn2=$activacion;

		$impresion .= '	<tr>
							<td class="celda_tabla_principal celda_boton">
								<button type="button" style="width:50px; height:50px;" title="Reincorporar Gu&iacute;a al Inventario de bodega" onclick="openPopup(\'courier_reincorporacion.php?id_guia='.$id_guia.'\',\'new\',\'800\',\'500\',\'scrollbars=1\',true);" '.$actBtn1.'>
									<img src="imagenes/al_principio-act.png" height="29" width="33" />
								</button>							
								<img src="imagenes/poli1.png" height="29" width="33" />
								<img src="imagenes/'.$img_revision.'" height="29" width="33" />
								<button type="button" style="width:50px; height:50px;" title="Finalizaci&oacute;n de la Gu&iacute;a" '.$estado_revision.' onclick="finalizacion('.$id_guia.')" '.$actBtn2.'>
									<img src="imagenes/imprimir-act.png" height="29" width="33" />
								</button>
							</td>						
							<td class="celda_tabla_principal celda_boton">'.$fila['master'].'</td>
							<td class="celda_tabla_principal celda_boton">'.$linea.'</td>
							<td class="celda_tabla_principal celda_boton">'.$consignatario.'</td>
							<td align="right" class="celda_tabla_principal celda_boton">'.$piezas.'</td>
							<td align="right" class="celda_tabla_principal celda_boton">'.$peso.'</td>
							<td class="celda_tabla_principal celda_boton">'.$nombre_funcionario.'</td>							
						</tr>
						';						
	}  
?>
<p class="titulo_tab_principal">Creaci&oacute;n Planilla de Entrega</p>
<br>
<table align="center">
	<tr>
		<td align="center" class="celda_tabla_principal" valign=""><div class="letreros_tabla">Buscar Gu&iacute;a</div></td>
		<td align="center" class="celda_tabla_principal celda_boton"><input type="text" name="busqueda" id="busqueda"></td>
		<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">
			<button type="button" valign="center" onclick="document.location='<?php echo $_SERVER['SCRIPT_NAME'] ?>?busqueda='+document.getElementById('busqueda').value"><img src="imagenes/buscar-act.png" height="20"></button></div>			
		</td>
	</tr>
</table>
<br>
<table align="center">
  <tr>
    <td align="center" class="celda_tabla_principal" valign=""><div class="letreros_tabla">Acciones</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td align="center" class="celda_tabla_principal" valign="center">
    	<div class="letreros_tabla" <?php echo $colorLinea ?>>Linea 
    		<button type="button" valign="center" onclick="document.location='<?php echo $_SERVER['SCRIPT_NAME'] ?>?filtro=courier_id_linea'"><img src="imagenes/filtro.png" height="20"></button>
    	</div>
    </td>
    <td align="center" class="celda_tabla_principal" valign="center">
    	<div class="letreros_tabla" <?php echo $colorCourier ?>>Courier 
    		<button type="button" valign="center" onclick="document.location='<?php echo $_SERVER['SCRIPT_NAME'] ?>?filtro=id_consignatario'"><img src="imagenes/filtro.png" height="20"></button>
    	</div>
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Encargado</div></td>
  </tr>
  <?php echo $impresion; ?>  
</table>

<!-- The Modal -->
  <div class="modal fade" id="myModal2">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Registro Procesado 
          <!--<button type="button" class="close" data-dismiss="modal">&times;</button>         -->
        </div>
        
        <!-- Modal body -->
        <div id="contenido_modal" class="modal-body">
          
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal" onclick="redireccion()">Aceptar</button>
          <!--<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="salida()">No</button>-->
        </div>
        
      </div>
    </div>
  </div>

</body>
</html>
<script language="javascript">
	// función que permite abrir ventanas emergentes con las propiedades deseadas
	function openPopup(url,name,w,h,props,center){
	  l=18;t=18
	  if(center){l=(screen.availWidth-w)/2;t=(screen.availHeight-h)/2}
	  url=url.replace(/[ ]/g,'%20')
	  popup=window.open(url,name,'left='+l+',top='+t+',width='+w+',height='+h+',scrollbars=1'+((props)?','+props:''))
	  props=props||''
	  if(props.indexOf('fullscreen')!=-1){popup.moveTo(0,0);popup.resizeTo(screen.width,screen.height)}
	  popup.focus()
	}

		//********************/
    function finalizacion(id_recibido)
    {
        var datosFormulario={                                
                                id_guia:id_recibido
                            }
        $.get("courier_entrega_finalizacion_guia.php",datosFormulario,resultado_finalizacion);           
    }    
    function resultado_finalizacion(datos_devueltos)
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
                //$("#contenido_modal").html("Guia Finalizada Exitosamente.");        
                //$("#myModal2").modal("show");
				document.location="courier_despacho_opciones.php?id_registro="+respuesta;
            break;
        }
    }

    function redireccion()
    {
		self.close();                
    	window.opener.location='courier_inventario.php';    	
    }    
    // función que permite abrir ventanas emergentes con las propiedades deseadas
    function openPopup(url,name,w,h,props,center)
    {
        l=18;t=18
        if(center){l=(screen.availWidth-w)/2;t=(screen.availHeight-h)/2}
        url=url.replace(/[ ]/g,'%20')
        popup=window.open(url,name,'left='+l+',top='+t+',width='+w+',height='+h+',scrollbars=1'+((props)?','+props:''))
        props=props||''
        if(props.indexOf('fullscreen')!=-1){popup.moveTo(0,0);popup.resizeTo(screen.width,screen.height)}
        popup.focus()
    }
</script>
