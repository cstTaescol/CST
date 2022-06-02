<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

//Discriminacion de aerolinea de usuario TIPO 2
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND id_aerolinea = '$id_aerolinea_user'";	
//*************************************

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">
// 3. Evalua cuando la disposicion fue cabotaje y ahora trae una unava admin aduanera y un nuevo tipo de deposito para cargar el deposito basado ene stos parametros
function showDeposito(str_aduana,str_t_deposito)
 {
 if (str_aduana=="")
   {
   document.getElementById("dv_deposito").innerHTML="";
   return;
   } 
if (window.XMLHttpRequest)
   {// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
   }
 else
   {// code for IE6, IE5
   xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
 xmlhttp.onreadystatechange=function()
   {
   if (xmlhttp.readyState==4 && xmlhttp.status==200)
     {
     document.getElementById("dv_deposito").innerHTML=xmlhttp.responseText;
     }
   }
 xmlhttp.open("GET","ajax_reasignacion.php?aduana="+str_aduana+"&tipodeposito="+str_t_deposito,true);
 xmlhttp.send(); 
 //Limpia los datos de una
 document.forms[0].codigo.value="";
 }
//-------------------------------------------

//abre una pop up con la opcion de agregar un nuevo registro.
function openPopup(url,name,w,h,props,center){
	l=18;t=18
	if(center){l=(screen.availWidth-w)/2;t=(screen.availHeight-h)/2}
	url=url.replace(/[ ]/g,'%20')
	popup=window.open(url,name,'left='+l+',top='+t+',width='+w+',height='+h+',scrollbars=1'+((props)?','+props:''))
	props=props||''
	if(props.indexOf('fullscreen')!=-1){popup.moveTo(0,0);popup.resizeTo(screen.width,screen.height)}
	popup.focus()
}

//completar los valores seleccionados en un txt
function completar(valor)
{
	document.forms[0].codigo.value=valor;
}

//validacion
function validar()
{
	if (document.forms[0].codigo.value=="")
	{
		alert("Atencion: Debe seleccionar un DEPOSITO");
		document.forms[0].tipo_deposito.focus();
		return(false);
	}

	if (document.forms[0].cantidad.value=="0")
	{
		alert("Atencion: No Hay Guias para Re-Asignar");
		return(false);
	}
}
</script>
</head>

<body>
<?php
require("menu.php");
$id_objeto=62;
include("config/provilegios_modulo.php");
?>
<p class="titulo_tab_principal">Re-Asignacion de Guias</p>
<?php
$tablas="";
$sql="SELECT * FROM guia WHERE (id_tipo_bloqueo='3' OR id_tipo_bloqueo='6') AND (id_disposicion='19' OR id_disposicion='20' OR id_disposicion='21') AND faltante_total != 'S' $sql_aerolinea ORDER BY id_consignatario ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$cont=0;
while($fila=mysql_fetch_array($consulta))
{
	include("config/evaluador_inconsistencias.php");
	$cont++;
	$id_consignatario=$fila['id_consignatario'];
	//recuperando datos de la disposicion		
	$sql2="SELECT nombre FROM consignatario WHERE id='$id_consignatario'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$cliente=$fila2['nombre'];		
	$id_guia=$fila['id'];
	$master=$fila['master'];
	include("config/master.php");
	$tablas=$tablas.'<tr>
			<td bgcolor="#FFFFFF" align="center"><input type="checkbox" name="ck'.$cont.'" value="'.$id_guia.'"></td>
			<td bgcolor="#FFFFFF">'.$master.'</td>
			<td bgcolor="#FFFFFF"><a href="consulta_guia.php?id_guia='.$id_guia.'">'.$fila['hija'].'</a></td>
			<td bgcolor="#FFFFFF">'.$piezas.'</td>
			<td bgcolor="#FFFFFF">'.$peso.'</td>
			<td bgcolor="#FFFFFF">'.$cliente.'</td>
		</tr>';
}
?>
<form name="reasignacion" method="post" action="despacho_reasignacion2.php" onsubmit="return validar();">
    <p class="asterisco" align="center">Seleccione el Dep&oacute;sito al que <strong><u>Re-Asignar&aacute;</u></strong> las Gu&iacute;as </p>
    <table align="center">
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Tipo de Deposito</div></td>
        <td class="celda_tabla_principal celda_boton">
                <select name="tipo_deposito" id="tipo_deposito" tabindex="2" onchange="showDeposito(3,this.value)">
                  <option value="">Seleccione Uno</option>
                  <?php
                        $sql="SELECT id,nombre FROM tipo_deposito WHERE estado='A' ORDER BY nombre ASC";
                        $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                        while($fila=mysql_fetch_array($consulta))
                        {
                            echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                        }
                    ?>
                </select>
                <input type="hidden" id="codigo" name="id_deposito" />
                <input type="hidden" id="cantidad" name="cantidadguias" value="<?php echo $cont ?>" />            
        </td>
     </tr>
     <tr>
        <td colspan="2"><div id="dv_deposito"></div></td>
        </tr>
    </table>
    <table align="center">
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">...</div></td>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Consolidado</div></td>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Cliente</div></td>
      </tr>
      <?php echo $tablas ?>
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
            <button type="submit" name="guardar" id="guardar">
                <img src="imagenes/guardar-act.png" title="Guardar" />
            </button>
          </td>
        </tr>
     </table>    
</form>
</body>
</html>