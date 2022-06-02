<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <style> 
      /* Style the tab */
      .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;        
      }

      /* Style the buttons inside the tab */
      .tab button {
        /*background-color: inherit;*/
        background-color:#C6DFE6;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;        
      }

      /* Change background color of buttons on hover */
      .tab button:hover {
        background-color: #ddd;
        font-weight: bold;
        
      }

      /* Create an active/current tablink class */
      .tab button.active {
        background-color: #ccc;
      }

      /* Style the tab content */
      .tabcontent {
        background-color: #FFFFFF;
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
      }
    </style>
    <script src="js/srcNewCalendar/js/jscal2.js"></script>
    <script src="js/srcNewCalendar/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/steel/steel.css" />	
    <!--
    <link rel="stylesheet" href="js/bootstrap.min.css" >
    <script src="js/jquery-3.3.1.min.js" ></script>
    <script src="js/popper.min.js" ></script>   
    <script src="js/bootstrap.min.js"></script>
    -->
</head>
<body>
<?php
  require("menu.php");
  $id_objeto=134;
  include("config/provilegios_modulo.php");
  $id_guia=$_REQUEST['id_guia']; 

  //Carga datos de la Guia
  $sql="SELECT master, courier_dato_llegada, courier_dato_inicio, courier_id_linea, id_consignatario FROM guia WHERE id='$id_guia'";
  $consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
  $fila=mysql_fetch_array($consulta);
  $master=$fila["master"];  
  $id_consignatario=$fila["id_consignatario"];
  $courier_id_linea=$fila["courier_id_linea"];
  if($courier_id_linea == 0 || $courier_id_linea == null)
  {
  	$estadoLinea='style="display: none;"';
  }
  else
  {
  	$estadoLinea="";
  }

  //Formato fecha hora
  $courier_dato_llegada=$fila["courier_dato_llegada"];
  $fecha_llegada=substr($courier_dato_llegada,0,10);
  $hora_llegada=substr($courier_dato_llegada,11,2);
  $minutos_llegada=substr($courier_dato_llegada,14,2);
  $datoBdLlegada= str_replace("-", "", $fecha_llegada).$hora_llegada.$minutos_llegada;

  $courier_dato_inicio=$fila["courier_dato_inicio"];
  if($courier_dato_inicio =="0000-00-00 00:00:00")
  {
    $fecha_inicio=""; 
    $hora_inicio="";
    $minutos_inicio="";
  }
  else
  {
    $fecha_inicio=substr($courier_dato_inicio,0,10);
    $hora_inicio=substr($courier_dato_inicio,11,2);
    $minutos_inicio=substr($courier_dato_inicio,14,2);  
  }

  $nombresDian="";
  $nombresTaescol="";
  $nombresPolfa="";
  $nombresInvima="";
  $nombresIca="";
  $nombresOtros="";
  $nombresCourier="";

  //identificando funcionario que intervienen con la guia
  $sql2="SELECT id,id_funcionario,tipo FROM courier_funcionarios_guia WHERE id_guia ='$id_guia'";
  $consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
  while($fila2=mysql_fetch_array($consulta2))
  {
    //Identificando la Entidad del funcionario
    $id_registro=$fila2['id'];
    $id_funcionario=$fila2['id_funcionario'];
    $tipo=$fila2['tipo'];    
    $sql3="SELECT f.nombre, f.otros, e.nombre AS nombre_entidad FROM courier_funcionario f LEFT JOIN courier_entidades e ON f.id_entidad = e.id WHERE f.id ='$id_funcionario'";
    $consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");  
    $fila3=mysql_fetch_array($consulta3);
    $nombre_funcionario=$fila3['nombre'];
    $nombre_entidad=$fila3['nombre_entidad'];    

    switch ($nombre_entidad) 
    {
      case 'DIAN':
        $nombresDian .= '<div>
                            <button type="button" title="Quitar"  onclick="aquitarUsuario('.$id_guia.','.$id_registro.',1)">
                              <img src="imagenes/cancelar-act.png" valign="middle" width="20">
                            </button>
                            <img src="imagenes/sesion.png" valign="middle" width="15"> '.$nombre_funcionario.'
                        </div>';
      break;
      case 'TAESCOL':
        $nombresTaescol .= '<div>
                                <button type="button" title="Quitar" onclick="aquitarUsuario('.$id_guia.','.$id_registro.',2)">
                                  <img src="imagenes/cancelar-act.png" valign="middle" width="20">
                                </button>
                                <img src="imagenes/sesion.png" valign="middle" width="15"> '.$nombre_funcionario.'
                            </div>';
      break;
      case 'POLFA':
        $nombresPolfa .= '<div>
                                <button type="button" title="Quitar" onclick="aquitarUsuario('.$id_guia.','.$id_registro.',3)">
                                  <img src="imagenes/cancelar-act.png" valign="middle" width="20">
                                </button>
                                <img src="imagenes/sesion.png" valign="middle" width="15"> '.$nombre_funcionario.'
                            </div>';
      break;
      case 'INVIMA':
        $nombresInvima .= '<div>
                                <button type="button" title="Quitar" onclick="aquitarUsuario('.$id_guia.','.$id_registro.',4)">
                                  <img src="imagenes/cancelar-act.png" valign="middle" width="20">
                                </button>
                                <img src="imagenes/sesion.png" valign="middle" width="15"> '.$nombre_funcionario.'
                            </div>';
      break;
      case 'ICA':
        $nombresIca .='<div>
                                <button type="button" title="Quitar" onclick="aquitarUsuario('.$id_guia.','.$id_registro.',5)">
                                  <img src="imagenes/cancelar-act.png" valign="middle" width="20">
                                </button>
                                <img src="imagenes/sesion.png" valign="middle" width="15"> '.$nombre_funcionario.'
                            </div>';
      break;
      case 'OTROS':
        $nombresOtros .= '<div>
                                <button type="button" title="Quitar" onclick="aquitarUsuario('.$id_guia.','.$id_registro.',6)">
                                  <img src="imagenes/cancelar-act.png" valign="middle" width="20">
                                </button>
                                <img src="imagenes/sesion.png" valign="middle" width="15"> '.$nombre_funcionario.' - '.$fila3['otros'].'
                            </div>';
      break;      

      case 'COURIER':
	      	if ($tipo == "C")
		  	{
		        $nombresCourier .= '<div>
		                                <img src="imagenes/sesion.png" valign="middle" width="15">(Cargue) '.$nombre_funcionario.'
		                            </div>';
	  		}
	  		else
	  		{
		        $nombresCourier .= '<div>
		                                <button type="button" title="Quitar" onclick="aquitarUsuario('.$id_guia.','.$id_registro.',7)">
		                                  <img src="imagenes/cancelar-act.png" valign="middle" width="20">
		                                </button>
		                                <img src="imagenes/sesion.png" valign="middle" width="15"> '.$nombre_funcionario.'
		                            </div>';	  			
	  		}
      break;      
    }
  }  
