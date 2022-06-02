<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
<!--
// funcion para validar
function validar()
{	
	if (document.forms[0].id.value=="")
	{
		alert("Atencion: Se requiere la CEDULA del conductor");
		document.forms[0].id.focus();
		return(false);
	}
	if (document.forms[0].nombre.value=="")
	{
		alert("Atencion: Se requiere el NOMBRE del conductor");
		document.forms[0].nombre.focus();
		return(false);
	}
}
//-->
//Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9\n]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 
</script>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Conductor</p>
<form method="post" name="conductor" onsubmit="return validar();" action="conductor_registro.php">
    <table align="center">
        <tr>
          <td class="celda_tabla_principal"><div class="letreros_tabla">Cedula</div></td>
          <td class="celda_tabla_principal celda_boton"><input name="id" type="text" id="id" tabindex="1" size="10" maxlength="15" onKeyPress="return numeric(event)"> <font color="#FF0000"><strong>(*)</strong></font></td>
          <script>document.forms[0].id.focus();</script>
        </tr>
        <tr>
          <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
          <td class="celda_tabla_principal celda_boton"><input name="nombre" type="text" tabindex="2" size="30" maxlength="50"></td>
        </tr>
        <tr>
          <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono 1</div></td>
          <td class="celda_tabla_principal celda_boton"><input name="telefono1" type="text" tabindex="3" size="10" maxlength="15"></td>
        </tr>
        <tr>
          <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono 2</div></td>
          <td class="celda_tabla_principal celda_boton"><input name="telefono2" type="text" tabindex="4" size="10" maxlength="15"></tr>
        <tr>
          <td class="celda_tabla_principal"><div class="letreros_tabla">Transportador</div></td>
          <td class="celda_tabla_principal celda_boton"><select name="id_transportador" id="id_transportador" tabindex="5">
            <?php
                    $sql="SELECT id,nombre FROM transportador WHERE estado='A' ORDER BY nombre ASC";
                    $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                    while($fila=mysql_fetch_array($consulta))
                    {
                        echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                    }
                ?>
          </select></td>
        </tr> 
    </table>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" name="cancelar" id="cancelar" onClick="document.location='consulta_identificar_parametricas.php?tabla=conductor'">
                <img src="imagenes/al_principio-act.png" title="Atras" />
            </button>
            <button type="reset" name="reset" id="reset">
                <img src="imagenes/descargar-act.png" title="Limpiar" />
            </button>
            <button type="submit" name="guardar" id="guardar">
                <img src="imagenes/guardar-act.png" title="Guardar" />
            </button>
          </td>
        </tr>
     </table>    
</form>
<p align="center"><br />
<?php 
if(isset($_POST["id"]))
	{
		$id=$_POST["id"];
		$nombre=strtoupper($_POST["nombre"]);
		$telefono1=strtoupper($_POST["telefono1"]);
		$telefono2=strtoupper($_POST["telefono2"]);
		$id_transportador=$_POST["id_transportador"];
		$sql="INSERT INTO conductor (id,nombre,telefono1,telefono2,id_transportador) value('$id','$nombre','$telefono1','$telefono2','$id_transportador')";
		mysql_query($sql,$conexion) or die ('<font size="4" color="red">ERROR: Ese Conductor ya existe</font><br />'.mysql_error());
		?>
        <script language="javascript">
			alert ("Registro Almacenado de Manera Exitosa");
		</script>
        <?php
	}
?>
</p>
</body>
</html>