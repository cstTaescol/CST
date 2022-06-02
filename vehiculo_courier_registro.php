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
$id_consignatario="";
if(isset($_REQUEST["id_consignatario"]))
  $id_consignatario=$_REQUEST["id_consignatario"];
?>
<p class="titulo_tab_principal">Vehiculos de Courier</p>
<form method="post" name="vehiculo" onsubmit="return validar();" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
  <table align="center">
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Placa</div></td>
        <td class="celda_tabla_principal celda_boton">
          <input name="placa" type="text" id="placa" tabindex="1" size="6" maxlength="6"> <font color="#FF0000"><strong>(*)</strong></font>
          <script>document.forms[0].placa.focus();</script>
        </td>          
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Consignatario</div></td>
        <td class="celda_tabla_principal celda_boton">
          <select name="id_consignatario" id="id_consignatario" tabindex="2">
            <?php
      				$sql="SELECT id,nombre FROM couriers WHERE estado='A' ORDER BY nombre ASC";
      				$consulta=mysql_query ($sql,$conexion) or die ("ERROR 01: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
      				while($fila=mysql_fetch_array($consulta))
      				{
                  if($id_consignatario == $fila['id'])
                    $seleccion='selected="selected"';
                  else
                    $seleccion='';              
                  echo '<option value="'.$fila['id'].'" '.$seleccion.'>'.substr($fila['nombre'], 0,30).'</option>';            					
      				}
    			 ?>
          </select>
      </td>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Acta</div></td>
        <td class="celda_tabla_principal celda_boton">
          <input name="no_acta" type="text" id="no_acta" tabindex="3" size="15" maxlength="20">
        </td>          
      </tr>      
    </tr> 
  </table>
  <table width="450" align="center">
      <tr>
        <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
      </tr>
      <tr>
        <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
          <button type="button" name="cancelar" id="cancelar" onClick="document.location='consulta_identificar_parametricas.php?tabla=vehiculos_courier'">
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
    $no_acta=strtoupper($_POST["no_acta"]);       
		$sql="INSERT INTO vehiculo_courier (placa,id_consignatario,no_acta) value('$placa','$id_consignatario','$no_acta')";
		mysql_query($sql,$conexion) or die ("ERROR 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		echo '
    <script language="javascript">
			alert ("Registro Almacenado de Manera Exitosa");
      document.location="consulta_identificar_parametricas.php?tabla=vehiculos_courier";
		</script>
    ';    
	}
?>
</p>
</body>
</html>