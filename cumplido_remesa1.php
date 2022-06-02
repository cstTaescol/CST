<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$i=0;
$transportador="";
$fecha_registrada="";
$id_vehiculo="";
$conductor="";
$deposito="";
$id_remesa="";
$impresion="";
$estado_botones='disabled="disabled"';
if (isset($_POST["nremesa"]))
{
	
	$id_remesa=$_POST["nremesa"];
	$sql="SELECT * FROM remesa WHERE id = '$id_remesa' AND estado='A'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
	{
		//Discriminacion de aerolinea de usuario TIPO 5						
		$sql2="SELECT g.id_aerolinea,c.* FROM guia g LEFT JOIN carga_remesa c ON g.id=c.id_guia WHERE c.id_remesa='$id_remesa'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$id_aerolinea=$fila2['id_aerolinea'];
		$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
		//Verificamos permiso de administrador o permiso de aerolinea
		if ($id_aerolinea_user != "*")
		{
			if($id_aerolinea_user != $id_aerolinea)
			{
				echo "<script>alert('ALERTA:No tiene privilegios de ver esta informacion.');</script>";
				echo "<script>document.location='".$_SERVER['SCRIPT_NAME']."';</script>";
				exit();
			}
		}
		//*************************************
		
		$estado_botones='';
		$fecha_registrada=$fila["fecha"];
		$id_deposito=$fila["id_deposito"];
		$id_transportador=$fila["id_transportador"]; 
		$id_vehiculo=$fila["id_vehiculo"];
		$id_conductor=$fila["id_conductor"];
		
		//carga datos
		$sql2="SELECT nombre FROM deposito WHERE id='$id_deposito'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 2". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$deposito=$fila2['nombre'];
	
		//carga datos
		$sql2="SELECT nombre FROM transportador WHERE id='$id_transportador'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 3". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$transportador=$fila2['nombre'];

		//carga datos
		$sql2="SELECT nombre FROM conductor WHERE id='$id_conductor'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 4". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$conductor=$fila2['nombre'];
		
		//carga datos
		$sql_guias="SELECT * FROM carga_remesa WHERE id_remesa='$id_remesa'";
		$consult_guias=mysql_query ($sql_guias,$conexion) or die ("ERROR: 5". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$impresion=$impresion.'<table border="1" cellpadding="0" cellspacing="0">';
		while($fila_guias=mysql_fetch_array($consult_guias))
		{
			$i++;
			$id_guia=$fila_guias['id_guia'];
			$piezas=$fila_guias['piezas'];
			$peso=$fila_guias['peso'];
			$volumen=$fila_guias['volumen'];
			
			//carga datos
			$sql2="SELECT hija FROM guia WHERE id='$id_guia'";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 6". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila2=mysql_fetch_array($consulta2);
			$hija=$fila2['hija'];
			$impresion=$impresion.'
			  <tr>
				<td class="celda_tabla_principal">GUIA:</td>
				<td class="celda_tabla_principal celda_boton">'.$hija.'</td>
			  </tr>
			  <tr>
				<td class="celda_tabla_principal">PIEZAS:</td>
				<td class="celda_tabla_principal celda_boton">'.$piezas.'</td>
			  </tr>
			  <tr>
				<td class="celda_tabla_principal">PESO:</td>
				<td class="celda_tabla_principal celda_boton">'.$peso.'</td>
			  </tr>
			  <tr>
				<td class="celda_tabla_principal">VOLUMEN:</td>
				<td class="celda_tabla_principal celda_boton">'.$volumen.'</td>
			  </tr>
			  <tr>
				<td class="celda_tabla_principal">OBSERVACIONES:</td>
				<td class="celda_tabla_principal celda_boton">
					<input type="text" name="descripcion'.$i.'" value="">
					<input type="hidden" name="id_guia'.$i.'" value="'.$id_guia.'">
				</td>
			  </tr>
			';
		}
		$impresion=$impresion.'</table>';
	}	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function abrir(url)
{
	popupWin = window.open(url,'','directories, status, scrollbars, resizable, dependent, width=640, height=480, left=100, top=100')
	//  popupWin = window.open('pdf_remesa.php','nombre_ventana','menubar, toolbar, location, directories, status, scrollbars, resizable, dependent, width=640, height=480, left=0, top=0')
}

// funcion para validar
function validar()
{	
	if (document.registro.scan.value=="")
	{
		alert("Atencion: Se requiere que Adjunte el  ARCHIVO DE CUMPLIDO");
		return(false);
	}
}

</script>
</head>
<body>
<?php require("menu.php"); ?>
<p class="titulo_tab_principal">Reportar Cumplido</p>
<form method="post" name="consulta" id="consulta" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" >
<table align="center">
    <tr>
        <td class="celda_tabla_principal">
            <p class="asterisco">Remesa No.</p>
        </td>
        <td class="celda_tabla_principal">
			<input type="text"  name="nremesa" id="nremesa" tabindex="1" size="35" maxlength="20" />  
            <script>document.forms[0].guia.focus();</script>            
        </td>
        <td class="celda_tabla_principal">
			<button type="submit" tabindex="2">
            	<img src="imagenes/buscar-act.png" title="Buscar" align="absmiddle" />
            </button>
        </td>        
    </tr>
</table>
</form>
<script language="javascript">document.getElementById('nremesa').focus();</script>
<hr />
<form method="post" name="registro" id="registro" onsubmit="return validar();" action="cumplido_remesa2.php"  enctype="multipart/form-data">
<table width="700px" align="center">
  <tr>
    <td class="celda_tabla_principal">
        <p align="center" class="asterisco" >Descripcion de la Remesa</p>
        <font class="asterisco"><strong>Remesa No:</strong></font><?php echo $id_remesa ?>
        	<button type="button" onClick="abrir('pdf_remesa.php?id_remesa=<?php echo $id_remesa ?>')" <?php echo $estado_botones?>>
				<img src="imagenes/buscar-act.png" title="Ver" align="absmiddle" />
             </button><br>
        <font class="asterisco"><strong>Transportador:</strong></font> <?php echo $transportador ?><br>
        <font class="asterisco"><strong>Fecha:</strong></font> <?php echo $fecha_registrada ?><br>
        <font class="asterisco"><strong>Vehiculo:</strong></font> <?php echo $id_vehiculo ?><br>
        <font class="asterisco"><strong>Conductor:</strong></font> <?php echo $conductor ?><br>		
        <font class="asterisco"><strong>Deposito:</strong></font> <?php echo $deposito ?><br>		
        <input type="hidden" name="id_remesa" value="<?php echo $id_remesa ?>">
        <input type="hidden" name="cantidad_guias" value="<?php echo $i ?>">
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal">
        <p align="center" class="asterisco" >Descripcion del Despacho</p>
        <p align="center"><?php echo $impresion ?></p>
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal">
    	<p align="center" class="asterisco" >Remesa Escaneada</p>
    	<input name="scan" id="scan" type="file" size="15" <?php echo $estado_botones?> /></p><br>
    </td>
  </tr>
</table>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
        <button type="reset" name="reset" id="reset">
            <img src="imagenes/descargar-act.png" title="Limpiar" />
        </button>
        <button type="submit" name="guardar" id="guardar" <?php echo $estado_botones?> />
            <img src="imagenes/guardar-act.png" title="Guardar" />
        </button>
      </td>
    </tr>
 </table>    
</form>
</body>
</html>