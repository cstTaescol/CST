<?php 
    session_start(); /*     "This product includes PHP software, freely available from
         						<http://www.php.net/software/>". */
    require("config/configuracion.php");
    require("config/control_tiempo.php");

    if(isset($_REQUEST['id_guia']))
    {
        $id_guia=$_REQUEST['id_guia'];
        // Verificacion de Datos Recibidos 
        $sql="SELECT master FROM guia WHERE id = '$id_guia'";
        $consulta=mysql_query($sql,$conexion) or die (exit('Error 1'.mysql_error()));
        $fila=mysql_fetch_array($consulta);
        $master=$fila["master"];    
    }
    else
    {
        echo '
            <script>
                alert("Error: El servidor no pudo obtener la informacion, intentelo de nuevo y comuniquese con el soporte tecnico.");
                document.location="base.php";
            </script>
            ';
        exit();
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />    
    <link rel="stylesheet" href="js/bootstrap.min.css" >
    <script src="js/jquery-3.3.1.min.js" ></script>
    <script src="js/popper.min.js" ></script>   
    <script src="js/bootstrap.min.js"></script>     
    <title>Verificaci&oacute;n</title>
    <style type="text/css">
        input[type=radio] 
        {
          transform: scale(2.5);
        }        
    </style>
</head>
<body>
<p class="titulo_tab_principal">Finalizaci&oacute;n de Gu&iacute;a</p>
<p class="asterisco" align="center">Guia No. <?php echo $master ?> </p>    
<table width="650px" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal" colspan="2"><div class="letreros_tabla">Datos de Evaluaci&oacute;n</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton" >
            <button type="button" name="agregar" id="agregar" onclick="evaluacion1()" tabindex="1" title="Evaluar"> 
                <img src="imagenes/check_blue.png" title="Evaluar" align="absmiddle"/> <br>
                Evaluar
            </button>
      </td>
      <td valign="middle" class="celda_tabla_principal celda_boton">                
            <div id="res_evaluacion1"><li>Asignaci&oacute;n de Veh&iacute;culos</li></div>
            <div id="res_evaluacion2"><li>Asignaci&oacute;n de L&iacute;nea, Funcionarios y Datos de Inicio</li></div>
            <!-- <div id="res_evaluacion3"><li>Conteo de Piezas y Peso</li></div> -->
            <div id="res_evaluacion4"><li>Asignaci&oacute;n de Personal Autorizado por Seguridad</li></div>
            <div id="res_evaluacion5">
                <br><br>
                Esta Guia Posee Actuaciones Aduaneras?<br><br>
                <input type="radio" name="actuacion_aduanera" id="actuacion_aduanerasi" value="1">&nbsp;&nbsp;&nbsp;&nbsp;Si<br><br>
                <input type="radio" name="actuacion_aduanera" id="actuacion_aduanerano" value="0">&nbsp;&nbsp;&nbsp;&nbsp;No
            </div>
      </td>
    </tr>
 </table>   
</form>
<!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Registro Procesado 
          <button type="button" class="close" data-dismiss="modal">&times;</button>         
        </div>
        
        <!-- Modal body -->
        <div id="contenido_modal1" class="modal-body"></div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Aceptar</button>
          <!--<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="salida()">No</button>-->
        </div>
        
      </div>
    </div>
  </div>
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
        <div id="contenido_modal2" class="modal-body">
          
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal" onclick="redireccion()">Aceptar</button>
          <!-- <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="salida()">No</button> -->
        </div>        
      </div>
    </div>
  </div>
</body>
</html>

<script language="javascript">
    var cheklist=0;
    var evaluador=false;    
    function evaluacion1()
    {        
        if(document.getElementById("actuacion_aduanerasi").checked)
        {
            evaluador=true;                        
        }
        if(document.getElementById("actuacion_aduanerano").checked)
        {
            evaluador=true;                        
        }         
        if(evaluador == true)
        {
            var datosFormulario={                                
                                    id_guia:<?php echo $id_guia ?>                                    
                                }
            $.get("courier_seguridad_entrega_evaluacion1.php",datosFormulario,resultado_evaluacion1);                       
        }     
        else
        {
            $("#contenido_modal1").html("<strong>Error:</strong> No ha seleccionado ninguna opcion sobre la Actuaci&oacute;n Aduanera,<br>seleccione Si o No");        
            $("#myModal").modal("show");                                                                                        
        }   
    }    
    function resultado_evaluacion1(datos_devueltos)
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
                if(datos_devueltos == "1")
                {
                    cheklist = cheklist + 1;
                    $('#res_evaluacion1').html('<img src="imagenes/btn_verde.gif" height="30" title="Cumple"> Asignaci&oacute;n de Veh&iacute;culos');         
                }
                else
                {
                    $('#res_evaluacion1').html('<img src="imagenes/btn_rojo.gif" height="30" title="NO Cumple"> Asignaci&oacute;n de Veh&iacute;culos');

                }                                            
                evaluacion2();
            break;
        }
    }
    //********************/
    function evaluacion2()
    {
        var datosFormulario={                                
                                id_guia:<?php echo $id_guia ?>
                            }
        $.get("courier_seguridad_entrega_evaluacion2.php",datosFormulario,resultado_evaluacion2);           
    }    
    function resultado_evaluacion2(datos_devueltos)
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
                if(datos_devueltos == "1")
                {
                    cheklist = cheklist + 1;
                    $('#res_evaluacion2').html('<img src="imagenes/btn_verde.gif" height="30" title="Cumple"> Asignaci&oacute;n de L&iacute;nea, Funcionarios y Datos de Inicio');         
                }
                else
                {
                    $('#res_evaluacion2').html('<img src="imagenes/btn_rojo.gif" height="30" title="NO Cumple"> Asignaci&oacute;n de L&iacute;nea, Funcionarios y Datos de Inicio');

                }                                            
                evaluacion4();
            break;
        }
    }
    //********************/
    function evaluacion3()
    {
        var datosFormulario={                                
                                id_guia:<?php echo $id_guia ?>
                            }
        $.get("courier_seguridad_entrega_evaluacion3.php",datosFormulario,resultado_evaluacion3);           
    }    
    function resultado_evaluacion3(datos_devueltos)
    {
        var respuesta = datos_devueltos.substring(0, 5);
        var coderror = datos_devueltos.substring(6, 7);
        //alert(respuesta);
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
                if(datos_devueltos == "1")
                {
                    cheklist = cheklist + 1;
                    $('#res_evaluacion3').html('<img src="imagenes/btn_verde.gif" height="30" title="Cumple"> Conteo de Piezas y Peso');         
                }
                else
                {
                    $('#res_evaluacion3').html('<img src="imagenes/btn_rojo.gif" height="30" title="NO Cumple"> Conteo de Piezas y Peso');

                }
                evaluacion4();
            break;
        }
    }
    //********************/
    function evaluacion4()
    {
        var datosFormulario={                                
                                id_guia:<?php echo $id_guia ?>
                            }
        $.get("courier_seguridad_entrega_evaluacion4.php",datosFormulario,resultado_evaluacion4);           
    }    
    function resultado_evaluacion4(datos_devueltos)
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
                if(datos_devueltos == "1")
                {
                    cheklist = cheklist + 1;
                    $('#res_evaluacion4').html('<img src="imagenes/btn_verde.gif" height="30" title="Cumple"> Asignaci&oacute;n de Persona Autorizada por Seguridad para revisar y recibir');         
                }
                else
                {
                    $('#res_evaluacion4').html('<img src="imagenes/btn_rojo.gif" height="30" title="NO Cumple"> Asignaci&oacute;n de Persona Autorizada por Seguridad para revisar y recibir');

                }   

                if (cheklist == 3)
                {
                    evaluacion5();                    
                }
                else
                {
                    $("#contenido_modal1").html("<strong>Error:</strong> No cumple con los requisitos para finalizar la Entrega de la Gu&iacute;a.");    
                    $("#myModal").modal("show");
                }
                
            break;
        }
    }
	//********************/
    function evaluacion5()
    {
        var ValorActuacionAduanera = document.querySelector('input[name=actuacion_aduanera]:checked').value;              
        var datosFormulario={                                
                                id_guia:<?php echo $id_guia ?>,
                                actuacion_aduanera:ValorActuacionAduanera
                            }
        $.get("courier_seguridad_entrega_evaluacion5.php",datosFormulario,resultado_evaluacion5);           
    }    
    function resultado_evaluacion5(datos_devueltos)
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
                $("#contenido_modal2").html("Usted a confirmado la entrega de la guia EXITOSAMENTE</strong>.");        
                $("#myModal2").modal("show");                                                                        
            break;
        }
    }

    function redireccion()
    {
		self.close();                
    	window.opener.location='courier_inventario.php';
    }
</script>