<?php     
    require("config/configuracion.php");    
/*
    //identificando usuario
    $sql3="SELECT nombre FROM usuario WHERE id='$id_usuario'";
    $consulta3=mysql_query ($sql3,$conexion) or die ("Error 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
    $fila3=mysql_fetch_array($consulta3);
    $usuario=$fila3["nombre"];

    $sql3="SELECT nombre FROM courier_linea WHERE id='$id_linea'";
    $consulta3=mysql_query ($sql3,$conexion) or die ("Error 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
    $fila3=mysql_fetch_array($consulta3);
    $linea=$fila3["nombre"];    
*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Monitor de Turnos</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="js/bootstrap.min.monitor.css" >
  <script src="js/jquery-3.3.1.min.js" ></script>
  <script src="js/popper.min.js" ></script>   
  <script src="js/bootstrap.min.js"></script> 
  <style>
  .fakeimg {
    height: 200px;    
    /*background: #C6DFE6;*/
    background-image: linear-gradient(-90deg, #FFFFFF, #C6DFE6);
    border-radius: 45px 0px 45px 0px; 
  }

  body
  {
  	/*background-image: linear-gradient(rgba(0, 0, 255, 0.5), rgba(255, 255, 0, 0.5)),url("imagenes/fondo_monitor_turnos.png");*/
	background-image: url("imagenes/fondo_monitor_turnos.png");
  }
  </style>
</head>
<body>
	<audio id="sound" src="sonidos/doorbell.mp3"></audio>
	<button type="button" height="15" onclick="document.getElementById('sound').play()"0.>
		<img src="imagenes/bocina.png" height="15" >
	</button>        

<!--
  <button onclick="document.getElementById('demo').pause()">Pausar el Audio</button>
  <button onclick="document.getElementById('demo').volume+=0.1">Aumentar el Volumen</button>
  <button onclick="document.getElementById('demo').volume-=0.1">Disminuir el Volumen</button>
</div>
-->
  <div class="container" style="margin-top:30px">
    <div class="row">
      <!-- Stikers de historial de Turnos -->
      <div class="col-sm-4">
      	<div align="center" style="color: #FFFFFF">
        	<h1>Turnos</h1>    
    	</div>
        <div id="historialTurnos">  
        </div>
      </div>
      <!-- Contenedor de columna con encabezado y  Mensaje de Turno Actualmente Solicitado -->
      <div class="col-sm-8">
        <br>
        <br>
        <div align="center" style="color: #FFFFFF">
        	<h1>Turno Actual</h1>
        </div>
        <div align="center" style="color: orange">
        	<h3>Est&eacute; atento a la aparici&oacute;n de su turno...</h3>
        </div>
        <!-- Mensaje de Turno Actualmente Solicitado -->
        <div style="text-align: center; color: #000000;">
          <div id="contenido">
        </div>
      </div>    
    </div>
  </div>

</body>
</html>
<script type="text/javascript">
$(document).ready(function() 
{    
  startMonitoreo(consultarTurnos,6000);    
});

//Monitoreo de Turnos de busqueda te turnos pendientes por atender
var interval = null;    
function startMonitoreo(func, time) {
    interval = setInterval(func, time);
}
function stopMonitoreo() {
    clearInterval(interval);
}
//******************************************

//Consulta de turnos pendiente por atender
function consultarTurnos()
{    
    var datosFormulario={                                
                            //accion:'start'                                    
                        }
    $.get("courier_turno_monitor_1_consultarTurnos.php",datosFormulario,resultado_consultarTurnos);                       
}    
function resultado_consultarTurnos(datos_devueltos)
{    
   var respuesta = datos_devueltos.split("**-**");  
   var cantidad = parseInt(respuesta[0]);   
   $('#contenido').fadeIn("slow").html(respuesta[1]);
   $('#contenido').delay(4000).fadeOut("slow");
   $('#historialTurnos').fadeIn("slow").html(respuesta[2]);      
   if(cantidad > 0)
   {
      reproduccion();
   }     
}
//****************************************
function reproduccion()
{
  document.getElementById('sound').play();
}
</script>