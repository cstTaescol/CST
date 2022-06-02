<?php
$id_usuario=$_SESSION['id_usuario'];
$sql="SELECT nombre FROM usuario WHERE id=$id_usuario";
$consulta=mysql_query($sql,$conexion);
$fila=mysql_fetch_array($consulta);
$usuario=$fila["nombre"];
?>
<link href="tema/estilo.css" rel="stylesheet" type="text/css" />
<link href="js/estilo_menu.css" rel="stylesheet" type="text/css" /> 
<!-- -->

<table width="100%" border="0">
  <tr>
    <td width="33%" rowspan="3" align="center"><img src="imagenes/logo.png" alt="" align="absmiddle" /></td>
    <td width="34%">&nbsp;</td>
    <td width="33%"colspan="2"><div align="center" id="hora" style="position:relative; background-color:#FFF; width:70px; left:150px; border-radius: 1em; border-color: #999; border-width: 2px; border-style: solid;"></div></td>
  </tr>
  <tr>
    <td rowspan="2" align="center"><p style="font-weight:bold; font-size:18px">SISTEMA INTEGRAL DE CARGA</p></td>
    <td rowspan="2" align="right"><img src="imagenes/sesion.png" align="absmiddle"></td>
    <td>Sesi&oacute;n:</td>
  </tr>
  <tr>
    <td><?php echo $usuario; ?></td>
  </tr>
