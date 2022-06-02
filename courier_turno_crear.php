<?php 
    session_start(); /*     "This product includes PHP software, freely available from
         						<http://www.php.net/software/>". */
    require("config/configuracion.php");
    require("config/control_tiempo.php");
    $metadata='';
    $nfilas='0';
    $boton='';
    $id_courier='';
    $id_courier_seleccionado='';
    $courier='';
    if(isset($_GET['id_registro']))
    {
        $id_courier=isset($_GET['id_registro']) ? $_GET['id_registro'] : $_POST['id_registro'];
        //Consulta 
        $sql="SELECT g.id, g.master, g.piezas, g.peso, c.nombre FROM guia g LEFT JOIN couriers c ON g.id_consignatario = c.id WHERE g.id_tipo_guia ='5' AND g.id_tipo_bloqueo='1' AND g.id_consignatario = '$id_courier'";
        $consulta=mysql_query ($sql,$conexion) or die (exit('Error 1'.mysql_error()));
        $nfilas=1;
        while($fila=mysql_fetch_array($consulta))
        {
            $id_guia=$fila['id'];
            $sql2="SELECT id FROM courier_turno_guia WHERE id_guia = '$id_guia'";
            $consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 2'.mysql_error()));
            $buffer=mysql_num_rows($consulta2);            
            if ($buffer==0)
            {
              $courier=$fila['nombre'];
              $metadata .= '
                          <tr>
                            <td align="center" class="celda_tabla_principal celda_boton">
                              <label class="container" style="width: 10px;"><input type="checkbox" name="ck'.$nfilas.'" id="ck'.$nfilas.'" value="'.$fila['id'].'"><span class="checkmark"></span></label>
                            </td>
                            <td align="center" class="celda_tabla_principal celda_boton">'.$fila['master'].'</td>
                            <td align="center" class="celda_tabla_principal celda_boton">'.$fila['piezas'].'</td>
                            <td align="center" class="celda_tabla_principal celda_boton">'.$fila['peso'].'</td>
                          </tr>
                          ';              
            }
            $nfilas++;
        }
        $boton='
                <table width="450" align="center">
                    <tr>
                      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
                    </tr>
                    <tr>
                      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
                        <button type="button" title="Atras" onclick="document.location=\'base.php\'">
                            <img src="imagenes/al_principio-act.png" title="Atras" />
                        </button>
                        <button type="reset" name="reset" id="reset" title="Limpiar">
                            <img src="imagenes/descargar-act.png" title="Limpiar" />
                        </button>
                        <button type="button" name="guardar" id="guardar" title="Guardar">
                            <img src="imagenes/guardar-act.png" title="Guardar" />
                        </button>
                      </td>
                    </tr>
                </table>
                ';
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
    <style type="text/css">
      /* Customize the label (the container) */
      .container {
        display: block;      
        position: relative;
        padding-left: 15px;
        margin-bottom: 15px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      /* Hide the browser's default checkbox */
      .container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
      }

      /* Create a custom checkbox */
      .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #117B9B;
      }

      /* On mouse-over, add a grey background color */
      .container:hover input ~ .checkmark {
        background-color: #ccc;
      }

      /* When the checkbox is checked, add a blue background */
      .container input:checked ~ .checkmark {
        background-color: #2196F3;
      }

      /* Create the checkmark/indicator (hidden when not checked) */
      .checkmark:after {
        content: "";
        position: absolute;
        display: none;
      }

      /* Show the checkmark when checked */
      .container input:checked ~ .checkmark:after {
        display: block;
      }

      /* Style the checkmark/indicator */
      .container .checkmark:after {
        left: 9px;
        top: 5px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
      }  
    </style>
</head>
<body>
<?php
  require("menu.php");
  $id_objeto=145;
  include("config/provilegios_modulo.php");
