<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if(isset($_REQUEST["id_guia"]))
	{
		$id_guia=$_REQUEST["id_guia"];
		$sql="SELECT * FROM guia WHERE id=$id_guia";
		$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
		$fila=mysql_fetch_array($consulta);

		$id_tipo_guia=$fila["id_tipo_guia"];
		if ($id_tipo_guia == 2) //Guias master se modifican en otro modulo
		{
			echo "<script>
    					document.location='modificar_consolidado.php?id_guia=$id_guia';
    				</script>";
			exit();
		}
		
		$id_objeto=153; 
		$resultado=privilegios($id_objeto);
		if ($resultado != true)
		{
			$id_tipo_bloqueo=$fila["id_tipo_bloqueo"];
			if ($id_tipo_bloqueo == 5 || $id_tipo_bloqueo == 8) // bloqueada, anulada
			{
				echo "<script>
    						alert(\"Esta Guia no puede ser modificada\");
    						document.location='consulta_guia.php?id_guia=$id_guia';
  					  </script>";
				exit();
			}
		}
		$hija=$fila["hija"];
		$descripcion=$fila["descripcion"];
		$id_disposicion=$fila["id_disposicion"];
		$id_deposito=$fila["id_deposito"];
		$id_agente_carga=$fila["id_agentedecarga"];
		$id_consignatario=$fila["id_consignatario"];
		$id_embarcador=$fila["id_embarcador"];
		$id_administracion_aduana=$fila["id_administracion_aduana"];
		$piezas_faltantes=$fila["piezas_faltantes"];
		//nuevos
		$id_tipo_carga=$fila["id_tipo_carga"];
		$cuarto_frio=$fila["cuarto_frio"];
		$precursores=$fila["precursores"];
		$asignacion_directa=$fila["asignacion_directa"];
		$id_agente_carga=$fila["id_agentedecarga"];
		//
		include("config/evaluador_inconsistencias.php"); //calcula y general el valor de $piezas, $peso y $volumen luego de las inconsistencias.    
		$flete=$fila["flete"];
	}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Modificacion de Guia</p>
<form name="mod_guia" id="mod_guia" method="post">
<input type="hidden" name="cod_disposicion" id="cod_disposicion" value="" >
<input type="hidden" name="cod_tipo_deposito" id="cod_tipo_deposito" value="" >
<input type="hidden" name="cod_deposito" id="cod_deposito" value="">
<input type="hidden" name="id_nueva_aduana"  id="id_nueva_aduana" value=""/>
<input type="hidden" name="cod_ciudad_destino"  id="cod_ciudad_destino" value="" />
<input type="hidden" name="cod_departamento_destino"  id="cod_departamento_destino" value="" />

<table align="center">
  <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Guia Hija</div></td>
        <td class="celda_tabla_principal celda_boton">
            <input type="text" name="hija" id="hija" value="<?php echo $hija?>" tabindex="1"><font color="#FF0000">(*)</font>
            <input type="hidden" name="id_guia" id="id_guia" value="<?php echo $id_guia?>">
        </td>
  </tr>
  <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Aduana</div></td>
        <td class="celda_tabla_principal celda_boton">
          <?php
            $sql="SELECT id,nombre FROM admon_aduana WHERE id ='$id_administracion_aduana'";
            $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
            $fila=mysql_fetch_array($consulta);
            echo $fila['nombre'];
          ?>
          <input name="cod_admon_aduana" type="hidden" id="cod_admon_aduana" value="<?php echo $id_administracion_aduana?>" size="5">
        </td>    
  </tr>
    <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Disposicion Actual</div></td>
        <td class="celda_tabla_principal celda_boton" >
           <?php
                $sql="SELECT id,nombre FROM disposicion_cargue WHERE id ='$id_disposicion'";
                $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                $fila=mysql_fetch_array($consulta);
                echo $fila['nombre'];
            ?>
     </td>
     <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Deposito Actual</div></td>
        <td class="celda_tabla_principal celda_boton">
            <?php
                if ($id_deposito != 0)
				{				
					$sql="SELECT id,nombre FROM deposito WHERE id ='$id_deposito'";
					$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
					$fila=mysql_fetch_array($consulta);
					echo $fila['nombre'];
				}
				else
					{
						echo "SIN DEPOSITO";					
					}
            ?>
        </td>
      </tr>
     <tr>
        <td class="celda_tabla_principal">
        	<div class="letreros_tabla">Nuevo Destino 
            	<input type="checkbox" name="ck_nuevodeposito" id="ck_nuevodeposito" value="1" onClick="activadores();" tabindex="2">
            </div>
         </td>
        <td class="celda_tabla_principal celda_boton">
             <font color="#FF0000" size="-2">Nueva Disposicion</font>
          	 <select name="disposicion" id="disposicion" tabindex="11" disabled="disabled" onChange="nuevo_destino(this.value)">
                    <option value=" " selected>Seleccione Uno</option>
					<?php
                        $sql="SELECT id,nombre FROM disposicion_cargue WHERE estado='A' ORDER BY nombre";
                        $consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
                        while($fila=mysql_fetch_array($consulta))
                        {
                            echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                        }
                    ?>
              </select>
              <div id="respuesta1"></div>
              <div id="respuesta2"></div>
              <div id="respuesta3"></div>
        </td>
      </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Agente de Carga</div></td>
    <td class="celda_tabla_principal celda_boton">
        <select name="agente_carga" id="agente_carga" tabindex="3">
        <option value="">SIN DATO</option>
          <?php
                $sql="SELECT id,razon_social FROM agente_carga WHERE estado='A' ORDER BY razon_social";
                $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                while($fila=mysql_fetch_array($consulta))
                {
                    if ($fila['id'] == $id_agente_carga) 
                        $seleccion="selected='selected'";
                        else
                            $seleccion="";
                    echo '<option value="'.$fila['id'].'" '.$seleccion.'>'.$fila['razon_social'].'</option>';
                }
            ?>
        </select>
        <font color="#FF0000">(*)</font>
     </td>    
   </tr>
   <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Embarcador</div></td>
        <td class="celda_tabla_principal celda_boton">
              <select name="embarcador" id="embarcador" tabindex="4">
                <?php
                    $sql="SELECT id,nombre FROM embarcador WHERE estado='A' ORDER BY nombre";
                    $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                    while($fila=mysql_fetch_array($consulta))
                    {
                        if ($fila['id'] == $id_embarcador) 
                            $seleccion="selected='selected'";
                            else
                                $seleccion="";
                        echo '<option value="'.$fila['id'].'" '.$seleccion.'>'.$fila['nombre'].'</option>';
                    }
                ?>
              </select>
              <font color="#FF0000">(*)</font>
       </td>
   </tr>
   <tr>
   		<td class="celda_tabla_principal"><div class="letreros_tabla">Consignatario</div></td>
      <td class="celda_tabla_principal celda_boton">
          <select name="consignatario" id="consignatario" tabindex="5">
            <?php
				$sql="SELECT id,nombre FROM consignatario WHERE id = '$id_consignatario'";
                  $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                  $fila=mysql_fetch_array($consulta);
				echo '<option value="'.$fila['id'].'" selected>'.substr($fila['nombre'],0,35).'</option>';
				
				$sql="SELECT id,nombre FROM consignatario WHERE estado = 'A' ORDER BY nombre";
                  $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                  while($fila=mysql_fetch_array($consulta))
                  {
                      if ($fila['id'] == $id_consignatario) 
                          $seleccion="selected='selected'";
                          else
                              $seleccion="";
                      echo '<option value="'.$fila['id'].'" '.$seleccion.'>'.substr($fila['nombre'],0,35).'</option>';
                  }
              ?>
           </select>
           <font color="#FF0000">(*)</font>
      </td>
	</tr>
    <tr>
		  <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
		  <td class="celda_tabla_principal celda_boton">
        	<input name="piezas" type="text" id="piezas" tabindex="5" onKeyPress="return numeric(event)" value="<?php echo $piezas?>" size="5" maxlength="10"><font color="#FF0000">(*)</font>
      </td>
  </tr>
  <tr>
    	<td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    	<td class="celda_tabla_principal celda_boton">
        	<input name="peso" type="text" id="peso" tabindex="6" onKeyPress="return numeric2(event)" value="<?php echo $peso?>" size="5" maxlength="10"><font color="#FF0000">(*)</font>
      </td>
  </tr>

  <tr>
    	<td class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
    	<td class="celda_tabla_principal celda_boton">
        	<input name="volumen" type="text" id="volumen" tabindex="7" onKeyPress="return numeric2(event)" value="<?php echo $volumen?>" size="5" maxlength="10">
      </td>
   </tr>
  <tr>
    	<td class="celda_tabla_principal"><div class="letreros_tabla">Tipo de Carga</div></td>
      <td class="celda_tabla_principal celda_boton">
          <select name="id_tipo_carga" id="id_tipo_carga" tabindex="8" >
            <?php
                  $sql="SELECT id,nombre FROM tipo_carga WHERE estado='A' ORDER BY nombre";
                  $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                  while($fila=mysql_fetch_array($consulta))
                  {
                      if ($fila['id'] == $id_tipo_carga) 
                          $seleccion="selected='selected'";
                          else
                              $seleccion="";
                      echo '<option value="'.$fila['id'].'" '.$seleccion.'>'.$fila['nombre'].'</option>';
                  }
              ?>
          </select>
      </td>
  </tr>
  <tr>
	    <td class="celda_tabla_principal"><div class="letreros_tabla">Flete</div></td>
    	<td class="celda_tabla_principal celda_boton">
        	<input name="flete" type="text" id="flete" tabindex="9" onKeyPress="return numeric(event)" value="<?php echo $flete?>" size="10" maxlength="15">
        </td>
  </tr>
  <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Requiere Cuarto Fr&iacute;o</div></td>
        <td class="celda_tabla_principal celda_boton"> 
            NO <input type="radio" name="cuarto_frio" value="N" <?php if ($cuarto_frio == "N") echo "checked=\"checked\""; ?> tabindex="12" />
            SI <input type="radio" name="cuarto_frio" value="S" <?php if ($cuarto_frio == "S") echo "checked=\"checked\""; ?> tabindex="13"/>
            <img src="imagenes/ice.fw.png" width="33" height="29"  align="absmiddle" />
            <font color="#FF0000">(*)</font>
      </td>
  </tr>
  <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Precursores</div></td>
        <td class="celda_tabla_principal celda_boton"> 
            NO <input type="radio" name="precursor" value="N" <?php if ($precursores == "N") echo "checked=\"checked\""; ?> tabindex="14" />
            SI <input type="radio" name="precursor" value="S" <?php if ($precursores == "S") echo "checked=\"checked\""; ?> tabindex="15"/>
              <font color="#FF0000">(*)</font>
      </td>
  </tr>
  <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Asignacion de Origen</div></td>
        <td class="celda_tabla_principal celda_boton"> 
            NO <input type="radio" name="asignacion_directa" value="N" <?php if ($asignacion_directa == "N") echo "checked=\"checked\""; ?> tabindex="16" /> 
            SI <input type="radio" name="asignacion_directa" value="S" <?php if ($asignacion_directa == "S") echo "checked=\"checked\""; ?> tabindex="17"/>
              <font color="#FF0000">(*)</font>
        </td>
  </tr>
  <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Descripcion</div></td>
      <td class="celda_tabla_principal celda_boton"><input type="text" size="30" id="descripcion" name="descripcion" tabindex="18" value="<?php echo $descripcion ?>">
        <font color="#FF0000">(*)</font>
      </td>
  </tr>
  <tr>
  	<td colspan="2" align="center">
    	<div id="salvado"></div>
    </td>
  </tr>
</table>

<table width="450" align="center">
  <tr>
    <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
  </tr>
  <tr>
    <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
      <button type="reset" tabindex="19"> <img src="imagenes/descargar-act.png" title="Limpiar" /></button>
      <button type="button" tabindex="20" onClick="validar()"> <img src="imagenes/guardar-act.png" title="Guardar" /></button>
    </td>
  </tr>
</table>
</form>
</body>
</html>
<script language="javascript">
var disposicion;
var nuevo_tipo_deposito;
//-------------------------------------------------------
//Activa o Desactiva el sistema de combos recargables para modificar el deposito.
function activadores(){
	document.mod_guia.disposicion.disabled = !document.mod_guia.ck_nuevodeposito.checked;
	document.mod_guia.tipo_deposito.disabled = !document.mod_guia.ck_nuevodeposito.checked;
	document.mod_guia.deposito.disabled = !document.mod_guia.ck_nuevodeposito.checked;
}
//-------------------------------------------------------

function nuevo_destino(disposicionlocal)
{
	$('cod_disposicion').value="";
	$('cod_deposito').value="";
	$('cod_tipo_deposito').value="";
	$('id_nueva_aduana').value="";
	$('cod_departamento_destino').value="";
	$('cod_ciudad_destino').value="";	
	$('respuesta2').innerHTML="";
	$('respuesta3').innerHTML="";
	nuevo_tipo_deposito="";
	disposicion = disposicionlocal;
	switch(disposicion)
	{
		case " ":
			$('respuesta1').innerHTML='Seleccione una alguna Disposicion';
		break;
		
		default:
			$('cod_disposicion').value=disposicion;
			cargar_tipo_deposito(disposicion);
		break;
	}	
}


function cargar_tipo_deposito(disposicion)
{	
	var peticion = new Request(
	{
		url: 'ajax_tipo_deposito2.php',
		method: 'get',
		onRequest: function()
		{
			$('respuesta1').innerHTML='<p align="center">Procesando...<img src="imagenes/cargando.gif"></p>';
		},			
		onSuccess: function(responseText)
		{
			var respuesta;
			respuesta=responseText;
			$('respuesta1').innerHTML=respuesta;			
		},
		onFailure: function()
		{
			$('respuesta1').innerHTML='<p align="center">Error al procesar la informacion...</p>';
		}
	}
	);
	peticion.send('disposicion='+disposicion);
}

function evaluador(tipo_deposito)
{
	$('respuesta3').innerHTML="";
	$('cod_tipo_deposito').value=tipo_deposito;
	nuevo_tipo_deposito=tipo_deposito;
	$('cod_deposito').value="";
	$('id_nueva_aduana').value="";
	$('cod_departamento_destino').value="";
	$('cod_ciudad_destino').value="";

	var peticion = new Request(
	{
		url: 'ajax_evaluador2.php',
		method: 'get',
		onRequest: function()
		{
			$('respuesta2').innerHTML='<p align="center">Procesando...<img src="imagenes/cargando.gif"></p>';
		},			
		onSuccess: function(responseText)
		{
			var respuesta;
			respuesta=responseText;
			$('respuesta2').innerHTML=respuesta;
		},
		onFailure: function()
		{
			$('respuesta2').innerHTML='<p align="center">Error al procesar la informacion...</p>';
		}
	}
	);
	peticion.send('disposicion='+disposicion+'&tipodeposito='+tipo_deposito+'&admon_aduana='+<?php echo $id_administracion_aduana ?>);
}

function nueva_aduana(nueva_aduana)
{
	$('id_nueva_aduana').value=nueva_aduana;
	$('cod_deposito').value="";
	$('cod_departamento_destino').value="";
	$('cod_ciudad_destino').value="";
	
	var peticion = new Request(
	{
		url: 'ajax_deposito2.php',
		method: 'get',
		onRequest: function()
		{
			$('respuesta3').innerHTML='<p align="center">Procesando...<img src="imagenes/cargando.gif"></p>';
		},			
		onSuccess: function(responseText)
		{
			var respuesta;
			respuesta=responseText;
			$('respuesta3').innerHTML=respuesta;
		},
		onFailure: function()
		{
			$('respuesta3').innerHTML='<p align="center">Error al procesar la informacion...</p>';
		}
	}
	);
	peticion.send('aduana='+nueva_aduana+'&tipodeposito='+nuevo_tipo_deposito);
}


function pasar_deposito(deposito)
{
	$('cod_deposito').value=deposito;
}

function pasar_destinos(nuevo_departamento,nuevo_ciudad)
{
	$('cod_departamento_destino').value=nuevo_departamento;
	$('cod_ciudad_destino').value=nuevo_ciudad;
	
}
</script>

<script type="text/javascript">
//**************************************************************************
function validar()
{	
	if (document.forms[0].hija.value=="")
	{
		alert("Atencion: Se requiere el NUMERO DE GUIA");
		document.forms[0].hija.focus();
		return(false);
	}

	if (document.forms[0].ck_nuevodeposito.checked==true)
	{
		var nueva_disposicion=document.forms[0].cod_disposicion.value;
		var msgalerta="Complete el proceso de asignacion del nuevo Destino";
		switch(nueva_disposicion)
		{
			//Requieren nueva aduana
			case "12":
				if($('cod_ciudad_destino').value=="" || $('cod_deposito').value=="")
				{
					alert(msgalerta);
					return(false);
				}
			break;
			case "16":
				if($('cod_ciudad_destino').value=="" || $('cod_deposito').value=="")
				{
					alert(msgalerta);
					return(false);
				}
			break;
			case "17":
				if($('cod_ciudad_destino').value=="" || $('cod_deposito').value=="")
				{
					alert(msgalerta);
					return(false);
				}
			break;
			case "24":
				if($('cod_ciudad_destino').value=="" || $('cod_deposito').value=="")
				{
					alert(msgalerta);
					return(false);
				}
			break;			
			//No requieren nueva aduana
			case "18":
				if($('cod_tipo_deposito').value=="" || $('cod_deposito').value=="")
				{
					alert(msgalerta);
					return(false);
				}
			break;
			case "10":
				if($('cod_tipo_deposito').value=="" || $('cod_deposito').value=="")
				{
					alert(msgalerta);
					return(false);
				}
			break;
			case "22":
				if($('cod_tipo_deposito').value=="" || $('cod_deposito').value=="")
				{
					alert(msgalerta);
					return(false);
				}
			break;
			case "11":
				if($('cod_tipo_deposito').value=="" || $('cod_deposito').value=="")
				{
					alert(msgalerta);
					return(false);
				}
			break;
			////No necesitan sino nueva disposicion los casos 28 - 21 - 20 - 25 - 19 - 29 - 23
		}
	}
	
	
	if (document.forms[0].piezas.value=="")
	{
		alert("Atencion: Se requiere las PIEZAS");
		document.forms[0].piezas.focus();
		return(false);
	}
	if (document.forms[0].peso.value=="")
	{
		alert("Atencion: Se requiere el PESO");
		document.forms[0].peso.focus();
		return(false);
	}
	if (document.forms[0].descripcion.value=="")
	{
		alert("Atencion: Se requiere la DESCRIPCION");
		document.forms[0].descripcion.focus();
		return(false);
	}
	
	
	guardarform();
}
//**************************************************************************

//Guardar capos del formulario
function guardarform()
{
	var peticion = new Request(
	{
		url: 'modificar_guia2.php',
		method: 'post',
		onRequest: function()
		{
			$('salvado').innerHTML='<p align="center">Procesando...<img src="imagenes/cargando.gif"></p>';
		},			
		onSuccess: function(responseText)
		{
			var respuesta;
			respuesta=""+responseText;
			if (respuesta == "1")
			{
				alert("Registro Almacenado de Manera Exitosa");
				document.location="consulta_guia.php?id_guia=<?php echo $id_guia ?>";
			}
			else
			{
				$('salvado').innerHTML="Error: "+respuesta;	
			}
			
		},
		onFailure: function()
		{
			$('salvado').innerHTML='<p align="center">Error al procesar la informacion...</p>';
		}
	}
	);
	peticion.send($('mod_guia'));
}
//*****




//Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 

function numeric2(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9-.]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 
//******************************************************************************

// función que permite abrir ventanas emergentes con las propiedades deseadas
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
