<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$buffer="";
$activaciones="";
$desactivaciones="";
$aerolinea=$_SESSION["aerolinea"];
$sql="SELECT master,id FROM guia WHERE id_tipo_guia ='2' AND id_tipo_bloqueo='1' AND id_aerolinea='$aerolinea' ORDER BY master ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$i=1;
while($fila=mysql_fetch_array($consulta))
{
	$buffer .= '<tr>
					<td class="celda_tabla_principal celda_boton">'.$fila['master'].'</td>
					<td align="center" class="celda_tabla_principal celda_boton">
						<input type="checkbox" name="ckmaster_'.$i.'" id="ckmaster_'.$i.'" value="'.$fila['id'].'" />
					</td>
				</tr>';
	$activaciones .= "document.getElementById('ckmaster_$i').checked=true;\n";
	$desactivaciones .= "document.getElementById('ckmaster_$i').checked=false;\n";
	$i++;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="tema/estilo.css" rel="stylesheet" type="text/css" />
</head>
<body>
<p class="titulo_tab_principal">Guias Master</p>
<p align="center" class="asterisco">Seleccione las guias que desea deshabilitar</p>
<form name="formulario" id="formulario"  method="post">
    <table align="center">
    	<tr>
        	<td class="celda_tabla_principal"><div class="letreros_tabla">No Guia</div></td>
        	<td class="celda_tabla_principal">
                <button type="button" onclick="seleccionar();">
                    <img src="imagenes/aceptar-act.png" height="33" width="29" title="Seleccionar Todos" />
                </button>
                <button type="button" onclick="deseleccionar();">
                    <img src="imagenes/aceptar-in.png" height="33" width="29" title="Quitar todas las Selecciones" />
                </button>       
            </td>
    	</tr>
       <?php echo $buffer; ?>
    </table>
    <div id="respuesta1"></div>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="reset" name="reset" id="reset" > <img src="imagenes/descargar-act.png" title="Limpiar" /></button>
            <button type="button" name="guardar" id="guardar" onclick="validar()" > <img src="imagenes/guardar-act.png" title="Guardar" /> </button>
          </td>
        </tr>
    </table>
	<input type="hidden" name="nguias" id="nguias" value="<?php echo $i; ?>" />    
</form>
</body>
</html>
<script type="text/javascript" src="js/mootools-core-1.4.5-full-nocompat.js"></script>
<script type="text/javascript" src="js/mootools-more-1.4.0.1.js"></script>
<script language="javascript">
function seleccionar()
{
	<?php echo $activaciones ?>
}

function deseleccionar()
{
	<?php echo $desactivaciones ?>
}
</script>
<script language="javascript">
function validar()
{
	guardarform();
}

//Guardar capos del formulario
function guardarform()
{
	var peticion = new Request(
	{
		url: 'guia_master_desactivar.php',
		method: 'post',
		onRequest: function()
		{
			$('respuesta1').innerHTML='<p align="center">Procesando...<img src="imagenes/cargando.gif"></p>';
		},			
		onSuccess: function(responseText)
		{
			var respuesta;
			respuesta=""+responseText;
			if (respuesta == "1")
			{
				alert("Registro Almacenado de Manera Exitosa");
				window.opener.location.reload();
				self.close();
			}
			else
			{
				$('respuesta1').innerHTML='<p align="center">Error: Revise la seleccion de guias</p>';	
			}
			
		},
		onFailure: function()
		{
			$('respuesta1').innerHTML='<p align="center">Error al procesar la informacion...</p>';
		}
	}
	);
	peticion.send($('formulario'));
}
//*****

</script>
