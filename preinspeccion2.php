<?php session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_usuario=$_SESSION['id_usuario'];
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");

if(isset($_REQUEST["id_guia"]))
{
	$id_guia=$_REQUEST['id_guia'];
	$nombre=strtoupper($_POST["nombre"]);
	$documento=$_POST["documento"];
	$telefono=$_POST["telefono"];
	$agencia=strtoupper($_POST["agencia"]);

	//consulta el nombre de la imagen para saber si actualiza o no la foto
	if (is_uploaded_file ($_FILES['foto']['tmp_name']))
	{
		$nombreDirectorio = "fotos/cumplidos/";
		$idUnico = time();
		$nombrefoto = $idUnico . "-" . $_FILES['foto']['name'];
		
		//SUBE LA IMAGEN LUEGO DE CREAR LOS DATOS
		move_uploaded_file ($_FILES['foto']['tmp_name'],$nombreDirectorio . $nombrefoto);
	}
	else
		$nombrefoto="";

	//1. almacenamiento de los datos en la tabla de guia
	$sql="INSERT INTO preinspeccion (id_guia,
							nombre,
							documento,
							telefono,
							agencia,
							foto,
							id_usuario,
							fecha,
							hora)	
							VALUE 
							('$id_guia',
							'$nombre',
							'$documento',
							'$telefono',
							'$agencia',
							'$nombrefoto',
							'$id_usuario',
							'$fecha',
							'$hora')";
	mysql_query($sql,$conexion) or die (mysql_error());
	$id_registro = mysql_insert_id($conexion); //obtiene el id de la ultima inserción
	
	//2. almacenamiento del traking
	$sql_trak="INSERT INTO tracking (id_guia,
									 fecha_creacion,
									 hora,
									 evento,
									 tipo_tracking,
									 id_usuario) 
										VALUE ('$id_guia',
											   '$fecha',
											   '$hora',
											   'AUTORIZACION No. $id_registro PARA PRE-INSPECCION',
											   '1',
											   '$id_usuario')";
	mysql_query($sql_trak,$conexion) or die (mysql_error());
	
	$nombrefoto="imagen_no_disponible.jpg";

	//3. Aviso de Guardado Exitoso
	echo '
	<script>
		alert("Registro almacenado de manera Exitosa");
	</script>';	
	
}
else
{
	echo"
	<script>
		alert('Error Al Obtener Los Datos, Informe al Soporte Tecnico');
		document.location='base.php';
	</script>
	";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function abrir(url)
{
	popupWin = window.open(url,'','directories, status, scrollbars, resizable, dependent, width=640, height=480, left=100, top=100')
	//  popupWin = window.open('pdf_remesa.php','nombre_ventana','menubar, toolbar, location, directories, status, scrollbars, resizable, dependent, width=640, height=480, left=0, top=0')
}

//Funcion para confirmar la eliminacion
function conf_eliminar(url)
{
var respuesta=confirm('ATENCION: Confirma que desea ANULAR el registro?');
if (respuesta)
	{
		window.location="preinspeccion_anular.php?id_registro="+url;
	}
}

</script>
</head>
<body>
<?php include("menu.php");?>
<p class="titulo_tab_principal">Pre-Inspecci&oacute;n</p>
<table align="center">
  <tr>
    <td width="200" rowspan="5" align="center" valign="middle" class="celda_tabla_principal celda_boton">
        <font color="#0099FF" size="-1"><em> foto del registro</em></font><br />
      <img src="fotos/cumplidos/<?php echo $nombrefoto ?>" width="136" height="141" /></td>
        <td colspan="2" class="celda_tabla_principal">No.</td>
        <td class="celda_tabla_principal celda_boton"><?php echo $id_registro; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Documento</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Tel&eacute;fono</div></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal celda_boton"><?php echo $nombre ?></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $documento ?></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $telefono ?></td>
  </tr>
  <tr>
    <td colspan="3" class="celda_tabla_principal"><div class="letreros_tabla">Agencia</div></td>
  </tr>
  <tr>
    <td height="120" colspan="3" class="celda_tabla_principal celda_boton"><?php echo $agencia ?></td>
  </tr>
</table>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
  </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
      	<button type="button" tabindex="9" onclick="document.location='base.php'">
       	<img src="imagenes/cancelar-act.png" title="Salir" />
        </button>
        <button type="button" tabindex="8" onClick="conf_eliminar(<?php echo $id_registro ?>);">
        	<img src="imagenes/eliminar-act.png" title="Eliminar" />
        </button>
      	<button type="button" tabindex="7" onClick="abrir('pdf_preinspeccion.php?id_registro=<?php echo $id_registro ?>')">
        	<img src="imagenes/imprimir-act.png" title="Imprimir" />
        </button>
      </td>
    </tr>
</table>

</body>
</html>
