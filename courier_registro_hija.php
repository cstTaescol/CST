<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>    
    <script src="js/srcNewCalendar/js/jscal2.js"></script>
    <script src="js/srcNewCalendar/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/steel/steel.css" />	
</head>
<body>
<?php
  require("menu.php");
  $id_objeto=129;
  include("config/provilegios_modulo.php");
  $id_guia=$_REQUEST['id_guia']; 

  //Carga datos de la Guia
  $sql="SELECT master, courier_dato_llegada, courier_dato_inicio, courier_id_linea, piezas, peso FROM guia WHERE id='$id_guia'";
  $consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
  $fila=mysql_fetch_array($consulta);
  $master=$fila["master"];
  $piezas=$fila["piezas"];
  $peso=$fila["peso"];   

?>
<p class="titulo_tab_principal">Actuaci&oacute;n Aduanera</p>
<p class="asterisco" align="center">Gu&iacute;a Master: <?php echo $master?></p>
<form name="guardar_datos" id="guardar_datos" method="post">
  <table align="center">
      <!-- Entidad -->
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Entidad</div></td>
        <td class="celda_tabla_principal celda_boton">
              <select name="entidad" id="entidad" tabindex="1" onchange="listar(this.value)">                    
                  <?php
                      $sql="SELECT id,nombre FROM courier_entidades WHERE estado='A' ORDER BY nombre ASC";
                      $consulta=mysql_query ($sql,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                      while($fila=mysql_fetch_array($consulta))
                      {
                        if($fila['id']==1){
                        	echo '<option value="'.$fila['id'].'" selected>'.$fila['nombre'].'</option>';
                        }
                        else{
                        	echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                        }
                      }
                  ?>
              </select>          
        </td>        
      </tr>      
      <!-- Funcionarios -->      
      <tr>        
        <td align="left" class="celda_tabla_principal" colspan="2">          
          <table align="center">
              <tr>
                  <td align="center" valign="middle" colspan="2">
                      <div id="procesando" class="opaco_ie" style="position:relative; background-image:url(imagenes/background.png);width:100%; height:30px"></div>
                  </td>
              </tr>
          </table>
          <p>Seleccione los funcionarios que autorizan la Actuaci&oacute;n Aduanera.</p>
          <table align="center">
            <tr align="center">
              <td class="celda_tabla_principal">Funcionarios</td>
              <td></td>
              <td class="celda_tabla_principal">Funcionarios Seleccionados</td>
            </tr>
            <tr>
              <td class="celda_tabla_principal celda_boton">
                <select id="origen" name="origen" multiple="multiple" cols="150" tabindex="2">                
                    <?php
                        $sql="SELECT id,nombre FROM courier_funcionario WHERE id_entidad='1' AND estado='A' ORDER BY nombre ASC";
                        $consulta=mysql_query ($sql,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                        while($fila=mysql_fetch_array($consulta))
                        {
                          echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                        }
                    ?>
                </select>                              
              </td>
              <td>
                  <button type="button" id="enviar" onclick="seleccionar(document.guardar_datos.origen.selectedIndex,this.id)" tabindex="2">
                  		<img src="imagenes/al_final-act.png">
                  	</button><br>
                  <button type="button" id="recibir" onclick="seleccionar(document.guardar_datos.destino.selectedIndex,this.id)" tabindex="3">
                  	<img src="imagenes/al_principio-act.png">
                  </button><br>
              </td>
              <td class="celda_tabla_principal celda_boton">
                  <select id="destino" name="destino[]"  multiple="multiple" cols="150" tabindex="4">
                  </select>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <!-- Numero de guia -->      
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">No Gu&iacute;a Hija</div></td>
        <td class="celda_tabla_principal celda_boton">
            <input type="text" maxlength="30" size="30" tabindex="5" name="no_guia" id="no_guia" />
        </td>    
      </tr>
      <!-- Piezas -->    
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
        <td class="celda_tabla_principal celda_boton">            
            <input type="text" maxlength="5" size="10" tabindex="6" onKeyPress="return numeric(event)" name="piezas" id="piezas"/> 
        </td>
      </tr>
      <!-- Peso -->
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
        <td class="celda_tabla_principal celda_boton">            
            <input type="text" maxlength="10" size="10" tabindex="7" onKeyPress="return numeric2(event)" name="peso" id="peso"/>
        </td>
      </tr>
      <!-- tipo_actuacion_aduanera -->
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Tipo de Actuaci&oacute;n</div></td>
        <td class="celda_tabla_principal celda_boton">
              <select name="id_tipo_actuacion_aduanera" id="id_tipo_actuacion_aduanera" tabindex="8">                    
                  <?php
                      $sql="SELECT id,nombre FROM tipo_actuacion_aduanera WHERE estado='A' ORDER BY nombre ASC";
                      $consulta=mysql_query ($sql,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                      while($fila=mysql_fetch_array($consulta))
                      {
                        echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                      }
                  ?>
              </select>          
        </td>        
      </tr>      
      <!-- No de Acta de Actuacion Aduanera -->         
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Actuaci&oacute;n Aduanera No.</div></td>
        <td class="celda_tabla_principal celda_boton">
            <input type="text" maxlength="15" size="30" tabindex="9" name="courier_docAprehension" id="courier_docAprehension"/>
        </td>
      </tr>      
      <!-- Ubicación -->
      <tr>
        <td class="celda_tabla_principal" colspan="2"><div class="letreros_tabla">Ubicaci&oacute;n en Cuarto de: </div></td>        
      </tr>      
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Aprehensiones</div></td>
        <td class="celda_tabla_principal celda_boton"><input type="radio" name="modulo" value="1" checked="checked" tabindex="10">
              <select name="id_posicion_aprehensiones" id="id_posicion_aprehensiones" tabindex="11">                    
                  <?php
                      $sql="SELECT id,nombre FROM courier_posiciones WHERE modulo='1' AND estado='A' ORDER BY id ASC";
                      $consulta=mysql_query ($sql,$conexion) or die ("ERROR 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                      while($fila=mysql_fetch_array($consulta))
                      {
                        echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                      }
                  ?>
              </select>          
        </td>        
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Valores</div></td>        
        <td class="celda_tabla_principal celda_boton"><input type="radio" name="modulo" value="2" tabindex="12">
              <select name="id_posicion_valores" id="id_posicion_valores" tabindex="13">                    
                  <?php
                      $sql="SELECT id,nombre FROM courier_posiciones WHERE modulo='2' AND estado='A' ORDER BY id ASC";
                      $consulta=mysql_query ($sql,$conexion) or die ("ERROR 6: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                      while($fila=mysql_fetch_array($consulta))
                      {
                        echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                      }
                  ?>
              </select>          
        </td>
      </tr>
      <tr>
        <td class="celda_tabla_principal" colspan="2"><div class="letreros_tabla"></div></td>        
      </tr>
      <!-- Observacion -->           
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
        <td class="celda_tabla_principal celda_boton"><textarea cols="33" rows="5" tabindex="14" name="observaciones" id="observaciones"></textarea></td>
      </tr>      
    </table>
	  <input type="hidden" name="id_guia" id="id_guia" value="<?php echo $id_guia ?>"> 
    <table align="center">
        <tr>
            <td align="center" valign="middle" colspan="2">
                <div id="respuesta" class="opaco_ie" style="position:relative; background-image:url(imagenes/background.png);width:100%; height:30px"></div>
            </td>
        </tr>
    </table>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" tabindex="17" title="Atras" onclick="document.location='courier_inventario.php'">
                <img src="imagenes/al_principio-act.png" title="Atras" />
            </button>
            <button type="reset" name="reset" id="reset" tabindex="16" title="Limpiar">
                <img src="imagenes/descargar-act.png" title="Limpiar" />
            </button>
            <button type="button" name="guardar" id="guardar" tabindex="15" title="Guardar" onClick="return validar();">
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
  if (document.guardar_datos.destino.length == 0)
  {
    alert("Atencion: Debe ADICIONAR un FUNCIONARIO.");
    document.getElementById("origen").focus();
    return(false);
  }
	
  if (document.guardar_datos.no_guia.value=="")
  {
    alert("Atencion: Debe digitar un NUMERO DE GUIA.");
    document.getElementById("no_guia").focus();
    return(false);
  }
		
	if (document.guardar_datos.piezas.value=="")
	{
		alert("Atencion: Debe digitar un NUMERO DE PIEZAS.");
		document.getElementById("piezas").focus();
		return(false);
	}	

	if (document.guardar_datos.peso.value=="")
	{
		alert("Atencion: Debe digitar una cantidad de PESO.");
		document.getElementById("peso").focus();
		return(false);
	}
	
  //Procedimiento de Guardado
	guardar_form();		
}

function guardar_form()
{
	var peticion = new Request(
	{
		url: 'courier_guia_registro_salvar_hija.php',
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
			respuesta=responseText;
			$('respuesta').innerHTML='<p align="center">Proceso Finalizado</p>';
			alert ("Guia Creada Satisfactoriamente");
	      	//alert (respuesta);
	      	document.location='courier_inventario_AA.php';			
			$('guardar').disabled=false;
			$('reset').disabled=false;
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

function numeric(e) 
{ 
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 

//Validacion de campos numéricos
function numeric2(e) 
{ 
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9-.]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 


function seleccionar(indice,boton)
{
	if(boton=='enviar')
	{
		var valor = document.guardar_datos.origen.options[indice].value
		var textoEscogido = document.guardar_datos.origen.options[indice].text	
		var sel = document.getElementById('destino');	
		var opt = document.createElement('option');	
		opt.appendChild(document.createTextNode(textoEscogido));
		opt.value = valor;
		opt.selected= true;
		sel.appendChild(opt);
		var sel = document.getElementById('origen');
		sel.removeChild( sel.options[indice] ); 
	}
	else
	{
		var valor = document.guardar_datos.destino.options[indice].value
		var textoEscogido = document.guardar_datos.destino.options[indice].text		
		var sel = document.getElementById('origen');	
		var opt = document.createElement('option');	
		opt.appendChild(document.createTextNode(textoEscogido));
		opt.value = valor;	
		opt.selected= true;	
		sel.appendChild(opt); 
		var sel = document.getElementById('destino');
		sel.removeChild(sel.options[indice] ); 		
	}
}

function listar(id_entidad){
    //Busqueda Asincrona de personal de cada entidad seleccionada
    limpiarSelect();
    var myRequest = new Request({
    url: 'courier_funcionarios_entidad_ajax.php',
    method: 'get',
    onRequest: function(){
      $('procesando').innerHTML = "Procesando <img src='imagenes/cargando.gif'/>";
    },
    onSuccess: function(responseText){      
      var linea = responseText.split("--->");
      for (var i = 1; i <= linea.length - 1; i++) {
        var metadata = linea[i].split("{{{{");
        var val = metadata[0];
        var text = metadata[1];
        adicionarASelect(text,val);
      }      
      //$('procesando').innerHTML = responseText;
      $('procesando').innerHTML = "";
      
    },
    onFailure: function(){
      $('procesando').innerHTML = "Error";
    }
  }); 
  myRequest.send('id_entidad=' + id_entidad);  
}

function limpiarSelect(){
  var x = document.getElementById("origen");
  var limite = x.length;
  for(var i=0; i <= limite; i++){
    x.remove(0);
  }

  var y = document.getElementById("destino");
  var limite = y.length;
  for(var i=0; i <= limite; i++){
    y.remove(0);
  }
}


function adicionarASelect(texto,valor) {
  var x = document.getElementById("origen");
  var option = document.createElement("option");
  option.text = texto;
  option.value = valor;
  x.add(option);
}

</script>