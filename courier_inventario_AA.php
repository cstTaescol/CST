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
    <title>Inventario de Guias con Actuacion Aduanera</title>		
	<style>
		button:
		{
			width:50px;
			height:50px;
		}
		button:hover 
		{
			background: rgba(0,0,0,0);
			color: #3a7999;
			box-shadow: inset 0 0 0 3px #3a7999;
		}	
	</style>
</head>
<body>
<?php
	require("menu.php");
	$id_objeto=120;
	include("config/provilegios_modulo.php");
 	$impresion=""; 
	$sql="SELECT id,master,hija,courier_id_posicion,piezas,peso,id_tipo_actuacion_aduanera FROM guia WHERE id_tipo_guia ='6' AND id_tipo_bloqueo='1' ORDER BY master ASC";
	$consulta=mysql_query($sql,$conexion) or die ("ERROR 01: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
	{
		$id_guia=$fila['id'];
		$id_master=$fila['master'];
		$courier_id_posicion=$fila['courier_id_posicion'];		
		$id_tipo_actuacion_aduanera=$fila['id_tipo_actuacion_aduanera'];		
				
		//**consulta Auxiliar de la guia master
		$sql2="SELECT master FROM guia WHERE id='$id_master'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$master=$fila2['master'];
		//**consulta Auxiliar de la posicion
		$sql2="SELECT nombre FROM courier_posiciones WHERE id='$courier_id_posicion'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 03: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);		
		//Identifica el tipo de Jaula de acuerdo a las iniciales del nombre de la posicion
		if (strpos($fila2['nombre'], 'AA') !== false) 
		{
		    $posicion = "Aprehensiones - " . $fila2['nombre'];
		}
		else
		{
			$posicion = "Valores - " . $fila2['nombre'];
		}
		//**consulta Auxiliar de tipo de Actuacion Aduanera
		$sql2="SELECT nombre FROM tipo_actuacion_aduanera WHERE id='$id_tipo_actuacion_aduanera'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 04: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);		
		$tipo_actuacion_aduanera=$fila2['nombre'];


		//Identificaion de los privilegios de los botones de acciones
		$id_objeto=138; 
		include("config/provilegios_objeto.php");  
		$actBtn1=$activacion;	

		$impresion .= '	<tr>
							<td class="celda_tabla_principal celda_boton">
								<button type="button" style="width:50px; height:50px;" title="Acceder a la INFORMACION de la Gu&iacute;a" onclick="document.location=\'consulta_guia_courier_hija.php?id_guia='.$id_guia.'\'">
									<img src="imagenes/info.png" height="29" width="33" />
								</button>	

								<button type="button" style="width:50px; height:50px;" title="Entrega" onclick="openPopup(\'courier_entrega_opciones_guia_hija.php?id_guia='.$id_guia.'\',\'new\',\'800\',\'500\',\'scrollbars=1\',true);" '.$actBtn1.'>
									<img src="imagenes/camion.png" height="29" width="33" />
								</button>	
							</td>						
							<td class="celda_tabla_principal celda_boton">'.$fila['hija'].'</td>
							<td class="celda_tabla_principal celda_boton">'.$master.'</td>							
							<td align="right" class="celda_tabla_principal celda_boton">'.$fila['piezas'].'</td>
							<td align="right" class="celda_tabla_principal celda_boton">'.$fila['peso'].'</td>
							<td class="celda_tabla_principal celda_boton">'.$tipo_actuacion_aduanera.'</td>														
							<td class="celda_tabla_principal celda_boton">'.$posicion.'</td>
						</tr>
						';
	}  
?>
<p class="titulo_tab_principal">Actuaci&oacute;n Aduanera</p>
<br>
<table align="center">
  <tr>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Acciones</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Hija</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Master</div></td>    
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Tipo de Actuaci&oacute;n</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Posicion</div></td>    
  </tr>
  <?php echo $impresion; ?>
</table>
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
    function finalizacion(id_recibido)
    {
        var datosFormulario={                                
                                id_guia:id_recibido
                            }
        $.get("courier_entrega_finalizacion_guia_hija.php",datosFormulario,resultado_finalizacion);           
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
				document.location="courier_despacho_opciones_hija.php?id_registro="+respuesta;
            break;
        }
    }

</script>
