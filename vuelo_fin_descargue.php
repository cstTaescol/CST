<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_usuario=$_SESSION['id_usuario'];
if(isset($_REQUEST["vuelo"]))
	$vuelo=$_REQUEST["vuelo"];
	else
		$vuelo= "Error al obtener el Vuelo";
		
//carga los datos del manifiesto si ya han sido ingresados
$sql2="SELECT nvuelo,hora_fin_descargue,fecha_fin_descargue FROM vuelo WHERE id='$vuelo' AND estado !='F' AND estado !='I'";
$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila2=mysql_fetch_array($consulta2))
	{
		$fecha=$fila2['fecha_fin_descargue'];
		$hora=$fila2['hora_fin_descargue'];
		$nvuelo=$fila2['nvuelo'];
		if ($hora == "")
		{
			$hora ="";
			$hh_hora="";
			$mm_hora="";
			$ss_hora="";
		}
			else
			{
				$hora=explode(":",$fila2['hora_fin_descargue']);
				$hh_hora=$hora[0];
				$mm_hora=$hora[1];
				$ss_hora=$hora[2];
			}
	}
//**********************************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
//Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 

// funcion para validar
function validar()
{
	if (document.forms[0].fecha.value=="")
	{
		alert("Atencion: Debe seleccionar la FECHA");
		document.forms[0].lanzador.focus();
		return(false);
	}

	if (document.forms[0].hh_hora.value=="")
	{
		alert("Atencion: Debe digitar la HORA COMPLETA");
		document.forms[0].hh_hora.focus();
		return(false);
	}
	if (document.forms[0].mm_hora.value=="")
	{
		alert("Atencion: Debe digitar la HORA COMPLETA");
		document.forms[0].mm_hora.focus();
		return(false);
	}
	if (document.forms[0].ss_hora.value=="")
	{
		alert("Atencion: Debe digitar la HORA COMPLETA");
		document.forms[0].ss_hora.focus();
		return(false);
	}
	if (document.forms[0].hh_hora.value > 23)
	{
		alert("Atencion: La HORA Maxima es 23");
		document.forms[0].hh_hora.focus();
		return(false);
	}
	if (document.forms[0].mm_hora.value > 59)
	{
		alert("Atencion: La los MINUTOS Maximos son 59");
		document.forms[0].mm_hora.focus();
		return(false);
	}	
	if (document.forms[0].ss_hora.value > 59)
	{
		alert("Atencion: La los SEGUNDOS Maximos son 59");
		document.forms[0].ss_hora.focus();
		return(false);
	}	
}
</script>
<!-Hoja de estilos del calendario -->
<link rel="stylesheet" type="text/css" media="all" href="js/calendar-color.css" title="win2k-cold-1" />
<!-- librería principal del calendario -->
<script type="text/javascript" src="js/calendar.js"></script>

<!-- librería para cargar el lenguaje deseado -->
<script type="text/javascript" src="js/calendar-es.js"></script>

<!-- librería que declara la función Calendar.setup, que ayuda a generar un calendario en unas pocas líneas de código -->
<script type="text/javascript" src="js/calendar-setup.js"></script>
</head>

<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Fin de Descargue</p>
<p class="asterisco" align="center">Vuelo No.  <?php if (isset($nvuelo)) echo $nvuelo ?></p>
<form name="arribo" method="post" action="vuelo_fin_descargue.php" onsubmit="return validar();">
    <table align="center">
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">No Vuelo</div></td>
        <td class="celda_tabla_principal"><div class="letreros_tabla"><?php echo $nvuelo; ?></div></td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha de Fin Descargue</div></td>
        <td class="celda_tabla_principal celda_boton">
            <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
            <input type="text" name="fecha" id="fecha" value="<?php echo $fecha ?>" readonly="readonly"/>
            <input type="button" id="lanzador" value="..." tabindex="1"/>
            <!-- script que define y configura el calendario-->
            <script type="text/javascript">
                Calendar.setup({
                    inputField     :    "fecha",      // id del campo de texto
                    ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                    button         :    "lanzador"   // el id del botón que lanzará el calendario
                });
				document.forms[0].lanzador.focus();
            </script>    
        </td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Hora de Fin Descargue</div></td>
        <td class="celda_tabla_principal celda_boton">
	        <input type="text" name="hh_hora" id="hh_hora" value="<?php echo $hh_hora ?>" size="2" maxlength="2" tabindex="2" onKeyPress="return numeric(event)"/>:
            <input type="text" name="mm_hora" id="mm_hora" value="<?php echo $mm_hora ?>" size="2" maxlength="2" tabindex="3" onKeyPress="return numeric(event)"/>:
            <input type="text" name="ss_hora" id="ss_hora" value="<?php echo $ss_hora ?>" size="2" maxlength="2" tabindex="4" onKeyPress="return numeric(event)"/>
            <input type="hidden" name="vuelo" id="vuelo" value="<?php echo $vuelo ?>"/>
        </td>
      </tr>
    </table>
        <table width="450" align="center">
            <tr>
              <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
            </tr>
            <tr>
              <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
                <button type="reset" name="reset" id="reset" tabindex="6"> <img src="imagenes/descargar-act.png" alt="" title="Limpiar" /></button>
                <button type="submit" name="guardar" id="guardar" tabindex="5"> <img src="imagenes/guardar-act.png" alt="" title="Guardar" /> </button>
              </td>
            </tr>
        </table>

