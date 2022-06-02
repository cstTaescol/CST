<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$id_usuario=$_SESSION['id_usuario'];
/***********************************/
$id=$_GET['id'];
$metodo=$_GET['metodo'];
$id_guia=$_GET['id_guia'];
$subtipo=$_GET['subtipo'];


switch($metodo)
{
	case "eliminar":
		$nfactura=$_GET['nfactura'];
		//Consultamos Datos de la guia a eliminar		
		$sql="SELECT id_tipo_guia FROM guia WHERE id = '$id_guia'";	
		echo $sql;
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1:". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila=mysql_fetch_array($consulta);
		$id_tipo_guia=$fila['id_tipo_guia'];	
		echo "<BR>".$id_tipo_guia;
		if(($id_tipo_guia == 2) || ($id_tipo_guia == 5))
		{
			$sql2="SELECT id FROM guia WHERE master = '$id_guia'";
			echo "<br>".$sql2;
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 2:". mysql_error(). " INFORME AL SOPORTE TECNICO");
			while($fila2=mysql_fetch_array($consulta2))
			{
				$id_guia_hija=$fila2['id'];
				//Elimina la factura relacionada a la guia hija
				$sql="DELETE FROM guia_factura WHERE id_guia='$id_guia_hija' AND nfactura='$nfactura'";
				echo "<br>".$sql;
				mysql_query ($sql,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");

				//Ingresa Tracking a la guia con el mensaje por eliminacion 
				$sql_trak="INSERT INTO tracking (id_guia,
												fecha_creacion,
												hora,
												evento,
												tipo_tracking,
												id_usuario) 
													value ('$id_guia_hija',
															'$fecha',
															'$hora',
															'FACTURA No. $nfactura ELIMINADA',
															'1',
															'$id_usuario')";
				mysql_query($sql_trak,$conexion) or die ("ERROR 4: ".mysql_error());				
			}
		}
		//Elimina la factura relacionada a la guia master
		$sql="DELETE FROM guia_factura WHERE id=$id";
		echo "<br>".$sql;
		mysql_query ($sql,$conexion) or die ("ERROR 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");

		//Ingresa Tracking a la guia con el mensaje por eliminacion 
		$sql_trak="INSERT INTO tracking (id_guia,
										fecha_creacion,
										hora,
										evento,
										tipo_tracking,
										id_usuario) 
											value ('$id_guia',
													'$fecha',
													'$hora',
													'FACTURA No. $nfactura ELIMINADA',
													'1',
													'$id_usuario')";
		mysql_query($sql_trak,$conexion) or die ("ERROR 6: ".mysql_error());	
		header('Location: liberaciones_listar_factura.php?id_guia='.$id_guia.'&subtipo='.$subtipo.'');
		exit();
	break;
	
	case "actualizar":
		//Consulta a los datos de la Factura
		$sql="SELECT * FROM guia_factura WHERE id='$id'";
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila=mysql_fetch_array($consulta);
		$nfactura=$fila["nfactura"];
		$valor_factura=$fila["valor_factura"];	
		$iva=$fila["iva"];	
		$fecha_factura=$fila["fecha_factura"];	
		$facturadoa=$fila["facturadoa"];	
	break;	
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-Hoja de estilos del calendario -->
<!-- librería principal del calendario -->
<link rel="stylesheet" type="text/css" media="all" href="js/calendar-color.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/calendar.js"></script>

<!-- librería para cargar el lenguaje deseado -->
<script type="text/javascript" src="js/calendar-es.js"></script>