?>    
<p class="titulo_tab_principal">Creaci&oacute;n de Turnos</p>
<p class="asterisco" align="center">---</p>    
<form method="post" name="formdata" id="formdata">
    <table align="center">
      <!-- Registro de courier -->
	      <tr>
	        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Courier</div></td>
	        <td class="celda_tabla_principal celda_boton">
	            <select name="couriers" id="couriers" tabindex="1" onchange="recargar(this.value)">
	                <option value="">Seleccione uno</option>
	                <script type="text/javascript">         
	                    document.getElementById("couriers").focus();
	                </script>        
	                <?php
	                	$metadata2="";
    	                $buffer="";
                      	$sql="SELECT id,master,id_consignatario FROM guia WHERE id_tipo_guia ='5' AND id_tipo_bloqueo='1' ORDER BY id_consignatario ASC";
	                    $consulta=mysql_query ($sql,$conexion) or die ("Error 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");                    
	                    while($fila=mysql_fetch_array($consulta))
	                    {                                                	                        
                          $id_guia=$fila['id'];
                          $id_consignatario=$fila['id_consignatario'];
                          $sql2="SELECT id FROM courier_turno_guia WHERE id_guia='$id_guia'";
                          $consulta2=mysql_query ($sql2,$conexion) or die ("Error 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");                    
                          $nregistros=mysql_num_rows($consulta2);
	                        if ($nregistros == 0)
	                        {
	                            $sql3="SELECT id,nombre FROM couriers WHERE id = '$id_consignatario'";
	                            $consulta3=mysql_query ($sql3,$conexion) or die (exit('Error 3'.mysql_error()));
	                            $fila3=mysql_fetch_array($consulta3);
	                            if($buffer != $fila3['id'])
	                            {
	                              echo '<option value="'.$fila3['id'].'">'.$fila3['nombre'].'</option>';                              
	                            }
	                            $buffer = $fila3['id'];
	                        }	                                          
	                    }                      
	                ?>
	            </select>    
	            <?php echo $metadata2 ?>
	        </td>
	      </tr>  
    </table>
    <div id="res_listar">
        <table width="650px" align="center">
            <tr>
              <td align="center" valign="middle" class="celda_tabla_principal" colspan="4"><div class="letreros_tabla">Datos del Turno</div></td>
            </tr>
            <tr>
              <td align="center" valign="middle" class="celda_tabla_principal" colspan="2"><div class="letreros_tabla">Courier</div></td>
              <td align="center" valign="middle" class="celda_tabla_principal" colspan="2"><p class="asterisco" align="left"><?php echo $courier ?> </p></td>
            </tr>
            <tr>
  		        <td align="left" class="celda_tabla_principal" colspan="2"><div class="letreros_tabla">L&iacute;nea</div></td>
  		        <td class="celda_tabla_principal celda_boton" colspan="2">
  		          <select name="id_linea" id="id_linea" tabindex="1">
  		              <option value="" >Seleccione una</option>     
  		              <?php
  		                    $sql="SELECT id,nombre FROM courier_linea WHERE estado='A'";
  		                    $consulta=mysql_query ($sql,$conexion) or die ("ERROR 01: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
  		                    while($fila=mysql_fetch_array($consulta))
  		                    {
  		                        if($courier_id_linea == $fila['id'])
  		                            $seleccion='selected="selected"';
  		                        else
  		                          $seleccion='';
  		                        echo '<option value="'.$fila['id'].'" '.$seleccion.'>'.$fila['nombre'].'</option>';
  		                    }
  		              ?>
  		          </select>         	
  		        </td>
            </tr>
            <tr>
              <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">...</div></td>
              <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Master</div></td>
              <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
              <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
            </tr>
            <?php echo $metadata; ?>
         </table>     
    </div>
    <input type="hidden" name="nfilas" id="nfilas" value="<?php echo --$nfilas; ?>">    
    <input type="hidden" name="id_courier" id="id_courier" value="<?php echo $id_courier ?>">    
    <?php echo $boton; ?>    
</form>
<div id="msgRespuesta" style="display:none"></div>

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
          <button type="button" class="btn btn-success" data-dismiss="modal" onclick="document.location='courier_turno_cola.php'">Aceptar</button>
          <!-- <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="salida()">No</button> -->
        </div>
        
      </div>
    </div>
  </div>

</html>

<script language="javascript">        
    function listar(identificador)
    {                
        var datosFormulario={                                
                                id_courier:identificador                                    
                            }
        $.get("courier_turno_crear_listado.php",datosFormulario,resultado_listar);                       
    }    
    function resultado_listar(datos_devueltos)
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
                    $('#res_listar').html(datos_devueltos);         
            break;
        }
    }  

    function validaForm()
    {
      
        // Campos de texto
        if($("#id_linea").val() == ""){
            /*lert("Debe seleccionar una Linea.");*/
			$('#titulo_modal1').html("Error");
            $('#contenido_modal1').html("Debe seleccionar una L&iacute;nea.");
            $('#myModal1').modal("show");
            $("#id_linea").focus();       // Esta función coloca el foco de escritura del usuario en el campo Nombre directamente.
            return false;
        }
        /*
	        if($("#apellidos").val() == ""){
	            alert("El campo Apellidos no puede estar vacío.");
	            $("#apellidos").focus();
	            return false;
	        }
	        if($("#direccion").val() == ""){
	            alert("El campo Dirección no puede estar vacío.");
	            $("#direccion").focus();
	            return false;
	        }

	        // Checkbox
	        if(!$("#mayor").is(":checked")){
	            alert("Debe confirmar que es mayor de 18 años.");
	            return false;
	        }
     	 */
      return true; // Si todo está correcto
    }

    function recargar(id_registro)
    {
        document.location='courier_turno_crear.php?id_registro='+id_registro;
    }

    //https://www.codigonexo.com/blog/programacion/javascript/formulario-con-ajax-y-jquery/
    $(document).ready(function() 
    {   
        $("#guardar").click( function() 
        {     
            if(validaForm())
            {                               
                $.post("courier_turno_crear_salvar.php",$("#formdata").serialize(),function(datos_devueltos)
                {
                    /*
                        $("#formulario").fadeOut("slow");   // Hacemos desaparecer el div "formulario" con un efecto fadeOut lento.                
                        if(res == 1)
                        {
                            $("#exito").delay(500).fadeIn("slow");      // Si hemos tenido éxito, hacemos aparecer el div "exito" con un efecto fadeIn lento tras un delay de 0,5 segundos.
                        } else 
                        {
                            $("#fracaso").delay(500).fadeIn("slow");    // Si no, lo mismo, pero haremos aparecer el div "fracaso"
                        }
                    */
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
                                $('#titulo_modal1').html("Error");
                                $('#contenido_modal1').html(datos_devueltos);
                                $('#myModal1').modal("show");
                            }           
                        break;

                        default:
                            //$('#msgRespuesta').delay(500).fadeIn("slow").html(datos_devueltos);     
                            $('#titulo_modal2').html("Registro Procesado");
                            $('#contenido_modal2').html(datos_devueltos);
                            $('#myModal2').modal("show");                                
                            /*
                                if(datos_devueltos == "1")
                                {                                
                                    $('#msgRespuesta').delay(500).fadeIn("slow").html('<img src="imagenes/btn_verde.gif" height="30" title="Cumple"> Asignaci&oacute;n de L&iacute;nea');         
                                }
                                else
                                {
                                    $('#msgRespuesta').delay(500).fadeIn("slow").html('<img src="imagenes/btn_rojo.gif" height="30" title="NO Cumple"> Asignaci&oacute;n de L&iacute;nea');
                                } 
                            */                                                                       
                        break;
                    }
                });
            }
        });    
    });
</script>