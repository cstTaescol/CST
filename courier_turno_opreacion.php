<?php     
    require("config/configuracion.php");    
    if(isset($_POST['linea']))
    {
	    $id_linea=$_POST['linea'];
	    $id_usuario=$_POST['usuario'];    	
    }
    else
    {
    	echo "<span style:'color:red'><strong>Error:</strong></span>No se pudieron Obtener los datos de la sesi&oacute;n, cierre el navegador y vuelva a iniciar la aplicaci&oacute;n<br>Si el problema persiste, informe al Soporte T&eacute;cnico";
    	exit();
    }

    //identificando usuario
    $sql3="SELECT nombre FROM usuario WHERE id='$id_usuario'";
    $consulta3=mysql_query ($sql3,$conexion) or die ("Error 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
    $fila3=mysql_fetch_array($consulta3);
    $usuario=$fila3["nombre"];

    $sql3="SELECT nombre FROM courier_linea WHERE id='$id_linea'";
    $consulta3=mysql_query ($sql3,$conexion) or die ("Error 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
    $fila3=mysql_fetch_array($consulta3);
    $linea=$fila3["nombre"];    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Control de L&iacute;nea</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />    
    <link rel="stylesheet" href="js/bootstrap.min.css" >
    <script src="js/jquery-3.3.1.min.js" ></script>
    <script src="js/popper.min.js" ></script>   
    <script src="js/bootstrap.min.js"></script> 
    <style type="text/css">
    	.flotando 
		    {          
		        border: 1px solid #D2D2D2;
		        border-radius: 8px 8px 8px 8px;          
		        position: fixed;          
		        right: 5px;
		        height: 150px;          
		        width: 100px;
		        text-align: center;
		        opacity: 0.8;          
		    }
    	.contenido
			{
				text-align: center;
				display: none;
			}      
      	.crono_wrapper 
			{
				text-align:center;
				width:200px;
				background-color: black;
				color: green;
			}
	  	.caja 
			{ 
				font-family: sans-serif; 
				font-size: 18px; 
				font-weight: 400; 
				height: 150px;
				width: 300px;
				margin-left: auto;
				margin-right:auto;
				border-radius: 8px;   
			}      
    </style>    
</head>
<body>
<p class="titulo_tab_principal">Operaci&oacute;n de Turnos</p>
<p align="center"><img src="imagenes/logo.png"></p>
<input type="hidden" name="id_linea" id="id_linea" value="<?php echo $id_linea ?>">
<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario ?>">
<input type="hidden" name="id_turno" id="id_turno">
 
 <!-- Pestañas flotantes con marcadores de conteo -->
 <div id="scores">
   <div id="column-left" class="flotando" style="top: 200px;">
      <strong><h3>TURNOS</h3></strong>
  </div>
  <div id="column-left" class="flotando" style="background-image: linear-gradient(-90deg, orange, red); top: 250px;">
    <strong>Espera<hr></strong>
    <div id="turnosEspera" style="font-size: 45px"></div>
  </div>
</div>
<!-- Encabezado-->
<div class="container">
  <h2><?php echo $linea ?></h2>
  <div class="card">
    <div class="card-body"><?php echo $usuario ?></div>
    <div class="crono_wrapper">
      <h2 id="crono">00:00:00</h2>            
    </div>         
  </div>  
</div>

<div id="fase0" class="contenido">  	
  	<table width="550px" align="center">
        <tr>
          <td align="center" class="celda_tabla_principal" colspan="2"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" class="celda_tabla_principal"><div class="letreros_tabla"><img src="imagenes/scanner_1.png" height="150"><br>Oprima el bot&oacuten para Atender un Turno</div></td>
          <td align="center" class="celda_tabla_principal celda_boton">
          	<div class="letreros_tabla">
	           	<button type="button" title="Sin Turnos" disabled="disabled">
	      			<img src="imagenes/btn_rojo.gif" height="70">
	      			<br>
	      			Sin Turnos
	  			</button>  
  			</div>
  		  </td>
		</tr>
	</table>
</div>
<div id="fase1" class="contenido">  	
  	<table width="550px" align="center">
        <tr>
          <td align="center" class="celda_tabla_principal" colspan="2"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" class="celda_tabla_principal"><div class="letreros_tabla"><img src="imagenes/scannerAnimado.gif" height="150"><br>Oprima el bot&oacuten para Atender un Turno</div></td>
          <td align="center" class="celda_tabla_principal celda_boton">
          	<div class="letreros_tabla">
	           	<button type="button" onclick="startFase1()" title="Atender">
	      			<img src="imagenes/btn_verde.gif" height="70">
	      			<br>
	      			Atender
	  			</button>  
  			</div>
  		  </td>
		</tr>
	</table>
</div>


<div id="respuestaFase1" class="contenido">
</div>
<div id="respuestaFase2" class="contenido">
</div>

<!-- The Modal 1-->
  <div class="modal fade" id="myModal1">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title"><div id="titulo_modal1"></div></h4> 
          <button type="button" class="close" data-dismiss="modal">&times;</button>         
        </div>
        
        <!-- Modal body -->
        <div id="contenido_modal1" class="modal-body"></div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Aceptar</button>          
        </div>
        
      </div>
    </div>
  </div>

<!-- The Modal 2 -->

  <div class="modal fade" id="myModal2">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title"><div id="titulo_modal2"></div></h4> 
          <!--<button type="button" class="close" data-dismiss="modal">&times;</button>         -->
        </div>
        
        <!-- Modal body -->
        <div id="contenido_modal2" class="modal-body"></div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal" onclick="">Aceptar</button>
          <!-- <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="salida()">No</button> -->
        </div>
        
      </div>
    </div>
  </div>
</html>
<script type="text/javascript">
$(document).ready(function() 
{    
  startMonitoreo(consultarTurnos,3000);    
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
                            id_linea:'<?php echo $id_linea ?>'                                    
                        }
    $.get("courier_turno_operacion_Estado1.php",datosFormulario,resultado_consultarTurnos);                       
}    
function resultado_consultarTurnos(datos_devueltos)
{    
    var valor = parseInt(datos_devueltos);
    if(valor > 0)
    {        
      $('#turnosEspera').fadeOut("slow");
      $('#turnosEspera').delay(500).fadeIn("slow").html(valor);
      ocultarFase0();
      mostrarFase1();
    }
    else
    {
      $('#turnosEspera').delay(500).fadeIn("slow").html('0');
      ocultarFase1();
      mostrarFase0();
    }
}
//****************************************

//**Fase 1 **////////////////
function ocultarScores()
{
  $('#scores').fadeOut("slow");
}

function mostrarScores()
{
  $('#scores').delay(500).fadeIn("slow");
}

//**Fase 0 **////////////////
function ocultarFase0()
{
  $('#fase0').fadeOut("slow");
}

function mostrarFase0()
{
  $('#fase0').delay(500).fadeIn("slow");
}


//**Fase 1 **////////////////
function ocultarFase1()
{
  $('#fase1').fadeOut("slow");
}

function mostrarFase1()
{
  $('#fase1').delay(500).fadeIn("slow");
}

function startFase1(btn)
{
  //Detencion de monitoreo y ocultamiento de contenido
  limpiar();
  stopMonitoreo();
  ocultarScores();
  ocultarFase1();
  cronometrar('Empezar');
  var datosFormulario={                                
                        id_linea:$("#id_linea").val(),
                        id_usuario:$("#id_usuario").val()                        
                      }
  $.get("courier_turno_operacion_Fase1.php",datosFormulario,resultado_Fase1);

}
function resultado_Fase1(datos_devueltos)
{    
  /* var coderror = eval(arreglos[1]); */
  var respuesta = datos_devueltos.split("**-**");  
  $("#id_turno").val(respuesta[0]);
  $('#respuestaFase1').delay(500).fadeIn("slow").html(respuesta[1]);      
}

function startFase2(btn)
{
  //Detencion de monitoreo y ocultamiento de contenido 
  var datosFormulario={                                
                          id_turno:$("#id_turno").val(),
                          id_linea:$("#id_linea").val(),
                          id_usuario:$("#id_usuario").val(),
                          boton:btn
                      }
  $.get("courier_turno_operacion_Fase2.php",datosFormulario,resultado_Fase2);
}
function resultado_Fase2(datos_devueltos)
{          
      $('#respuestaFase1').fadeOut("slow");
      $('#respuestaFase2').delay(500).fadeIn("slow").html(datos_devueltos); 
      $('#respuestaFase2').delay(2500).fadeOut("slow");
      cronometrar('Detener');
      startMonitoreo(consultarTurnos,3000);                  
      mostrarScores();
}

function limpiar()
{
	$("#id_turno").val("");
	$('#respuestaFase1').html("");      
	$('#respuestaFase2').html("");	
}


/* Seccion de CRONOMETRO *************************************************************/
var inicio=0;
var timeout=0;   
function cronometrar(elemento)
{
  if(timeout==0)
  {
    // empezar el cronometro
    elemento.value="Detener";
    // Obtenemos el valor actual
    inicio=vuelta=new Date().getTime();
    // iniciamos el proceso
    funcionando();
  }else{
    // detemer el cronometro
    elemento.value="Empezar";
    clearTimeout(timeout);
    timeout=0;
  }
}   
function funcionando()
{
  // obteneos la fecha actual
  var actual = new Date().getTime();
  // obtenemos la diferencia entre la fecha actual y la de inicio
  var diff=new Date(actual-inicio);
  // mostramos la diferencia entre la fecha actual y la inicial
  var result=LeadingZero(diff.getUTCHours())+":"+LeadingZero(diff.getUTCMinutes())+":"+LeadingZero(diff.getUTCSeconds());
  document.getElementById('crono').innerHTML = result;
  // Indicamos que se ejecute esta función nuevamente dentro de 1 segundo
  timeout=setTimeout("funcionando()",1000);
}   
/* Funcion que pone un 0 delante de un valor si es necesario */
function LeadingZero(Time) 
{
  return (Time < 10) ? "0" + Time : + Time;
}
//*****************************************************************************************

</script>