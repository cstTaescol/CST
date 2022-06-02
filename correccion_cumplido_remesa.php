<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$i=0;
$color='class="celda_tabla_principal" style="background:#CCC"';
$estado_botones='disabled="disabled"';
if (isset($_POST["nremesa"]))
{
	$id_remesa=$_POST["nremesa"];
	$sql="SELECT * FROM remesa WHERE id = '$id_remesa' AND estado='C'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
	{
		$estado_botones="";
		$color='class="celda_tabla_principal"';
		//Discriminacion de aerolinea de usuario TIPO 5						
		$sql2="SELECT id_aerolinea,c.* FROM guia g LEFT JOIN carga_remesa c ON g.id=c.id_guia WHERE c.id_remesa='$id_remesa'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
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
		$cumplido=$fila['foto_cumplido'];
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php require("menu.php"); ?>
<p class="titulo_tab_principal">Correccion Cumplido Remesa</p>
    <form method="post" name="consulta" id="consulta" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" >
        <table align="center">
            <tr>
                <td class="celda_tabla_principal">
                    <p class="asterisco">Remesa No.</p>
                </td>
                <td class="celda_tabla_principal">
                    <input type="text" name="nremesa" id="nremesa" />
         
                </td>
                <td class="celda_tabla_principal">
                    <button type="submit" tabindex="2">
                        <img src="imagenes/buscar-act.png" title="Buscar" align="absmiddle" />
                    </button>
                </td>        
            </tr>
        </table>
    </form>
    <script language="javascript">
        document.getElementById('nremesa').focus();
    </script>
</p>
<hr />
<p>
<form method="post" name="registro" id="registro" onsubmit="return validar();" action="correccion_cumplido_remesa_salvar.php"  enctype="multipart/form-data">
<table align="center" <?php echo $color ?>>
  <tr>
    <td align="center">
        <button type="button" onClick="abrir('pdf_remesa.php?id_remesa=<?php echo $id_remesa ?>')" style="height:70px; width:70px" <?php echo $estado_botones ?>>
        	<img src="imagenes/buscar-act.png" height="45" width="43"/>
        </button>
    </td>
    <td align="center">
        <button type="button" onClick="abrir('fotos/cumplidos/<?php echo $cumplido ?>')" style="height:70px; width:70px" <?php echo $estado_botones ?>>
        	<img src="imagenes/pdf.jpg" height="45" width="43"/>
        </button>
    </td>
    <td align="center">
    	Archivo reemplazante:<br />
        <input name="scan" id="scan" type="file" size="15" <?php echo $estado_botones ?>/>
    </td>
  </tr>
  <tr>
    <td align="center">Remesa</td>
    <td align="center">Cumplido Actual</td>
    <td align="center">Examinar</td>
  </tr>
  <tr>
      <td colspan="3" align="center">
        <button type="submit" name="guardar" id="guardar" <?php echo $estado_botones ?>> 
            <img src="imagenes/guardar-act.png"/><br />
            Guardar 
        </button>
        <button type="reset" name="reset" id="reset" <?php echo $estado_botones ?>> 
            <img src="imagenes/editar-act.png" /><br />
            Limpiar 
        </button>
        <button type="button" name="cancelar" id="cancelar" onclick="document.location='base.php'"> 
            <img src="imagenes/cancelar-act.png" /><br />
            Cancelar 
        </button>      
      </td>
  </tr>
</table>
<input type="hidden" name="id_remesa" value="<?php echo $id_remesa ?>" />
</form>
</p>
</body>
</html>
<script language="javascript">
function abrir(url)
{
	popupWin = window.open(url,'','directories, status, scrollbars, resizable, dependent, width=640, height=480, left=100, top=100')
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