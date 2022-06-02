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
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Personal de Courier</p>
<form method="post" name="vehiculo" onsubmit="return validar();" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
  <table align="center">
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">No. Documento</div></td>
        <td class="celda_tabla_principal celda_boton">
          <input name="no_documento" type="text" id="no_documento" tabindex="1" size="20" maxlength="10" onkeypress="return numeric(event)"> 
          <font color="#FF0000"><strong>(*)</strong></font>
          <script>document.forms[0].no_documento.focus();</script>
        </td>          
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
        <td class="celda_tabla_principal celda_boton">
          <input name="nombre" type="text" id="nombre" tabindex="2" size="20" maxlength="50">
          <font color="#FF0000"><strong>(*)</strong>
        </td>          
      </tr>            
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Courier</div></td>
        <td class="celda_tabla_principal celda_boton">
          <select name="id_courier" id="id_courier" tabindex="3">
            <?php
      				$sql="SELECT id,nombre FROM couriers WHERE estado='A' ORDER BY nombre ASC";
      				$consulta=mysql_query ($sql,$conexion) or die ("ERROR 01: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
      				while($fila=mysql_fetch_array($consulta))
      				{           
                  echo '<option value="'.$fila['id'].'">'.substr($fila['nombre'], 0,30).'</option>';            					
      				}
    			 ?>
          </select>
      </td>
    </tr> 
  </table>
  <table width="450" align="center">
      <tr>
        <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
      </tr>
      <tr>
        <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
          <button type="button" name="cancelar" id="cancelar" onClick="document.location='consulta_identificar_parametricas.php?tabla=courier_funcionario'">
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
if(isset($_POST["no_documento"]))
	{
		$no_documento=$_POST["no_documento"];		
    $nombre=strtoupper($_POST["nombre"]);
    $id_courier=$_POST["id_courier"];  
         
		$sql="INSERT INTO courier_funcionario (nombre,no_documento,id_entidad,id_consignatario) value('$nombre','$no_documento','7','$id_courier')";
		mysql_query($sql,$conexion) or die ("ERROR 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		echo '
    <script language="javascript">
			alert ("Registro Almacenado de Manera Exitosa");
      document.location="consulta_identificar_parametricas.php?tabla=courier_funcionario";
		</script>
    ';    
	}
?>
</p>
</body>
</html>
<script language="javascript">
// funcion para validar
function validar()
{ 
  if (document.forms[0].no_documento.value=="")
  {
    alert("Atencion: Se requiere el NUMERO DE DOCUMENTO");
    document.forms[0].no_documento.focus();
    return(false);
  }
  if (document.forms[0].nombre.value=="")
  {
    alert("Atencion: Se requiere el NOMBRE");
    document.forms[0].nombre.focus();
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