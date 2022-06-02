<?php
	session_start(); /*     "This product includes PHP software, freely available from */
	include("configuracion.php");	
	$sql_cfg="SELECT * FROM configuracion WHERE id='1'";
	$consulta_cfg=mysql_query ($sql_cfg,$conexion) or die (exit('Error al Obtener Configuracion '.mysql_error()));
	$fila_cfg=mysql_fetch_array($consulta_cfg);
	$fila_cfg['VERSION'];
	$fila_cfg['PROGRAMA'];
	$fila_cfg['SIGLA'];	
	$fila_cfg['PROGINI'];
	$fila_cfg['EMPRESA'];
	$fila_cfg['AUTOR'];
	$fila_cfg['CLAVE'];
	$fila_cfg['CLIENTE'];
	$fila_cfg['URLCLIENTE'];
	$fila_cfg['SERVIDORDECORREO'];
	$fila_cfg['URLAPLICACION'];
	$fila_cfg['CORREOSOPORTE'];
	$fila_cfg['CORREOSEGUIMIENTO'];
	$fila_cfg['TIEMPOSESION'];	
	$fila_cfg['FOTO_MAX_SIZE'];
?>
<html>
<head>
<style type="text/css">
	body,td,th {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 14px;
		color: #0F0;
		font-weight: bold;
	}
	body {
		background-color: #000;
	}
	.textorespuesta{
		color:#FFF;
	}
</style>
</head>
<body>
<p align="center"><font size="+2">PARAMETROS DEL SISTEMA</font><br></p>
<hr>
<form method="post" name="frm_guardar" id="frm_guardar">
    VERSION: <font class="textorespuesta"><?php echo $fila_cfg['VERSION']; ?></font><br />
    PROGRAMA: <font class="textorespuesta"><?php echo $fila_cfg['PROGRAMA']; ?></font><br />
    DESARROLLADOR: <font class="textorespuesta"><?php echo $fila_cfg['EMPRESA']; ?></font><br />
    CLIENTE: <font class="textorespuesta"><?php echo $fila_cfg['CLIENTE']; ?></font><br />
    LICENCIA: <font class="textorespuesta"><?php echo $fila_cfg['CLAVE']; ?></font><br />
    CORREOSOPORTE: <font class="textorespuesta"><?php echo $fila_cfg['CORREOSOPORTE']; ?></font><br />    
    <table style="border:solid #FFF">
    	<tr>
        	<td>SIGLA</td>
            <td><input type="text" name="SIGLA" id="SIGLA" value="<?php echo $fila_cfg['SIGLA']; ?>" maxlength="50"/></td>
        </tr>
        <tr>            
            <td>URL CLIENTE</td>
            <td><input type="text" name="URLCLIENTE" id="URLCLIENTE" value="<?php echo $fila_cfg['URLCLIENTE']; ?>" maxlength="200" /></td>
        </tr>
        <tr>            
            <td>SERVIDOR DE CORREO</td>
            <td><input type="text" name="SERVIDORDECORREO" id="SERVIDORDECORREO" value="<?php echo $fila_cfg['SERVIDORDECORREO']; ?>" maxlength="200" /></td>
        </tr>
        <tr>            
            <td>URL APLICACION</td>
            <td><input type="text" name="URLAPLICACION" id="URLAPLICACION" value="<?php echo $fila_cfg['URLAPLICACION']; ?>" maxlength="200"/></td>
        </tr>
        <tr>            
            <td>CORREO SEGUIMIENTO</td>
            <td><input type="text" name="CORREOSEGUIMIENTO" id="CORREOSEGUIMIENTO" value="<?php echo $fila_cfg['CORREOSEGUIMIENTO']; ?>" maxlength="200"/></td>
        </tr>
        <tr>            
            <td>TAMANO MAXIMO DE LAS FOTOS</td>
            <td><input type="text" name="FOTO_MAX_SIZE" id="FOTO_MAX_SIZE" value="<?php echo $fila_cfg['FOTO_MAX_SIZE']; ?>" onKeyPress="return numeric(event)" maxlength="7" /></td>
        </tr>
        <tr>            
            <td>TIEMPO MAXIMO DE SESION</td>
            <td>				
				<select name="TIEMPOSESION" id="TIEMPOSESION">
					<option value="300000" <?php if ($fila_cfg['TIEMPOSESION'] == "300000") echo "selected"  ?>>5</option>
					<option value="600000" <?php if ($fila_cfg['TIEMPOSESION'] == "600000") echo "selected"  ?>>10</option>
					<option value="900000" <?php if ($fila_cfg['TIEMPOSESION'] == "900000") echo "selected"  ?>>15</option>
				</select>
				MINUTOS
			</td>
        </tr>
        <tr>
        	<td colspan="2" align="center">
            	<button type="button" name="guardar" id="guardar"  onClick="return validar();">
            		Guardar
                </button>
            	<button type="reset" name="reset" id="reset"> 
            		Resetear 
                </button>
            	<button type="button" name="cancelar" id="cancelar" onClick="document.location='../cfg_rt.php'">
            		Cancelar
                </button> 
            </td>
        </tr>
    </table>
    <div id="respuesta" style="left:auto"></div>
