<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("../config/configuracion.php");
if(isset($_REQUEST['id_guia']))
{
	$id_guia=$_REQUEST['id_guia'];
	$_SESSION['id_guia']=$id_guia;
}
if(isset($_REQUEST['tipo']))
{
	$boton=$_REQUEST['tipo'];
	$_SESSION['boton']=$boton;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
	<style type="text/css">
	body {
		padding: 8px;
		margin: 0;
		font-family: 'Lucida Sans Unicode', 'Lucida Grande', sans-serif;
	}
	
	h1 {
		font-size: 30px;
		font-weight: bold;
		color: #202020;
		text-transform: uppercase; 
	}
	
	
	h2 {
		margin-top: 80px;
		font-size: 18px;
		color: #616161; 
	}
	
	ul {
	}
	
	.half {
		width: 600px;
	}
	</style>
	<script type="text/javascript" src="js/mootools-yui-compressed.js"></script>
	<script type="text/javascript" src="Source/MooUpload.js"></script>
	<script type="text/javascript">
	window.addEvent('domready', function() {		
		// Autostart without listview
		var myUpload = new MooUpload('filecontrol', {
			action: 'upload.php',		// Server side upload script
			method: 'html4'				// Use only HTML4 method						
		});
		
	});
	</script>
</head>
<body>
<p align="center">
	<font size="+2" color="#0066FF"><strong>Registro de Adjuntos</strong></font> <img src="../imagenes/cargar-act.png" align="absmiddle" />
	<br>
	<font size="-1" color="green"><strong>AGREGAR</strong></font><br>
    <font size="-1" color="green"><strong>Tamaño Maximo de Archivo:</strong><?php echo ini_get('post_max_size'); ?></font>    
</p>
<div id="filecontrol" class="half"></div>
</p>
</body>
</html>