<!-- librería que declara la función Calendar.setup, que ayuda a generar un calendario en unas pocas líneas de código -->
<script type="text/javascript" src="js/calendar-setup.js"></script>
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
		alert("Atencion: Se requiere la FECHA de la FACTURA");
		document.forms[0].lanzador.focus();
		return(false);
	}
	if (document.forms[0].nfactura.value=="")
	{
		alert("Atencion: Se requiere el NUMERO de la FACTURA");
		document.forms[0].nfactura.focus();
		return(false);
	}
	if (document.forms[0].valor.value=="")
	{
		alert("Atencion: Se requiere el VALOR de la FACTURA");
		document.forms[0].valor.focus();
		return(false);
	}	
	if (document.forms[0].iva.value=="")
	{
		alert("Atencion: Se requiere el VALOR del IVA");
		document.forms[0].iva.focus();
		return(false);
	}	
}
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Liberaciones</p>
<form name="liberaciones" method="post" action="liberaciones_guardar_factura.php" onSubmit="return validar();">
		<table width="400" align="center" >
		  <tr>
		  	<td colspan="2"><span class="asterisco">Ingrese los datos relacionados a la Facturaci&oacute;n de esta Gu&iacute;a. </span></td>
		  </tr>
		  <tr>
			<td class="celda_tabla_principal"><div class="letreros_tabla">Fecha Factura</div></td>
			<td class="celda_tabla_principal celda_boton">
                <input name="metodo" id="metodo" type="hidden" value="actualizar"/>
                <input name="id_registro" id="id_registro" type="hidden" value="<?php echo $id; ?>"/>
                <input name="id_guia" id="id_guia" type="hidden" value="<?php echo $id_guia; ?>"/>
                <input name="subtipo" id="subtipo" type="hidden" value="<?php echo $subtipo; ?>"/>                
                <input name="fecha" id="fecha" type="text" size="10" readonly="readonly" tabindex="8" value="<?php echo $fecha_factura; ?>"/>
				<input type="button" id="lanzador" value="..." tabindex="1"/>
                <span class="asterisco">(*)	</span>              <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
				<!-- script que define y configura el calendario-->
				<script type="text/javascript">
					Calendar.setup({
						inputField     :    "fecha",      // id del campo de texto
						ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
						button         :    "lanzador"   // el id del botón que lanzará el calendario
					});
				</script>			
			<script>document.forms[0].lanzador.focus();</script>			</td>
		  </tr>
		  <tr>
			<td class="celda_tabla_principal"><div class="letreros_tabla">No. Factura</div></td>
			<td class="celda_tabla_principal celda_boton"><input name="nfactura" type="text" id="nfactura" tabindex="2" maxlength="10" value="<?php echo $nfactura; ?>">
		    <span class="asterisco">(*)</span><input type="hidden" name="id_guia" value="<?php echo $id_guia ?>"><input type="hidden" name="subtipo" value="<?php echo $subtipo ?>"></td>
		  </tr>
		  <tr>
			<td class="celda_tabla_principal"><div class="letreros_tabla">Valor</div></td>
			<td class="celda_tabla_principal celda_boton"><input name="valor" type="text" id="valor" tabindex="3" onKeyPress="return numeric(event)" maxlength="10" value="<?php echo $valor_factura; ?>">
		    <span class="asterisco">(*)</span></td>
		  </tr>
		  <tr>
			<td class="celda_tabla_principal"><div class="letreros_tabla">I.V.A.</div></td>
			<td class="celda_tabla_principal celda_boton"><input name="iva" type="text" id="iva" tabindex="3" onKeyPress="return numeric(event)" maxlength="10" value="<?php echo $iva; ?>">
		    <span class="asterisco">(*)</span></td>
		  </tr>
          
		  <tr>
		    <td class="celda_tabla_principal"><div class="letreros_tabla">Facturado a:</div></td>
		    <td class="celda_tabla_principal celda_boton"><input name="facturadoa" type="text" id="facturadoa" tabindex="4" maxlength="50" value="<?php echo $facturadoa; ?>"></td>
	      </tr>
	  </table>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
                <button name="Limpiar" type="reset" tabindex="6">
                    <img src="imagenes/descargar-act.png" title="Limpiar" />
                </button>
                
                <button name="Buscar" type="submit" tabindex="5">
                    <img src="imagenes/guardar-act.png" title="Guardar" />
                </button>
          </td>
        </tr>
    </table>     
</form>
</body>
</html>