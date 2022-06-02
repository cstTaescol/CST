<?php
session_start(); /*     "This product includes PHP software, freely available from */
require("config/configuracion.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
</style>
</head>
<body>
    <p align="center"><font size="+2">MONITOR DEL SISTEMA</font><br></p>
    <hr>
        <div id="servidor_de_aplicaciones">ESTADO DEL SERVIDOR DE APLICACIONES: ...</div>
        <div id="servidor_de_correo">ESTADO DEL SERVIDOR DE CORREO: ...</div>
        <div id="info_correo">ENVIO DE INFORMACION: ...</div>
        <div id="info_licencia">VERIFICACION LICENCIA POR SERVIDOR: ...</div>
        <!--<div id="monitor_turnos">MONITOREO TURNOS DE COURIERS: ...</div>-->
    <br>
    <br>
    <hr>
</body>
</html>
<script type="text/javascript" src="js/mootools-core-1.4.5-full-nocompat.js"></script>
<script type="text/javascript" src="js/mootools-more-1.4.0.1.js"></script>
<script type="text/javascript">
window.addEvent('load',function(){
	monitor_aplicacion();
	monitor_server_mail();
	monitor_licencia();
	//monitor_turnos_courier();
	setInterval(function(){monitor_aplicacion();},60000); //1000 X Segundo
	setInterval(function(){monitor_server_mail();},60000); // X 60 Segundos
	setInterval(function(){document.location='<?php echo $_SERVER['SCRIPT_NAME']; ?>';},43200000); // X 12 Horas
	//setInterval(function(){monitor_turnos_courier();},15000); // X 10 Segundos
});

function monitor_aplicacion ()
{	
	var peticion = new Request(
	{
		url: 'monitor_drv_mon_apli.php',
		method: 'get',
		onRequest: function()
		{
			$('servidor_de_aplicaciones').innerHTML='ESTADO DEL SERVIDOR DE APLICACIONES: <font color="#FFFFFF">Procesando...</font>';
		},			
		onSuccess: function(responseText)
		{
			var respuesta;
			respuesta=eval(responseText);
			switch (respuesta)
			{
				case 0:
					$('servidor_de_aplicaciones').innerHTML='ESTADO DEL SERVIDOR DE APLICACIONES: <font color="#FFFFFF">Error</font>';
				break;
				case 1:
					$('servidor_de_aplicaciones').innerHTML='ESTADO DEL SERVIDOR DE APLICACIONES: <font color="#FFFFFF">OK</font>';
				break;
			}
		},
		onFailure: function()
		{
			$('servidor_de_aplicaciones').innerHTML='ESTADO DEL SERVIDOR DE APLICACIONES: <font color="#FFFFFF">Error</font>';
		}
	}
	);
	peticion.send();	
}
function monitor_server_mail ()
{	
	var peticion = new Request(
	{
		url: 'monitor_drv_mon_cor.php',
		method: 'get',
		onRequest: function()
		{
			$('servidor_de_correo').innerHTML='ESTADO DEL SERVIDOR DE CORREO: <font color="#FFFFFF">Procesando...</font>';
		},			
		onSuccess: function(responseText)
		{
			var respuesta;
			respuesta=eval(responseText);
			switch (respuesta)
			{
				case 0:
					$('servidor_de_correo').innerHTML='ESTADO DEL SERVIDOR DE CORREO: <font color="#FFFFFF">Error</font>';
				break;
				case 1:
					$('servidor_de_correo').innerHTML='ESTADO DEL SERVIDOR DE CORREO: <font color="#FFFFFF">OK</font>';
					send_mail();
				break;
			}
		},
		onFailure: function()
		{
			$('servidor_de_correo').innerHTML='ESTADO DEL SERVIDOR DE CORREO: <font color="#FFFFFF">Error</font>';
			$('diponibilidad').value=0;
		}
	}
	);
	peticion.send();	
}
function send_mail()
{	
	var peticion = new Request(
	{
		url: 'monitor_drv_mon_send.php',
		method: 'get',
		onRequest: function()
		{
			$('info_correo').innerHTML='ENVIO DE INFORMACION:<font color="#FFFFFF">Procesando...</font>';
		},			
		onSuccess: function(responseText)
		{
			var respuesta;
			respuesta=""+responseText;
			switch (respuesta)
			{
				case "0":
					$('info_correo').innerHTML='ENVIO DE INFORMACION:<font color="#FFFFFF">Error</font>';
				break;
				case "2":
					$('info_correo').innerHTML='ENVIO DE INFORMACION: <font color="#FFFFFF">No se ha configurado el servidor de correo</font>';
				break;
				case "3":
					$('info_correo').innerHTML='ENVIO DE INFORMACION: <font color="#FFFFFF">No Existen Datos Para El Envio De Correo</font>';
				break;
				default:
					window.open(respuesta,'VentanaHija');
					monitor_server_mail();
				break;
			}
		},
		onFailure: function()
		{
			$('info_correo').innerHTML='ENVIO DE INFORMACION:<font color="#FFFFFF">Error</font>';
		}
	}
	);
	peticion.send();	
}
function monitor_licencia()
{	
	var peticion = new Request(
	{
		url: 'monitor_drv_licencia.php',
		method: 'get',
		onRequest: function()
		{
			$('info_licencia').innerHTML='ENVIO DE INFORMACION:<font color="#FFFFFF">Procesando...</font>';
		},			
		onSuccess: function(responseText)
		{
			var respuesta;
			respuesta=""+responseText;
			switch (respuesta)
			{
				case "0":
					$('info_licencia').innerHTML='VERIFICACION LICENCIA POR SERVIDOR: <font color="#FFFFFF">Error</font>';
				default:
					window.open(respuesta,'VentanaHija');
					$('info_licencia').innerHTML='VERIFICACION LICENCIA POR SERVIDOR: <font color="#FFFFFF">OK</font>';
				break;
			}
		},
		onFailure: function()
		{
			$('info_licencia').innerHTML='VERIFICACION LICENCIA POR SERVIDOR: <font color="#FFFFFF">Error</font>';
		}
	}
	);
	peticion.send();	
}
function monitor_turnos_courier()
{	
	var peticion = new Request(
	{
		url: 'monitor_drv_mon_turnos.php',
		method: 'get',
		onRequest: function()
		{
			$('monitor_turnos').innerHTML='MONITOREO TURNOS DE COURIERS:<font color="#FFFFFF">Procesando...</font>';
		},			
		onSuccess: function(responseText)
		{			
			$('monitor_turnos').innerHTML='MONITOREO TURNOS DE COURIERS: <font color="#FFFFFF">'+responseText+'</font>';
		},
		onFailure: function()
		{
			$('monitor_turnos').innerHTML='MONITOREO TURNOS DE COURIERS: <font color="#FFFFFF">Error</font>';
		}
	}
	);
	peticion.send();	
}
</script>