</form>
</body>
</html>
<script type="text/javascript" src="../js/mootools-core-1.4.5-full-nocompat.js"></script>
<script type="text/javascript" src="../js/mootools-more-1.4.0.1.js"></script>
<script language="javascript">
function validar()
{	
	
	if ($('SIGLA').value=="")
	{
		alert("Atencion: Se requiere la SIGLA.");
		$('SIGLA').focus();
		return(false);
	}

	if ($('URLCLIENTE').value=="")
	{
		alert("Atencion: Se requiere la URL del CLIENTE.");
		$('URLCLIENTE').focus();
		return(false);
	}

	if ($('SERVIDORDECORREO').value=="")
	{
		alert("Atencion: Se requiere la URL del SERVIDOR DE CORREO.");
		$('SERVIDORDECORREO').focus();
		return(false);
	}

	if ($('URLAPLICACION').value=="")
	{
		alert("Atencion: Se requiere la URL de la APLICACION.");
		$('URLAPLICACION').focus();
		return(false);
	}

	if ($('CORREOSEGUIMIENTO').value=="")
	{
		alert("Atencion: Se requiere la direccion de CORREO DE SEGUIMIENTO.");
		$('CORREOSEGUIMIENTO').focus();
		return(false);
	}

	if ($('FOTO_MAX_SIZE').value=="")
	{
		alert("Atencion: Se requiere la TAMANO MAXIMO DE LAS FOTOS.");
		$('FOTO_MAX_SIZE').focus();
		return(false);
	}

	guardar_form();				
}
	

function guardar_form()
{
	var peticion = new Request(
	{
		url: 'mod_cfg_salvar.php',
		method: 'post',
		onRequest: function()
		{
			$('respuesta').innerHTML='PROCESANDO:...';
			$('guardar').disabled=true;
			$('reset').disabled=true;
		},			
		onSuccess: function(responseText)
		{
			var respuesta;
			respuesta=eval(responseText);
			switch (respuesta)
			{
				case 0:
					$('respuesta').innerHTML='<font class="textorespuesta">Error al guardar los datos.</font>';
				break;
				case 1:
					$('respuesta').innerHTML='<font class="textorespuesta">Configuracion almacenada de manera exitosa...</font>';
					alert('Registro almacenado de manera exitosa...');
					document.location='<?php echo $_SERVER['SCRIPT_NAME'] ?>';
				break;
			}
			$('guardar').disabled=false;
			$('reset').disabled=false;
		},
		onFailure: function()
		{
			$('respuesta').innerHTML='<font class="textorespuesta">Error al guardar los datos.</font>';
			$('guardar').disabled=false;
			$('reset').disabled=false;
		}
	}
	);
	peticion.send($('frm_guardar'));
}
	//Validacion de campos numéricos
	function numeric(e) 
	{ // 1
	    tecla = (document.all) ? e.keyCode : e.which; // 2
	    if (tecla==8) return true; // 3
	    patron =/[0-9\n]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
	    te = String.fromCharCode(tecla); // 5
	    return patron.test(te); // 6
	} 
</script>
