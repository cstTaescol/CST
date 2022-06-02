<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$cantidadguias=$_POST["cantidadguias"];
$cont=0;
$error=false;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<style>
        .titulo_add{
            color:#FFF;
        }
		.opaco_ie { 
 			filter: alpha(opacity=30);
		} 
    </style>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Despachos de Mercancia</p>
<p class="asterisco" align="center">Transportador</p>
<p align="center"><img src="imagenes/3.jpg" width="186" height="67" alt="PASO 3" style="border-radius: 15px;" /></p>

<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Transportador</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $_SESSION["ntransportador"]; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Vehiculo</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $_SESSION["nvehiculo"]; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Conductor</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $_SESSION["nconductor"]; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Deposito</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $_SESSION["ndeposito"];?></td>
  </tr>
</table>
<br />
<form name="guardar_datos" id="guardar_datos" method="post">
<table align="center">
  <tr>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Master</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">No Guia</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
  </tr>
<?php
for($i=1; $i <= $cantidadguias; $i++)
{
	$guia="guia".$i;
	if (isset($_POST["$guia"]))
	{
		$cont++;
		$id_guia=$_POST["$guia"];
		$observacionesdespacho=strtoupper($_POST["observacionesdespacho$i"]);
		//Carga datos de guia
		$sql="SELECT master,hija,piezas_despacho,peso_despacho,volumen_despacho,piezas_inconsistencia,peso_inconsistencia,volumen_inconsistencia,piezas,peso,volumen,id_tipo_bloqueo,bloqueo_piezas,bloqueo_peso FROM guia WHERE id='$id_guia'";

		$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
		$fila=mysql_fetch_array($consulta);
		$master=$fila['master'];
		require("config/master.php");
		$hija=$fila['hija'];
		//**********************************************
		
		//Valores solicitados a Despachar***************
		$ckpiezas="piezas".$i;
		$ckpeso="peso".$i;
		$ckvolumen="volumen".$i;
		$ckpiezas=$_POST["$ckpiezas"];
		$ckpeso=$_POST["$ckpeso"];
		//**********************************************
	
		include("config/evaluador_inconsistencias.php"); //calcula y general el valor de $piezas, $peso y $volumen luego de las inconsistencias.
		$piezas_totales=$piezas; //para calcular el prorrateo del volumen
		$piezas_pendientes_despachar=$piezas-$fila["piezas_despacho"];
		$peso_pendientes_despachar=round($peso-$fila["peso_despacho"],2);		

		//URL de retorno en caso de algun error
		$url_retorno= "<meta http-equiv=\"Refresh\" content=\"0;url=despacho_transportador2.php?transportador=".$_SESSION["transportador"]."&vehiculo=".$_SESSION["vehiculo"]."&conductor=".$_SESSION["conductor"]."&deposito=".$_SESSION["deposito"]."&id_aerolinea=".$_SESSION["id_aerolinea"]."&planilla_envio=".$_SESSION["planilla_envio"]."&planilla_envio=".$_SESSION["planilla_envio"]."\">";
		
		//verificacion de existencias de datos solicitadas para despachar
		if ($ckpiezas > $piezas_pendientes_despachar || $ckpiezas == "" || $ckpiezas =="0")
			{
				echo '<script type="text/javascript">
						alert("ERROR: Esta intentando despachar una cantidad de PIEZAS ('.$ckpiezas.') que no concuerda con las que hay en existencia ('.$piezas_pendientes_despachar.') para la Guia '.$hija.'")
				 		</script>';
				echo $url_retorno;
				exit();
			}
		else 
			{
				$piezas=$ckpiezas;
			}

		if ($ckpeso > $peso_pendientes_despachar || $ckpeso == "" || $ckpeso =="0")
			{
				echo '<script type="text/javascript">
						alert("ERROR: Esta intentando despachar mas PESO ('.$ckpeso.') del que hay en existencia ('.$peso_pendientes_despachar.') para la Guia '.$hija.'")
					   </script>';
				echo $url_retorno;
				exit();
			}
		else 
			{
				$peso=$ckpeso;
			}
		//Verificacion de valores cuando la guia tiene bloqueo Parcial.
		$id_tipo_bloqueo=$fila["id_tipo_bloqueo"];
		if ($id_tipo_bloqueo == 10) //Bloqueo Parcial
		{
			if ($ckpiezas > ($piezas_pendientes_despachar-$fila["bloqueo_piezas"]))
			{
				echo '<script type="text/javascript">
						alert("ERROR: Esta intentando despachar una cantidad de PIEZAS ('.$ckpiezas.') que no concuerda con las que hay DESBLOQUEADAS en existencia ('.($piezas_pendientes_despachar-$fila["bloqueo_piezas"]).') para la Guia '.$hija.'")
				 		</script>';
				echo $url_retorno;
				exit();				
			}
			
			if ($ckpeso > ($peso_pendientes_despachar-$fila["bloqueo_peso"]))
			{
				echo '<script type="text/javascript">
						alert("ERROR: Esta intentando despachar una cantidad de PESO ('.$ckpeso.') que no concuerda con el que hay DESBLOQUEADO en existencia ('.($peso_pendientes_despachar-$fila["bloqueo_peso"]).') para la Guia '.$hija.'")
				 		</script>';
				echo $url_retorno;
				exit();				
			}

		}
		//**calcula el volumen prorrateado
		$volumen=round(($volumen/$piezas_totales)*$piezas,1);
		
//Evauaci�n de guia completa o guia parcial, se evalua cada uno de los items y que uno solo no quede en cero
//***************************************************************************
	   	if (($piezas_pendientes_despachar - $piezas) == 0)
			{
				if (($peso_pendientes_despachar-$peso) == 0)
					{
						$error=false;
						$completa="S";
					}
				else //piezas en 0 pero peso no
					{
						$error=true;
					}
			}
			else //piezas diferentes a 0
			{
				if (($peso_pendientes_despachar-$peso) != 0) //piezas diferentes a 0 y peso diferente a 0
					{
						$error=false;
						$completa="N";
					}
					else //piezas diferentes a 0 pero peso si es 0
						{
							$error=true;	
						}
			}		
		if ($error==true)
		{
			echo '<script type="text/javascript">
					alert("ERROR: Despache la guia COMPLETA o deje RESTANTE en: Piezas y Peso, el Volumen sera Prorrateado");
				</script>';
			echo '<meta http-equiv="Refresh" content="0;url=despacho_transportador2.php?transportador='.$_SESSION["transportador"].'&vehiculo='.$_SESSION["vehiculo"].'&conductor='.$_SESSION["conductor"].'&deposito='.$_SESSION["deposito"].'&id_aerolinea='.$_SESSION["id_aerolinea"].'">';
			exit();
		}
//*****************************************************************************
		echo '<tr>
				<td class="celda_tabla_principal celda_boton">'.$master.'</td>
				<td class="celda_tabla_principal celda_boton">'.$hija.'</td>
				<td class="celda_tabla_principal celda_boton">'.$piezas.'</td>
				<td class="celda_tabla_principal celda_boton">'.$peso.'</td>
				<td class="celda_tabla_principal celda_boton">'.$volumen.'</td>
				<td class="celda_tabla_principal celda_boton">'.$observacionesdespacho.'</td>
				
			  </tr>
			<input type="hidden" name="guia'.$i.'" value="'.$id_guia.'">
			<input type="hidden" name="peso'.$i.'" value="'.$peso.'">
			<input type="hidden" name="piezas'.$i.'" value="'.$piezas.'">
			<input type="hidden" name="volumen'.$i.'" value="'.$volumen.'">
			<input type="hidden" name="observacionesdespacho'.$i.'" value="'.$observacionesdespacho.'">
			
			<input type="hidden" name="completa'.$i.'" value="'.$completa.'">
			';
	}
}
//Cuando no ha seleccionado ninguna guia del listado.
if ($cont == 0)
{
	echo '<script type="text/javascript">
			alert("ERROR: Debe seleccionar alguna GUIA")
		   </script>';
	echo '<meta http-equiv="Refresh" content="0;url=despacho_transportador2.php?transportador='.$_SESSION["transportador"].'&vehiculo='.$_SESSION["vehiculo"].'&conductor='.$_SESSION["conductor"].'&deposito='.$_SESSION["deposito"].'&id_aerolinea='.$_SESSION["id_aerolinea"].'">';
	exit();
}
?>
</table>
<br />
<table align="center">
  <tr>
    <td class="celda_tabla_principal" style="background-color:#FFFF00"><div class="letreros_tabla">Exclusivo</div></td>
    <td class="celda_tabla_principal celda_boton"><input type="checkbox" name="exclusivo" value="S" /></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal" style="background-color:#00CCCC"><div class="letreros_tabla">Refrigerado</div></td>
    <td class="celda_tabla_principal celda_boton"><input type="checkbox" name="refrigerado" value="S" /></td>
  </tr>
  <tr>
    <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Observaciones de Remesa</div></td>
    <td class="celda_tabla_principal celda_boton"><textarea name="observaciones" cols="30" rows="5"></textarea></td>
  </tr>
</table>

<table align="center">
	<tr>
		<td align="center" valign="middle" colspan="2">
        	<div id="respuesta" class="opaco_ie" style="position:relative; background-image:url(imagenes/background.png);width:100%; height:30px"></div>
        </td>
	</tr>
</table>
<input type="hidden" name="cantidadguias" value="<?php echo $cantidadguias ?>" />
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
        <button type="reset" name="reset" id="reset">
            <img src="imagenes/descargar-act.png" title="Limpiar" /><br />
        </button>
        <button type="button" name="guardar" id="guardar" onClick="return validar();">
            <img src="imagenes/guardar-act.png" title="Guardar" /><br />
        </button>
      </td>
    </tr>
</table>    
</form>
</body>
</html>
<script language="JavaScript">
// funcion para validar
function validar()
{	
	guardar_form();
}


function guardar_form()
{
	var peticion = new Request(
	{
		url: 'despacho_transportador4.php',
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
			id_despacho=responseText;
			$('respuesta').innerHTML='<p align="center">Proceso Finalizado</p>';
			document.location='despacho_transportador5.php?id_despacho='+id_despacho;
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
</script>