</table>
<script type="text/javascript" src="js/mootools-core-1.4.5-full-nocompat.js"></script>
<script type="text/javascript" src="js/mootools-more-1.4.0.1.js"></script>
<table align="center" border="0">
	<tr>
		<td	> 
        	<hr />       	
            <div class="menu" align="center">
                <ul class="nav">
                    <!-- Archivo -->
                    <li class="titulos_menu_principal">
                            <a href="#">Archivo <img src="imagenes/menu_down.png" border="0" align="absmiddle" /></a>
                            <ul>
                                <li><a href="contrasena.php">Cambio de Clave</a></li>
                                <li><a href="usuario_menu.php">Usuarios</a></li>
                                <li><a href="#">Tablas Param&eacute;tricas<span class="flecha"><img src="imagenes/menu_next.png" border="0" align="absmiddle" /></span></a>
                                    <ul style="z-index:1;">
                                        <li><a href="consulta_identificar_parametricas.php?tabla=aerolinea" >Aerol&iacute;neas</a></li>
                                        <li><a href="consulta_identificar_parametricas.php?tabla=agente_carga" >Agente de Carga</a></li>
                                        <li><a href="consulta_identificar_parametricas.php?tabla=conductor" >Conductores</a></li>
                                        <li><a href="consulta_identificar_parametricas.php?tabla=consignatario" >Consignatarios</a></li>
                                        <li><a href="#">Couriers<span class="flecha"><img src="imagenes/menu_next.png" border="0" align="absmiddle" /></span></a>
                                            <ul style="z-index:2;">
                                                <li><a href="consulta_identificar_parametricas.php?tabla=couriers">Couriers</a></li>
                                                <li><a href="consulta_identificar_parametricas.php?tabla=courier_funcionario_entidad" >Funcionarios de Entidades</a></li>
                                                <li><a href="consulta_identificar_parametricas.php?tabla=courier_funcionario" >Funcionarios del Courier</a></li>
                                                <li><a href="consulta_identificar_parametricas.php?tabla=vehiculos_courier" >Veh&iacute;culos del Courier</a></li>
                                            </ul>
                                        </li>                                        
                                        <li><a href="consulta_identificar_parametricas.php?tabla=deposito" >Dep&oacute;sitos</a></li>
                                        <li><a href="consulta_identificar_parametricas.php?tabla=embarcador" >Embarcador</a></li>
                                        <li><a href="consulta_identificar_parametricas.php?tabla=feriados" >Feriados</a></li>
                                        <li><a href="consulta_identificar_parametricas.php?tabla=movilizacion" ><font size="-1">(In)Movilizaciones</font></a></li>
                                        <li><a href="consulta_identificar_parametricas.php?tabla=rutas" >Rutas</a></li>                                        
                                        <li><a href="consulta_identificar_parametricas.php?tabla=transportador" >Transportadores</a></li>
                                        <li><a href="consulta_identificar_parametricas.php?tabla=vehiculos" >Veh&iacute;culos</a></li>                                        
                                    </ul>
                                </li>
                                <li><a href="SiPall/index.php" target="_top">Sistema de Pallets</a></li>
                                <li><a href="cerrar_sesion.php">Salir</a></li>
                            </ul>
                    </li>
                    <!-- Vuelos -->
                    <li class="titulos_menu_principal">
                        <a href="#">Vuelos <img src="imagenes/menu_down.png" border="0" align="absmiddle" /></a>
                        <ul>
                            <li><a href="guia_selector_aerolinea.php" ><img src="imagenes/descargar-act.png" width="21" height="22" border="0" align="absmiddle" /> Registro Guias</a></li>
                            <li><a href="#">1.Creaci&oacute;n Vuelo <img src="imagenes/menu_next.png" border="0" align="absmiddle" /></a>
                                <ul style="z-index:1;">
                                    <li><a href="vuelo_nuevo1.php">Vuelo Nuevo</a></li>
                                    <li><a href="vuelo_seleccionar.php?opcion=addguia">Modificar Asignaci&oacute;n</a></li>
                                </ul>
                            </li>
                            <li><a href="#">2.Manifestar <img src="imagenes/menu_next.png" border="0" align="absmiddle" /></a>
                                <ul style="z-index:1;">
                                    <li><a href="vuelo_seleccionar.php?opcion=manifestar">Manifestar</a></li>
                                    <li><a href="vuelo_seleccionar.php?opcion=modmanifiesto" >Modificar</a></li>
                                </ul>
                            </li>
                            <li><a href="vuelo_seleccionar.php?opcion=arribo">3.Arribo</a></li>
                            <li><a href="vuelo_seleccionar.php?opcion=findescargue">4.Fin Descargue</a></li>
                            <li><a href="vuelo_seleccionar.php?opcion=inconsistencias">5.Inconsistencias</a></li>                
                        </ul>
                    </li>
                    <!-- Inventarios -->
                    <li class="titulos_menu_principal">
                        <a href="#">Inventarios <img src="imagenes/menu_down.png" border="0" align="absmiddle" /></a>
                        <ul>
                            <li><a href="inventario_general.php">General</a></li>
                            <li><a href="inventario_sobrantes.php">Sobrantes</a></li>
                            <li><a href="inventario_faltantes.php">Faltantes</a></li>
                            <li><a href="inventario_pausas.php">Pausadas</a></li>
                            <li><a href="inventario_pre-alerta.php">Pre-Alerta <img src="imagenes/avion1.png" height="22" border="0" align="absmiddle" /></a></li>                            
                        </ul>            
                    </li>
                    <!-- Despachos -->
                    <li class="titulos_menu_principal">
                        <a href="#">Despachos <img src="imagenes/menu_down.png" border="0" align="absmiddle" /></a>
                        <ul>
                            <li><a href="despacho_transportador1.php">Transportador<span class="flecha"><img src="imagenes/menu_next.png" border="0" align="absmiddle" /></span></a>
                                    <ul style="z-index:1;">
                                        <li><a href="despacho_transportador1.php">Remesa <img src="imagenes/camion.png" border="0" align="absmiddle" /></a></li>
                                        <li><a href="upload_menu_opciones.php?tipo=PLANILLA_CARGUE">Planilla de Cargue</a></li>
                                    </ul>                        
                            <li><a href="despacho_directo1.php">Descargue Directo</a></li>
                            <li><a href="despacho_cabotaje1.php">Cabotaje</a></li>
                            <li><a href="despacho_trasbordo1.php">Trasbordo</a></li>
                            <li><a href="despacho_correo0.php">Correo</a></li>
                            <li><a href="despacho_otros1.php">Otros</a></li>
                            <li><a href="despacho_reasignacion1.php">Reasignaci&oacute;n</a></li>
                        </ul>            
                    </li>
                    <!-- Consultas -->
                    <li class="titulos_menu_principal">
                        <a href="#">Consultas <img src="imagenes/menu_down.png" border="0" align="absmiddle" /></a>
                        <ul>
                            <li><a href="consulta_menu_guias.php" >Gu&iacute;a</a></li>  
                            <li><a href="consulta_tracking.php" >Tracking</a></li>
                            <li><a href="consulta_despachos.php" >Despachos</a></li>    
                            <li><a href="consulta_reportes.php" ><img src="imagenes/imprimir-act.png" width="25" height="25" align="absmiddle" border="0" /> Reportes</a></li>             
                        </ul>            
                    </li>
                    <!-- Operaciones -->
                    <li class="titulos_menu_principal">
                        <a href="#">Operaciones <img src="imagenes/menu_down.png" border="0" align="absmiddle" /></a>
                        <ul>
                            <li><a href="despacho_retorno1.php" ><img src="imagenes/atras-act.png" width="20" height="20" align="absmiddle" border="0" />Retorno</a></li>
                            <li><a href="cumplido_remesa1.php">Cumplidos</a></li>
                            <li><a href="consulta_preinspeccion.php">Pre-Inspecciones</a></li>
                            <li><a href="planilla_recepcion1.php">Planilla de Recepci&oacute;n</a></li>    
                            <li><a href="liberaciones_listar_guias.php">Liberaciones</a></li>
                            <li><a href="despacho_transportador1.php">Seguridad<span class="flecha"><img src="imagenes/menu_next.png" border="0" align="absmiddle" /></span></a>
                                <ul style="z-index:1;">
                                    <li><a href="consulta_guia_buscar.php?origen=verificacion">Verificacion Peso <img src="imagenes/camion.png" border="0" align="absmiddle" /></a></li>
                                    <li><a href="upload_menu_opciones.php?tipo=PLANILLA_DESPALETIZAJE" >Planilla de Despaletizaje</a></li>
                                </ul>                        
                            <li><a href="correcciones.php">Correcciones</a></li>
                        </ul>            
                    </li>
                    <!-- Bodega -->
                    <li class="titulos_menu_principal">
                        <a href="#">Bodega <img src="imagenes/menu_down.png" border="0" align="absmiddle" /></a>
                        <ul>
                            <li><a href="vuelo_seleccionar.php?opcion=despaletizaje">Despaletizaje</a></li>
                            <li><a href="ubicacion1.php">Ubicaci&oacute;n</a></li>
                            <li><a href="registro_fotografico_menu.php">Reg&iacute;stro Fotog&aacute;fico</a></li>
                        </ul>            
                    </li>
                     <!-- Courier -->  
                    <li class="titulos_menu_principal">
                        <a href="#">Courier <img src="imagenes/menu_down.png" border="0" align="absmiddle" /></a>
                        <ul>
                            <li><a href="courier_guia_registro.php">Crear Guia</a></li>                            
                            <li><a href="#">Turno<img src="imagenes/menu_next.png" border="0" align="absmiddle" /></a>
                                <ul style="z-index:1;">
                                    <li><a href="courier_turno_crear.php">Crear</a></li>
                                    <li><a href="courier_turno_cola.php">Consultar Cola</a></li>
                                    <li><a href="courier_turno_linea.php">Linea</a></li>
                                    <li><a href="courier_turno_monitor.php" target="_blank">Monitor</a></li>
                                </ul>
                            </li>                            
                            <li><a href="courier_inventario.php">Inventario General <img src="imagenes/gohome.png" height="21" border="0" align="center" /></a></li>
                            <li><a href="#">Actuaci&oacute;n Aduanera <img src="imagenes/menu_next.png" border="0" /></a>
                                <ul style="z-index:2;">
                                    <li><a href="courier_inventario_AA.php">Inventario</a></li>
                                    <li><a href="courier_consulta_entregaAA.php">Consultar</a></li>
                                </ul>
                            </li>                            
                            <li><a href="#">Inventario Entregas <img src="imagenes/menu_next.png" border="0" /></a>
                                <ul style="z-index:2;">
                                    <li><a href="courier_inventario_entrega.php">Crear</a></li>
                                    <li><a href="courier_consulta_entregaXfecha.php">Consultar</a></li>
                                </ul>
                            </li>
                            <li><a href="courier_consulta_guiasXfecha.php">Consulta Gu&iacute;a <img src="imagenes/buscar-act.png" height="21" border="0" align="center" /></a></li>
                            <li><a href="courier_liberaciones_listar_guias.php">Facturaci&oacute;n</a></li>
                            <li><a href="courier_consulta_reportes.php">Reportes</a></li>
                        </ul>            
                    </li> 
                
                </ul>        
            </div>
            <hr />
		</td>
	</tr>
</table>

<script language="javascript">
fecha = new Date("<?php echo date("d M Y H:i:s"); ?>");
function hora()
{
	var hora=fecha.getHours();
	var minutos=fecha.getMinutes();
	var segundos=fecha.getSeconds();
	if(hora<10){ hora='0'+hora;}
	if(minutos<10){minutos='0'+minutos; }
	if(segundos<10){ segundos='0'+segundos; }
	if (hora>12){ hora=hora-12;} //convierte los datos en Am/Pm pues solo contara las horas hasta las 12 y reinicia el horario.
	fech=hora+":"+minutos+":"+segundos;
	document.getElementById("hora").innerHTML=fech;
	fecha.setSeconds(fecha.getSeconds()+1);
	setTimeout("hora()",1000);
}
window.addEvent('load',function(){
	hora();
});
</script>
