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
// funcion para validar
function validar()
{	
	if (document.forms[0].placa.value=="")
	{
		alert("Atencion: Se requiere la PLACA del Vehiculo");
		document.forms[0].placa.focus();
		return(false);
	}
}
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
<p class="titulo_tab_principal">Vehiculos</p>
<form method="post" name="vehiculo" onsubmit="return validar();" action="vehiculo_registro.php">
<table align="center">
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Placa</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="placa" type="text" id="placa" tabindex="1" size="6" maxlength="6"> <font color="#FF0000"><strong>(*)</strong></font></td>
      <script>document.forms[0].placa.focus();</script>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Marca</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="marca" type="text" tabindex="2" size="30" maxlength="30"></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Modelo</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="modelo" type="text" tabindex="3" size="4" maxlength="4" onKeyPress="return numeric(event)"></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Carroceria</div></td>
      <td class="celda_tabla_principal celda_boton">
      	<select name="carroceria" tabindex="4">
      		<option value="FURGON">FURGON</option>
            <option value="ESTACAS">ESTACAS</option>
            <option value="PLANCHON">PLANCHON</option>
        </select>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Transportador</div></td>
      <td class="celda_tabla_principal celda_boton">
      <select name="id_transportador" id="id_transportador" tabindex="5">
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
            <button type="button" name="cancelar" id="cancelar" onClick="document.location='consulta_identificar_parametricas.php?tabla=vehiculos'">
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
if(isset($_POST["placa"]))
	{
		$placa=strtoupper($_POST["placa"]);
		$marca=strtoupper($_POST["marca"]);
		$modelo=$_POST["modelo"];
		$carroceria=$_POST["carroceria"];
		$id_transportador=$_POST["id_transportador"];
		$sql="INSERT INTO vehiculo (placa,marca,modelo,carroceria,id_transportador) value('$placa','$marca','$modelo','$carroceria','$id_transportador')";
		mysql_query($sql,$conexion) or die ('<p align="center"><font size="4" color="red"><strong>ERROR:</strong>Esta placa de Veh&iacute;culo ya existe.</font></p>'.mysql_error());
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