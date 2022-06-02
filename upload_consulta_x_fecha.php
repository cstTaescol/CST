<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$tipo=$_REQUEST['tipo'];
$impresion="";
switch($tipo)
{
	case 'PLANILLA_CARGUE':
		$titulo='Busqueda Planilla de Cargue';
		if(isset($_REQUEST['rangoini']) &&  isset($_REQUEST['rangofin']))
		{
			$id_objeto=112; 
			include("config/provilegios_objeto.php");  
			$btnActivacion1=$activacion;
			
			$id_objeto=113; 
			include("config/provilegios_objeto.php");  
			$btnActivacion2=$activacion;
			
			$fecha_inial=$_REQUEST['rangoini'];
			$fecha_final=$_REQUEST['rangofin'];		
			//Consulta datos
			$sql="SELECT * FROM planilla_cargue WHERE fecha BETWEEN '$fecha_inial' AND '$fecha_final' ORDER BY id DESC";
			$consulta=mysql_query ($sql,$conexion) or die ("ERROR 01: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$nfilas=mysql_num_rows($consulta);
			if ($nfilas > 0)
			{
				$impresion .='
				<table align="center">
					<tr>
					  <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
					  <td class="celda_tabla_principal"><div class="letreros_tabla">Hora</div></td>
					  <td class="celda_tabla_principal"><div class="letreros_tabla">Archivo</div></td>
					  <td class="celda_tabla_principal"><div class="letreros_tabla">Ver</div></td>
					  <td class="celda_tabla_principal"><div class="letreros_tabla">Eliminar</div></td>
					</tr>
				';	
				while($fila=mysql_fetch_array($consulta))
				{
					$fecha=$fila['fecha'];
					$hora=$fila['hora'];
					$archivo=$fila['archivo'];
					$id=$fila['id'];
					$impresion = $impresion . '
								</tr>
								  <td class="celda_tabla_principal celda_boton">'.$fecha.'</td>
								  <td class="celda_tabla_principal celda_boton">'.$hora.'</td>
								  <td class="celda_tabla_principal celda_boton">'.$archivo.'</td>
								  <td class="celda_tabla_principal celda_boton" align="center">
								  	<button type="button" title="Ver" onClick="openPopup(\'fotos/adjuntos/'.$archivo.'\',\'new\',\'700\',\'450\',\'scrollbars=1\',true);" '.$btnActivacion1.'>
										<img src="imagenes/buscar-act.png" width="33" height="29" />
									</button>								  
								  </td>
								  <td class="celda_tabla_principal celda_boton" align="center">
								  	<button type="button" title="Eliminar" onClick="document.location=\'upload_eliminar.php?ruta=fotos/adjuntos/'.$archivo.'&id='.$id.'&retorno='.$_SERVER['SCRIPT_NAME'].'&parametro1='.$fecha_inial.'&parametro2='.$fecha_final.'\';" '.$btnActivacion2.'>
										<img src="imagenes/eliminar-act.png" width="33" height="29" />
									</button>
								  </td>
								';					
				}
			}			
		}
	break;

	case 'PLANILLA_DESPALETIZAJE':
		$titulo='Busqueda Planilla de Despalet.';
		if(isset($_REQUEST['rangoini']) &&  isset($_REQUEST['rangofin']))
		{
			$id_objeto=115; 
			include("config/provilegios_objeto.php");  
			$btnActivacion1=$activacion;
			
			$id_objeto=116; 
			include("config/provilegios_objeto.php");  
			$btnActivacion2=$activacion;
			
			$fecha_inial=$_REQUEST['rangoini'];
			$fecha_final=$_REQUEST['rangofin'];		
			//Consulta datos
			$sql="SELECT * FROM planilla_despaletizaje WHERE fecha BETWEEN '$fecha_inial' AND '$fecha_final' ORDER BY id DESC";
			$consulta=mysql_query ($sql,$conexion) or die ("ERROR 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$nfilas=mysql_num_rows($consulta);
			if ($nfilas > 0)
			{
				$impresion .='
				<table align="center">
					<tr>
					  <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
					  <td class="celda_tabla_principal"><div class="letreros_tabla">Hora</div></td>
					  <td class="celda_tabla_principal"><div class="letreros_tabla">Archivo</div></td>
					  <td class="celda_tabla_principal"><div class="letreros_tabla">Ver</div></td>
					  <td class="celda_tabla_principal"><div class="letreros_tabla">Eliminar</div></td>
					</tr>
				';	
				while($fila=mysql_fetch_array($consulta))
				{
					$fecha=$fila['fecha'];
					$hora=$fila['hora'];
					$archivo=$fila['archivo'];
					$id=$fila['id'];
					$impresion = $impresion . '
								</tr>
								  <td class="celda_tabla_principal celda_boton">'.$fecha.'</td>
								  <td class="celda_tabla_principal celda_boton">'.$hora.'</td>
								  <td class="celda_tabla_principal celda_boton">'.$archivo.'</td>
								  <td class="celda_tabla_principal celda_boton" align="center">
								  	<button type="button" title="Ver" onClick="openPopup(\'fotos/adjuntos/'.$archivo.'\',\'new\',\'700\',\'450\',\'scrollbars=1\',true);" '.$btnActivacion1.'>
										<img src="imagenes/buscar-act.png" width="33" height="29" />
									</button>								  
								  </td>
								  <td class="celda_tabla_principal celda_boton" align="center">
								  	<button type="button" title="Eliminar" onClick="document.location=\'upload_eliminar.php?ruta=fotos/adjuntos/'.$archivo.'&id='.$id.'&tipo='.$tipo.'&parametro1='.$fecha_inial.'&parametro2='.$fecha_final.'\';" '.$btnActivacion2.'>
										<img src="imagenes/eliminar-act.png" width="33" height="29" />
									</button>
								  </td>
								';					
				}
			}			
		}
	break;
}
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
<p class="titulo_tab_principal"><?php echo $titulo ?></p>
<form name="buscar" method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" onsubmit="return validar();">
  <table align="center" cellpadding="0" cellspacing="0" style="width:650px" >
    <tr>
      <td>
      <p>&nbsp;</p>
      <table align="center" style="width:650px">
        <tr>
              <td width="150" class="celda_tabla_principal"><div class="letreros_tabla">Rango de Fecha</div></td>
              <td width="250px" class="celda_tabla_principal celda_boton">
                    <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo ?>" />
                    
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
      </table>
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
            <button type="button" tabindex="14" onclick="document.location='upload_menu_opciones.php?tipo=<?php echo $tipo ?>'">
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
<p><?php echo $impresion ?></p>
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
</script>
<script language="javascript">
<!-- función que permite abrir ventanas emergentes con las propiedades deseadas -->
function openPopup(url,name,w,h,props,center){
	l=18;t=18
	if(center){l=(screen.availWidth-w)/2;t=(screen.availHeight-h)/2}
	url=url.replace(/[ ]/g,'%20')
	popup=window.open(url,name,'left='+l+',top='+t+',width='+w+',height='+h+',scrollbars=1'+((props)?','+props:''))
	props=props||''
	if(props.indexOf('fullscreen')!=-1){popup.moveTo(0,0);popup.resizeTo(screen.width,screen.height)}
	popup.focus()
}
<!-- -------------------------------------------------------------------------- -->
</script>

