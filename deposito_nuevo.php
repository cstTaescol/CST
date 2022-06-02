<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$cod_deposito="";
if(isset($_GET["obligatorio"])) 
	{
		$obligatorio="1";
		$marca='<font color="#FF0000"><strong>(*)</strong></font>';
	}
	else
	{
		$obligatorio="";
		$marca="";
	}
?>
<html>
<head>
	<script language="javascript">
    // funcion para validar
    function validar()
    {
        if (document.forms[0].cod_dian.value=="")
        {
            alert("Atencion: Debe digitar el CODIGO DEL DEPOSITO autorizado por la DIAN");
            document.forms[0].cod_dian.focus();
            return(false);
        }
    
        if (document.forms[0].nombre.value=="")
        {
            alert("Atencion: Debe digitar un NOMBRE");
            document.forms[0].nombre.focus();
            return(false);
        }
        
        if (document.forms[0].direccion.value=="")
        {
            alert("Atencion: Debe digitar una DIRECCION");
            document.forms[0].direccion.focus();
            return(false);
        }
    }
    </script>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Deposito</p>
<form name="deposito" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" onSubmit="return validar();" method="post">
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Codigo Dian</div></td>
    <td class="celda_tabla_principal celda_boton" width="522">
    	<input name="cod_dian" id="cod_dian" type="text" size="4" maxlength="4" tabindex="1" />
        <font color="#FF0000"><strong> (*)</strong></strong></font>
        <script type="text/javascript">
			document.forms[0].cod_dian.focus();
		</script>
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
    <td class="celda_tabla_principal celda_boton"><input name="nombre" id="nombre" type="text" size="30" maxlength="200" tabindex="2" /><font color="#FF0000"><strong> (*)</strong></strong></font></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Direccion</div></td>
    <td class="celda_tabla_principal celda_boton"><input name="direccion" type="text" size="30" maxlength="200" tabindex="3"  /><font color="#FF0000"><strong> (*)</strong></strong></font></td>
  </tr> 
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono</div></td>
    <td class="celda_tabla_principal celda_boton"><input name="telefono1" type="text" size="15" maxlength="10" tabindex="4"/></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Aduana</div></td>
    <td class="celda_tabla_principal celda_boton">
        <select name="admon_aduana" id="admon_aduana" tabindex="5">
          <option value="3">ADUANAS DE BOGOT&Aacute;</option>
			<?php
                $sql="SELECT id,nombre FROM admon_aduana WHERE estado='A' ORDER BY nombre";
                $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                while($fila=mysql_fetch_array($consulta))
                {
                    echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                }
            ?>
        </select>
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Tipo</div></td>
    <td class="celda_tabla_principal celda_boton">
        <select name="tipo_deposito" id="tipo_deposito" tabindex="6">
		<?php
            $sql="SELECT id,nombre FROM tipo_deposito WHERE estado='A' ORDER BY nombre";
            $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
            while($fila=mysql_fetch_array($consulta))
            {
                echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
            }
        ?>
        </select>
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">FPU</div></td>
    <td class="celda_tabla_principal celda_boton">
    	Si<input type="radio" name="deposito_fpu" value="S" tabindex="7" />
      	No<input type="radio" name="deposito_fpu" value="N" checked tabindex="8" />
      <br />
      (Fuera del per&iacute;metro Urbano)
    </td>
  </tr>
</table>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" name="cancelar" id="cancelar" onClick="document.location='consulta_identificar_parametricas.php?tabla=deposito'">
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
<?php
if(isset($_POST["nombre"]))
{
	$nombre=strtoupper($_POST["nombre"]);
	$cod_deposito=$_POST["cod_dian"];
	if(isset($_POST["direccion"]))$direccion=strtoupper($_POST["direccion"]);
	if(isset($_POST["telefono1"]))$telefono1=strtoupper($_POST["telefono1"]);
	if(isset($_POST["admon_aduana"]))$admon_aduana=strtoupper($_POST["admon_aduana"]);
	if(isset($_POST["tipo_deposito"]))$tipo_deposito=strtoupper($_POST["tipo_deposito"]);
	if(isset($_POST["deposito_fpu"]))$deposito_fpu=strtoupper($_POST["deposito_fpu"]);
		
	$sql="INSERT INTO deposito (id,nombre,direccion,telefono,id_admon_aduana,id_tipo_deposito,fpu) value ('$cod_deposito','$nombre','$direccion','$telefono1','$admon_aduana','$tipo_deposito','$deposito_fpu')";
	mysql_query($sql,$conexion) or die (mysql_error().'<script language="javascript">
															alert("ERROR:El Codigo del Deposito YA EXISTE");
														</script>');
	
	echo '<script language="javascript">
				alert("Deposito Almacedado de Manera Exitosa");
			</script>
		<p align="center"><font color="green" size="4">Deposito Guardado</font></p>';
}
?>
</body>
</html>