?>
<p class="titulo_tab_principal">Asignacion de L&iacute;neas y Fechas</p>
<p class="asterisco" align="center">Gu&iacute;a: <?php echo $master?></p>
<form name="guardar_datos" id="guardar_datos" method="post">
  <table align="center">
      <!-- Funcionarios -->      
      <tr>
        <td align="left" class="celda_tabla_principal" colspan="2">
          <div class="letreros_tabla asterisco"><h3>Funcionarios</h3></div>
          <p>Seleccione una entidad e ingrese los funcionarios que intervienen <br>en la revisión.</p>       
          <div class="tab">
            <button type="button" class="tablinks" onclick="openTab(event, 'tabDian')">Dian</button>
            <button type="button" class="tablinks" onclick="openTab(event, 'tabTaescol')">Taescol</button>&nbsp;
            <button type="button" class="tablinks" onclick="openTab(event, 'tabPolfa')">Polfa</button>&nbsp;
            <button type="button" class="tablinks" onclick="openTab(event, 'tabInvima')">Invima</button>&nbsp;
            <button type="button" class="tablinks" onclick="openTab(event, 'tabIca')">Ica</button>&nbsp;
            <button type="button" class="tablinks" onclick="openTab(event, 'tabOtros')">Otros</button>&nbsp;
            <button type="button" class="tablinks" onclick="openTab(event, 'tabCourier')">Courier</button>&nbsp;
          </div>
          <!-- DIAN -->
          <div id="tabDian" class="tabcontent">
              <h3>Dian</h3>
              Seleccione uno
              <select name="id_funcionarioDian" id="id_funcionarioDian">                    
                  <?php
                      $sql="SELECT id,nombre FROM courier_funcionario WHERE id_entidad='1' AND estado='A' ORDER BY nombre ASC";
                      $consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                      while($fila=mysql_fetch_array($consulta))
                      {
                        echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                      }
                  ?>
              </select>
            <button type="button" title="Guardar"  onclick="adicionarUsuario(<?php echo $id_guia; ?>,$(id_funcionarioDian).value,1)">
              <img src="imagenes/guardar-act.png" valign="middle">
            </button>
            <button type="button" title="Agregar uno nuevo" onclick="openPopup('courier_funcionario_registro.php?entidad=1','new','700','450','scrollbars=1',true)" <?php  $id_objeto=135; include("config/provilegios_objeto.php");  echo $activacion ?>>
              <img src="imagenes/agregar-act.png" valign="middle">
            </button>
            <div id="usrsDian">
              <p><?php echo $nombresDian ?></p>
            </div>
          </div>
          <!-- TAESCOL -->
          <div id="tabTaescol" class="tabcontent">
              <h3>Taescol</h3>
              Seleccione uno
              <select name="id_funcionarioTaescol" id="id_funcionarioTaescol">                    
                  <?php
                      $sql="SELECT id,nombre FROM courier_funcionario WHERE id_entidad='2' AND estado='A' ORDER BY nombre ASC";
                      $consulta=mysql_query ($sql,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                      while($fila=mysql_fetch_array($consulta))
                      {
                        echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                      }
                  ?>
              </select>
            <button type="button" title="Guardar"  onclick="adicionarUsuario(<?php echo $id_guia; ?>,$(id_funcionarioTaescol).value,2)">
              <img src="imagenes/guardar-act.png" valign="middle">
            </button>
            <button type="button" title="Agregar uno nuevo" onclick="openPopup('courier_funcionario_registro.php?entidad=2','new','700','450','scrollbars=1',true)" <?php  $id_objeto=135; include("config/provilegios_objeto.php");  echo $activacion ?>>
              <img src="imagenes/agregar-act.png" valign="middle">
            </button>
            <div id="usrsTaescol">
              <p><?php echo $nombresTaescol ?></p>
            </div>
          </div>
          <!-- POLFA -->
          <div id="tabPolfa" class="tabcontent">
              <h3>Polfa</h3>
              Seleccione uno
              <select name="id_funcionarioPolfa" id="id_funcionarioPolfa">                    
                  <?php
                      $sql="SELECT id,nombre FROM courier_funcionario WHERE id_entidad='3' AND estado='A' ORDER BY nombre ASC";
                      $consulta=mysql_query ($sql,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                      while($fila=mysql_fetch_array($consulta))
                      {
                        echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                      }
                  ?>
              </select>
            <button type="button" title="Guardar"  onclick="adicionarUsuario(<?php echo $id_guia; ?>,$(id_funcionarioPolfa).value,3)">
              <img src="imagenes/guardar-act.png" valign="middle">
            </button>
            <button type="button" title="Agregar uno nuevo" onclick="openPopup('courier_funcionario_registro.php?entidad=3','new','700','450','scrollbars=1',true)" <?php  $id_objeto=135; include("config/provilegios_objeto.php");  echo $activacion ?>>
              <img src="imagenes/agregar-act.png" valign="middle">
            </button>
            <div id="usrsPolfa">
              <p><?php echo $nombresPolfa ?></p>
            </div>
          </div>
          <!-- INVIMA -->
          <div id="tabInvima" class="tabcontent">
              <h3>Invima</h3>
              Seleccione uno
              <select name="id_funcionarioInvima" id="id_funcionarioInvima">                    
                  <?php
                      $sql="SELECT id,nombre FROM courier_funcionario WHERE id_entidad='4' AND estado='A' ORDER BY nombre ASC";
                      $consulta=mysql_query ($sql,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                      while($fila=mysql_fetch_array($consulta))
                      {
                        echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                      }
                  ?>
              </select>
            <button type="button" title="Guardar"  onclick="adicionarUsuario(<?php echo $id_guia; ?>,$(id_funcionarioInvima).value,4)">
              <img src="imagenes/guardar-act.png" valign="middle">
            </button>
            <button type="button" title="Agregar uno nuevo" onclick="openPopup('courier_funcionario_registro.php?entidad=4','new','700','450','scrollbars=1',true)" <?php  $id_objeto=135; include("config/provilegios_objeto.php");  echo $activacion ?>>
              <img src="imagenes/agregar-act.png" valign="middle">
            </button>
            <div id="usrsInvima">
              <p><?php echo $nombresInvima ?></p>
            </div>
          </div>
          <!-- ICA -->
          <div id="tabIca" class="tabcontent">
              <h3>Ica</h3>
              Seleccione uno
              <select name="id_funcionarioIca" id="id_funcionarioIca">                    
                  <?php
                      $sql="SELECT id,nombre FROM courier_funcionario WHERE id_entidad='5' AND estado='A' ORDER BY nombre ASC";
                      $consulta=mysql_query ($sql,$conexion) or die ("ERROR 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                      while($fila=mysql_fetch_array($consulta))
                      {
                        echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                      }
                  ?>
              </select>
            <button type="button" title="Guardar"  onclick="adicionarUsuario(<?php echo $id_guia; ?>,$(id_funcionarioIca).value,5)">
              <img src="imagenes/guardar-act.png" valign="middle">
            </button>
            <button type="button" title="Agregar uno nuevo" onclick="openPopup('courier_funcionario_registro.php?entidad=5','new','700','450','scrollbars=1',true)" <?php  $id_objeto=135; include("config/provilegios_objeto.php");  echo $activacion ?>>
              <img src="imagenes/agregar-act.png" valign="middle">
            </button>
            <div id="usrsIca">
              <p><?php echo $nombresIca ?></p>
            </div>
          </div>
          <!-- OTROS -->  
          <div id="tabOtros" class="tabcontent">
              <h3>Otros</h3>
              Seleccione uno
              <select name="id_funcionarioOtros" id="id_funcionarioOtros">                    
                  <?php
                      $sql="SELECT id,nombre FROM courier_funcionario WHERE id_entidad='6' AND id_guia='$id_guia' AND estado='A' ORDER BY nombre ASC";
                      $consulta=mysql_query ($sql,$conexion) or die ("ERROR 6: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                      while($fila=mysql_fetch_array($consulta))
                      {
                        echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                      }
                  ?>
              </select>
            <button type="button" title="Guardar"  onclick="adicionarUsuario(<?php echo $id_guia; ?>,$(id_funcionarioOtros).value,6)">
              <img src="imagenes/guardar-act.png" valign="middle">
            </button>
            <button type="button" title="Agregar uno nuevo" onclick="openPopup('courier_funcionario_registro.php?entidad=6&id_guia=<?php echo $id_guia ?>','new','700','450','scrollbars=1',true)" <?php  $id_objeto=135; include("config/provilegios_objeto.php");  echo $activacion ?>>
              <img src="imagenes/agregar-act.png" valign="middle">
            </button>
            <div id="usrsOtros">
              <p><?php echo $nombresOtros ?></p>
            </div>
          </div>
          <!-- COURIER -->
          <div id="tabCourier" class="tabcontent">
              <h3>Courier</h3>
              Seleccione uno
              <select name="id_funcionarioCourier" id="id_funcionarioCourier">                    
                  <?php
                      $sql="SELECT id,nombre FROM courier_funcionario WHERE id_entidad='7' AND id_consignatario='$id_consignatario' AND estado='A' ORDER BY nombre ASC";
                      $consulta=mysql_query ($sql,$conexion) or die ("ERROR 7: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                      while($fila=mysql_fetch_array($consulta))
                      {
                        echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                      }
                  ?>
              </select>
            <button type="button" title="Guardar"  onclick="adicionarUsuario(<?php echo $id_guia; ?>,$(id_funcionarioCourier).value,7)">
              <img src="imagenes/guardar-act.png" valign="middle">
            </button>
            <button type="button" title="Agregar uno nuevo" onclick="openPopup('courier_funcionario_registro.php?entidad=7&id_guia=<?php echo $id_guia ?>','new','700','450','scrollbars=1',true)" <?php  $id_objeto=135; include("config/provilegios_objeto.php");  echo $activacion ?>>
              <img src="imagenes/agregar-act.png" valign="middle">
            </button>
            <div id="usrsCourier">
              <p><?php echo $nombresCourier ?></p>
            </div>
          </div>
        </td>
      </tr>
      <!-- Linea -->    
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">L&iacute;nea</div></td>
        <td class="celda_tabla_principal celda_boton">
        	<div <?php echo $estadoLinea; ?>>
	          <select name="id_linea" id="id_linea" tabindex="1">	
	          	  <option value="">Seleccione una</option>
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
      		</div>
        </td>
      </tr>
      <!-- Calendario -->    
      <tr>
        <td align="left" class="celda_tabla_principal">
          <div class="letreros_tabla">
            Datos de Inicio Revisi&oacute;n<br>
            <img src="imagenes/scanner_1.png" height="50" width="50">          
          </div>
        </td>
        <td class="celda_tabla_principal celda_boton">            
          <table class="celda_tabla_principal">
            <tr>
              <td colspan="2">
                Fecha<input style="text-align: center" readonly="true" name="date" id="date" size="14" value="<?php echo $fecha_inicio ?>" /> <button id="f_btn1" tabindex="7">...</button>
              </td>
            </tr>                 
            <tr>
              <td>Horas</td>
              <td>Minutos</td>
            </tr>
            <tr>
              <td>
                  <select name="hour" id="hour">
                    <option value="<?php echo $hora_inicio ?>"><?php echo $hora_inicio ?></option>
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                  </select>

              </td>
              <td>
                  <select name="minute" id="minute">
                    <option value="<?php echo $minutos_inicio ?>"><?php echo $minutos_inicio ?></option>
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                    <option value="32">32</option>
                    <option value="33">33</option>
                    <option value="34">34</option>
                    <option value="35">35</option>
                    <option value="36">36</option>
                    <option value="37">37</option>
                    <option value="38">38</option>
                    <option value="39">39</option>
                    <option value="40">40</option>
                    <option value="41">41</option>
                    <option value="42">42</option>
                    <option value="43">43</option>
                    <option value="44">44</option>
                    <option value="45">45</option>
                    <option value="46">46</option>
                    <option value="47">47</option>
                    <option value="48">48</option>
                    <option value="49">49</option>
                    <option value="50">50</option>
                    <option value="51">51</option>
                    <option value="52">52</option>
                    <option value="53">53</option>
                    <option value="54">54</option>
                    <option value="55">55</option>
                    <option value="56">56</option>
                    <option value="57">57</option>
                    <option value="58">58</option>
                    <option value="59">59</option>                  
                  </select>
              </td>
            </tr>
          </table>               
        </td>
      </tr>
    </table>	  
    <div id="respuesta"></div>
    <input type="hidden" name="id_guia" id="id_guia" value="<?php echo $id_guia ?>"/>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" tabindex="13" title="Atras" onclick="document.location='courier_inventario.php'">
                <img src="imagenes/al_principio-act.png" title="Atras" />
            </button>
            <button type="reset" name="reset" id="reset" tabindex="12" title="Limpiar">
                <img src="imagenes/descargar-act.png" title="Limpiar" />
            </button>
            <button type="button" name="guardar" id="guardar" tabindex="11" title="Guardar" onClick="return validar();">
                <img src="imagenes/guardar-act.png" title="Guardar" />
            </button>
          </td>
        </tr>
    </table>
</form>
</body>
</html>
<script language="javascript">
// funcion para validar
function validar()
{
		
	if (document.forms[0].date.value=="")
	{
		alert("Atencion: Debe ingresar una FECHA de INICIO DE REVISION");
		document.getElementById("f_btn1").focus();
		return(false);
	}
  if (document.forms[0].hour.value=="")
  {
    alert("Atencion: Debe ingresar una HORA de INICIO DE REVISION");
    document.getElementById("hour").focus();
    return(false);
  }

  if (document.forms[0].minute.value=="")
  {
    alert("Atencion: Debe ingresar unos MINUTOS de INICIO DE REVISION");
    document.getElementById("minute").focus();
    return(false);
  }

  var valorInicio=document.forms[0].date.value + document.forms[0].hour.value + document.forms[0].minute.value;
  var datoInicio =parseInt(valorInicio.replace(/-/gi, ""));
  var datoBdLlegada = parseInt(<?php echo $datoBdLlegada ?>);
  if(datoBdLlegada > datoInicio)
  {
    alert("Atencion: No puede ingresar unos DATOS DE INICIO anteriores a los DATOS DE LLEGADA (<?php echo $courier_dato_llegada ?>)");
    return(false);
  }

  //Procedimiento de Guardado
	guardar_form();		
}

function guardar_form()
{
	var peticion = new Request(
	{
		url: 'courier_registro_linea_salvar.php',
		method: 'post',
		onRequest: function()
		{			
			mostrar_div($('respuesta'));
			$('respuesta').innerHTML='<p align="center">Procesando...<image src="imagenes/cargando.gif"></p>';
			$('guardar').disabled=true;
			$('reset').disabled=true;			
		},			

    onSuccess: function(responseText)
    {      
      var respuesta=responseText;
      var arreglos = respuesta.split("|¡¡|");
      var accion = eval(arreglos[0]);
      var coderror = eval(arreglos[1]);      
      var mensaje = arreglos[3];
      switch(accion)
      {
        case 0: //Error por Exit() en consulta BD
          $('respuesta').innerHTML='<p align="center">Error Codigo '+ coderror + mensaje+'</p>';
          $('guardar').disabled=false;
          $('reset').disabled=false;          
        break;

        case 1: //Registro Exitoso   
          var id_registro = eval(arreglos[2]);       
          $('respuesta').innerHTML='<p align="center">Proceso Finalizado</p>';
          alert('Proceso Finalizado con exito');
          document.location='courier_inventario.php';
          $('guardar').disabled=false;
          $('reset').disabled=false;
        break;

        case 2: //Error por agregar usuario que revisan el proceso de esta guia
          $('respuesta').innerHTML='<p align="center">Error Codigo '+ coderror + mensaje+'</p>';
          alert(mensaje);
          $('guardar').disabled=false;
          $('reset').disabled=false;          
        break;
      }
    },
		onFailure: function()
		{
			$('respuesta').innerHTML='<p align="center">Error al guardar, Intente de nuevo...</p>';
			$('guardar').disabled=false;
			$('reset').disabled=false;
		}
	}
	);
	peticion.send($('guardar_datos'));
}

//Adicion Asincrona de Datos
function adicionarUsuario(id_guia,id_funcionario,id_entidad){  
  //Almacena el usuario que revisara la guia
  var localizacionRespuesta="";
  switch(id_entidad)
  {
    case 1:
      localizacionRespuesta="usrsDian";
    break;

    case 2:
      localizacionRespuesta="usrsTaescol";
    break;

    case 3:
      localizacionRespuesta="usrsPolfa";
    break;

    case 4:
      localizacionRespuesta="usrsInvima";
    break;

    case 5:
      localizacionRespuesta="usrsIca";
    break;

    case 6:
      localizacionRespuesta="usrsOtros";
    break;

    case 7:
      localizacionRespuesta="usrsCourier";
    break;

  }
	var myRequest = new Request({
		url: 'courier_registro_linea_addUsuario_ajax.php',
		method: 'get',
		onRequest: function(){
			$(localizacionRespuesta).innerHTML = "Procesando <img src='imagenes/cargando.gif'/>";
		},
		onSuccess: function(responseText){
			$(localizacionRespuesta).innerHTML= responseText;
		},
		onFailure: function(){
			$(localizacionRespuesta).innerHTML = "Error";
		}
	});	
	myRequest.send('id_guia=' + id_guia+'&id_funcionario='+id_funcionario+'&id_entidad='+id_entidad);
}

//Eliminacion Asincrona de Datos
function aquitarUsuario(id_guia,id_registro,id_entidad){  
  //Elimina el usuario que revisara la guia
  var localizacionRespuesta="";
  switch(id_entidad)
  {
    case 1:
      localizacionRespuesta="usrsDian";
    break;

    case 2:
      localizacionRespuesta="usrsTaescol";
    break;

    case 3:
      localizacionRespuesta="usrsPolfa";
    break;

    case 4:
      localizacionRespuesta="usrsInvima";
    break;

    case 5:
      localizacionRespuesta="usrsIca";
    break;

    case 6:
      localizacionRespuesta="usrsOtros";
    break;

    case 7:
      localizacionRespuesta="usrsCourier";
    break;

  }
  var myRequest = new Request({
    url: 'courier_registro_linea_delUsuario_ajax.php',
    method: 'get',
    onRequest: function(){
      $(localizacionRespuesta).innerHTML = "Procesando <img src='imagenes/cargando.gif'/>";
    },
    onSuccess: function(responseText){
      $(localizacionRespuesta).innerHTML = responseText;
    },
    onFailure: function(){
      $(localizacionRespuesta).innerHTML = "Error";
    }
  }); 
  myRequest.send('id_guia=' + id_guia+'&id_registro='+id_registro+'&id_entidad='+id_entidad);
}

function cerrarmodal(vmodal)
{
  $(vmodal).modal('hide');
}

function mostrar_div(id_div)
{
	id_div.set('morph',{ 
	duration: 200, 
	transition: 'linear'
	});
	id_div.morph({
		'opacity': 1 
	});
}

//Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
}

//Calendario
Calendar.setup({
  inputField : "date",
  trigger    : "f_btn1",
  onSelect   : function() { this.hide() },
  //showTime   : 12,
  //Con Hora--> dateFormat : "%Y-%m-%d %I:%M %p"
  dateFormat : "%Y-%m-%d"
});

//Tabs
function openTab(evt, cityName) 
{
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) 
  {
  tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) 
  {
  tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

function openPopup(url,name,w,h,props,center){
  l=18;t=18
  if(center){l=(screen.availWidth-w)/2;t=(screen.availHeight-h)/2}
  url=url.replace(/[ ]/g,'%20')
  popup=window.open(url,name,'left='+l+',top='+t+',width='+w+',height='+h+',scrollbars=1'+((props)?','+props:''))
  props=props||''
  if(props.indexOf('fullscreen')!=-1){popup.moveTo(0,0);popup.resizeTo(screen.width,screen.height)}
  popup.focus()
}
</script>
