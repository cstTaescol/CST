<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_vuelo=$_REQUEST['id_vuelo'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />
    <title>GUIA SOBRANTE NUEVA</title>
    <script language="javascript">
    //Validacion de campos numéricos
    function numeric(e) { // 1
        tecla = (document.all) ? e.keyCode : e.which; // 2
        if (tecla==8) return true; // 3
        patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
        te = String.fromCharCode(tecla); // 5
        return patron.test(te); // 6
    } 
    
    function numeric2(e) { // 1
        tecla = (document.all) ? e.keyCode : e.which; // 2
        if (tecla==8) return true; // 3
        patron =/[0-9-.\s]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
        te = String.fromCharCode(tecla); // 5
        return patron.test(te); // 6
    } 
    // funcion para validar
    function validar()
    {
        if (document.forms[0].guia.value == "")
            {
                alert("Atencion: Debe digitar el No. GUIA Sobrante");
                document.forms[0].guia.focus();
                return(false);
            }
        if (document.forms[0].piezas.value == "")
            {
                alert("Atencion: Debe digitar el No. DE PIEZAS");
                document.forms[0].piezas.focus();
                return(false);
            }
        if (document.forms[0].piezas.value == 0)
            {
                alert("Atencion: Debe digitar el No. DE PIEZAS");
                document.forms[0].piezas.focus();
                return(false);
            }
        if (document.forms[0].peso.value == "")
            {
                alert("Atencion: Debe digitar el PESO");
                document.forms[0].peso.focus();
                return(false);
            }
        if (document.forms[0].peso.value == 0)
            {
                alert("Atencion: Debe digitar el PESO");
                document.forms[0].peso.focus();
                return(false);
            }
    }
    </script>
</head>
<body>
<p class="titulo_tab_principal">Guia Sobrante Nueva</p>
<hr />
<form action="guia_sobrante_nueva.php" method="post" onsubmit="return validar();">
<table width="90%" align="center">
  <tr>
    <td width="60%" align="center" class="celda_tabla_principal"><div class="letreros_tabla asterisco">No. Guia</div></td>
    <td width="10%" class="celda_tabla_principal celda_boton">
    	<input name="guia" type="text" id="guia" size="20" maxlength="20" tabindex="1"/>
        <script language="javascript">
			document.getElementById("guia").focus();
         </script>
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Piezas</div></td>
    <td class="celda_tabla_principal celda_boton"><input name="piezas" id="piezas" type="text" size="5" maxlength="10" onkeypress="return numeric(event)" onfocus="activar1('s1');" tabindex="2"/></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Peso</div></td>
    <td class="celda_tabla_principal celda_boton"><input name="peso" id="peso" type="text" size="5" maxlength="10" onkeypress="return numeric2(event)" tabindex="3"></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Volumen</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<input name="volumen" id="volumen" type="text" size="5" maxlength="10" onkeypress="return numeric2(event)" tabindex="4"/>
        <input name="id_vuelo" id="id_vuelo" type="hidden" value="<?php echo $id_vuelo ?>"/>
    </td>
  </tr>
</table>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
        <button type="reset" name="reset" id="reset" tabindex="6">
            <img src="imagenes/descargar-act.png" title="Limpiar" />
        </button>
        <button type="submit" name="guardar" id="guardar" tabindex="5">
            <img src="imagenes/guardar-act.png" title="Guardar" />
        </button>
      </td>
    </tr>
 </table>    
</form>
<hr />
<em>Ingrese los datos de la gu&iacute;a sobrante y guarde el registro.</em>
</body>
</html>
<?php
if(isset($_POST['guia']))
{
	$guia=strtoupper($_POST['guia']); 
	$piezas=$_POST['piezas'];
	$peso=$_POST['peso'];
	$volumen=$_POST['volumen'];
	if ($volumen=="" || $volumen==0)
		$volumen=$peso;
	$id_vuelo=$_POST['id_vuelo'];
	
	$sql="INSERT INTO despaletizaje_sobantes (id_vuelo,
											  guia,
											  piezas,
											  peso,
											  volumen) 
												VALUE ('$id_vuelo',
													   '$guia',
													   '$piezas',
													   '$peso',
													   '$volumen')";
	mysql_query($sql,$conexion) or die ("Error 1 ". mysql_error());
	echo '<script language="javascript">
			alert ("Datos almacenados de manera Exitosa");
			window.opener.location.reload();
			self.close();
		</script>';
}
?>