</form>
<?php
// Al recibir la opcion de Guardar ********
if(isset($_POST["fecha"]))
{
	//Calcula rangos de fecha y hora frente a las registradas y del servidor
	$sql3="SELECT fecha_arribo,hora_llegada FROM vuelo WHERE id='$vuelo'";
	$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila3=mysql_fetch_array($consulta3);
	$fecha_arribo=explode("-",$fila3["fecha_arribo"]);
	$fecha_arribo=$fecha_arribo[0].$fecha_arribo[1].$fecha_arribo[2];
	$fecha_registrada=explode("-",$_POST["fecha"]);
	$fecha_registrada=$fecha_registrada[0].$fecha_registrada[1].$fecha_registrada[2];
	$fecha_servidor=date("Y").date("m").date("d");
	$hora_llegada=explode(":",$fila3["hora_llegada"]);
	$hora_llegada=$hora_llegada[0].$hora_llegada[1].$hora_llegada[2];
	$hora_registrada=$_POST["hh_hora"].$_POST["mm_hora"].$_POST["ss_hora"];
	$hora_servidor=date("His");
	$url_retorno="vuelo_fin_descargue.php";
	//Validacion de fechas frente al proceso anterior del vuelo y al servidor en horas y fechas 
	switch ($fecha_registrada)
	{
		case ($fecha_registrada == $fecha_arribo):
/*		
			if ($hora_registrada > $hora_servidor)
			{
				echo "<script language=\"javascript\">
						alert ('ERROR: No puede ingresar una HORA superior a la del Servidor');
						document.location='$url_retorno?vuelo=$vuelo';
					</script>";
				exit();
			}
*/
			if ($hora_registrada < $hora_llegada)
			{
				echo "<script language=\"javascript\">
						alert ('ERROR: La hora de FINALIZAR EL DESCARGUE no puede ser inferior a la hora de LLEGADA del Vuelo');
						document.location='$url_retorno?vuelo=$vuelo';
					</script>";
				exit();
			}
			else //mismo dia, pero hora correcta
				{
					//Si supero las validaciones, se actualiza el vuelo
					$fecha=$_POST["fecha"];
					$hora=$_POST["hh_hora"].":".$_POST["mm_hora"].":".$_POST["mm_hora"];
					$sql="UPDATE vuelo SET hora_fin_descargue='$hora', fecha_fin_descargue='$fecha', estado='D', usuario_findescargue='$id_usuario' WHERE id='$vuelo'";
					mysql_query($sql,$conexion) or die (mysql_error());
					echo '<script language="javascript"> 
							alert("Datos del Vuelo Actualizados de Manera Exitosa"); 
							document.location="base.php";
						</script>';
				}
		break;
		
		case ($fecha_registrada < $fecha_arribo):
			echo "<script language=\"javascript\">
					alert ('ERROR: La FECHA ingresada no puede ser inferior a la de LLEGADA del Vuelo');
					document.location='$url_retorno?vuelo=$vuelo';
				</script>";
				exit();
		break;
		
		case ($fecha_registrada > $fecha_arribo):
			switch($fecha_servidor)
			{
				case ($fecha_servidor == $fecha_registrada):   //Cuando el día de registro sea el dia en curso, no puede tener una hora mayor a la del servidor
					if ($hora_registrada > $hora_servidor)
						{
							echo "<script language=\"javascript\">
									alert ('ERROR: No puede ingresar una HORA superior a la del Servidor');
									document.location='$url_retorno?vuelo=$vuelo';
								</script>";
							exit();
						}
						else
						{
							//Si supero las validaciones, se actualiza el vuelo
							$fecha=$_POST["fecha"];
							$hora=$_POST["hh_hora"].":".$_POST["mm_hora"].":".$_POST["mm_hora"];
							$sql="UPDATE vuelo SET hora_fin_descargue='$hora', fecha_fin_descargue='$fecha', estado='D', usuario_findescargue='$id_usuario' WHERE id='$vuelo'";
							mysql_query($sql,$conexion) or die (mysql_error());
							echo '<script language="javascript"> 
									alert("Datos del Vuelo Actualizados de Manera Exitosa"); 
									document.location="base.php";
								</script>';
						}
				break;
				case ($fecha_servidor < $fecha_registrada):  //La fecha del servidor no puede ser inferior a la fecha registrada
					echo "<script language=\"javascript\">
							alert ('ERROR: FECHA no puede ser superior a la fecha del servidor');
							document.location='$url_retorno?vuelo=$vuelo';
						</script>";
					exit();
				break;
				case ($fecha_servidor > $fecha_registrada):  //entrada correcta
					//Si supero las validaciones, se actualiza el vuelo
					$fecha=$_POST["fecha"];
					$hora=$_POST["hh_hora"].":".$_POST["mm_hora"].":".$_POST["mm_hora"];
					$sql="UPDATE vuelo SET hora_fin_descargue='$hora', fecha_fin_descargue='$fecha', estado='D', usuario_findescargue='$id_usuario' WHERE id='$vuelo'";
					mysql_query($sql,$conexion) or die (mysql_error());
					echo '<script language="javascript"> 
							alert("Datos del Vuelo Actualizados de Manera Exitosa"); 
							document.location="base.php";
						</script>';
				break;
			}
		break;
	}
}
//******************************************
?>
</body>
</html>