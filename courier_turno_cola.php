<?php 
    session_start(); /*     "This product includes PHP software, freely available from
         						<http://www.php.net/software/>". */
    require("config/configuracion.php");
    require("config/control_tiempo.php");
    $metadata='';
    //Consulta inicial
    $fecha_consulta=date("Y-m-d");    
    $sql="SELECT * FROM courier_turno WHERE date_creacion LIKE '%$fecha_consulta%' ORDER BY date_creacion DESC";    
    $consulta=mysql_query ($sql,$conexion) or die (exit('Error 1'.mysql_error()));
    $nfilas=1;
    $contC=0;
    $contA=0;
    $contF=0;
    $contP=0;
    while($fila=mysql_fetch_array($consulta))
    {
        switch ($fila['estado']) 
        {
          case 'C':
            $estado="Esperando";
            $contC++;  
          break;
          
          case 'A':
            $estado="Atendiendo";
            $contA++;
          break;

          case 'F':
            $estado="Finalizado";
            $contF++;
          break;

          case 'P':
            $estado="Perdido";        
            $contP++;
          break;
        }
      $courier=$fila['id_courier'];  
      $sql_aux="SELECT nombre FROM couriers WHERE id = '$courier'";
      $consulta_aux=mysql_query ($sql_aux,$conexion) or die (exit('Error 2'.mysql_error()));      
      $fila_aux=mysql_fetch_array($consulta_aux);
      $courier=$fila_aux['nombre'];

        $metadata .= '
                    <tr>
                      <td align="center" class="celda_tabla_principal celda_boton"><button type="button" onclick="document.location=\'courier_turno_consulta.php?id_registro='.$fila['id'].'\'"><img src="imagenes/buscar-act.png"></button></td>
                      <td align="center" class="celda_tabla_principal celda_boton">'.$fila['no_turno'].'</td>
                      <td align="center" class="celda_tabla_principal celda_boton">'.$courier.'</td>
                      <td align="center" class="celda_tabla_principal celda_boton">'.$fila['date_creacion'].'</td>
                      <td align="center" class="celda_tabla_principal celda_boton">'.$estado.'</td>                          
                    </tr>
                    ';
        $nfilas++;
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
    <script src="js/srcNewCalendar/js/jscal2.js"></script>
    <script src="js/srcNewCalendar/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/steel/steel.css" />      
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
      }      
    </style>    
</head>
<body>
<?php
  require("menu.php");
  $id_objeto=146;
  include("config/provilegios_modulo.php");
?>    
<p class="titulo_tab_principal">Cola de Turnos</p>
<p class="asterisco" align="center">---</p>    

  <table width="650px" align="center">
      <tr>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
      </tr>
      <tr>
        <td align="center" class="celda_tabla_principal celda_boton">
          <input type="text" size="10" id="f_date1" readonly="readonly" value="<?php echo $fecha_consulta ?>" /><button id="f_btn1" type="button">...</button>
          <button id="listar" type="button" onclick='listar($("#f_date1").val())'>Buscar</button>
        </td>

      </tr>
  </table>        

<div id="contenido">
  <table width="650px" align="center">
      <tr>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">...</div></td>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">No. Turno</div></td>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Courier</div></td>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Estado</div></td>
      </tr>
      <?php echo $metadata; ?>
   </table>     

  <!-- Pestañas flotantes con marcadores de conteo -->
  <div id="column-left" class="flotando" style="top: 200px;">
    <strong><h3>TURNOS</h3></strong>
  </div>
  <div id="column-left" class="flotando" style="background-image: linear-gradient(-90deg, orange, red); top: 250px;">
    <strong>Espera<hr></strong>
    <h1><?php echo $contC ?></h1>
  </div>
  <div id="column-left" class="flotando" style="background-image: linear-gradient(-90deg, orange, orange); top: 400px;">
    <strong>Atenci&oacute;n<hr></strong>
    <h1><?php echo $contA ?></h1>
  </div>
  <div id="column-left" class="flotando" style="background-image: linear-gradient(-90deg, #4682B4, green); top: 550px;">
    <strong>Finalizados<hr></strong>
    <h1><?php echo $contF ?></h1>  
  </div>
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
//<![CDATA[
  Calendar.setup({
    inputField : "f_date1",
    trigger    : "f_btn1",
    onSelect   : function() { this.hide() },
    //showTime   : 12,
    //Con Hora--> dateFormat : "%Y-%m-%d %I:%M %p"
    dateFormat : "%Y-%m-%d"
  });
//]]>   


  function listar(identificador)
  {

      $('#contenido').html('<div align="center"><img src="imagenes/cargando.gif"></div>');                
      var datosFormulario={                                
                              fecha_consulta:identificador                                    
                          }
      $.get("courier_turno_cola_listar.php",datosFormulario,resultado_listar);                       
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
                  $('#contenido').html(datos_devueltos);         
          break;
      }
  }   
</script>