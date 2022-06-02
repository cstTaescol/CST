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

unset($_SESSION["id_vuelo"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
<body>
<?php
require("menu.php");
$id_objeto=37;
include("config/provilegios_modulo.php");
?>
<p class="titulo_tab_principal">Selector Aerolinea</p>
<form method="post" action="guia_registro.php" onSubmit="return validar();">
    <div align="center">
        <select name="aerolinea" tabindex="1" id="aerolinea">
		<?php
            $sql="SELECT id,nombre FROM aerolinea WHERE estado='A' AND importacion = TRUE $sql_aerolinea ORDER BY nombre ASC";
            $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
            while($fila=mysql_fetch_array($consulta))
            {
                echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
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
<p>Seleccione la <font color="#000099"><strong><em>Aerol&iacute;nea</em></strong></font> a la que corresponden las <font color="#000099"><em><strong>Gu&iacute;as</strong></em></font> que va a ingresar.</p>
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
