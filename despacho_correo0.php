<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

//Discriminacion de aerolinea de usuario  TIPO 1
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND id = '$id_aerolinea_user'";	
//*************************************

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
<body>
<?php
require("menu.php");
$id_objeto=60;
include("config/provilegios_modulo.php");
?>
<p class="titulo_tab_principal">Selector Aerolinea</p>
<form method="post" action="despacho_correo1.php" onSubmit="return validar();">
    <div align="center">
        <select name="aerolinea" tabindex="1" id="aerolinea">
		<?php
			$sql="SELECT DISTINCT a.nombre,g.id_aerolinea FROM aerolinea a LEFT JOIN guia g ON a.id = g.id_aerolinea WHERE (g.id_disposicion='26' OR g.id_disposicion='27') AND (id_tipo_bloqueo='3' OR id_tipo_bloqueo='6'  OR id_tipo_bloqueo='10') $sql_aerolinea";
			$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			while($fila=mysql_fetch_array($consulta))
			{
				echo '<option value="'.$fila['id_aerolinea'].'">'.$fila['nombre'].'</option>';
			}
        ?>
        </select>
    
		<script type="text/javascript">
            document.forms[0].aerolinea.focus();
        </script>
       </div>
       <div align="center">
        <button type="button" name="cancelar" id="cancelar" onClick="document.location='base.php'">
            <img src="imagenes/al_principio-act.png" title="Atras" />
        </button>          
        <button type="submit" name="cancelar" id="cancelar">
            <img src="imagenes/al_final-act.png" title="Continuar" />
        </button>
    </div>  
 <hr />
</form>
<p>Seleccione la <font color="#000099"><strong><em>Aerol&iacute;nea</em></strong></font> a la que corresponden las <font color="#000099"><em><strong>Gu&iacute;as</strong></em></font> que va a despachar.</p>
</body>
</html>
<script language="javascript">
	// funcion para validar
	function validar()
	{
		if (document.forms[0].aerolinea.value=="")
		{
			alert("Atencion: Debe seleccinar una AEROLINEA");
			document.forms[0].aerolinea.focus();
			return(false);
		}
	}
</script>
</html>