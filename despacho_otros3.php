<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_otros=$_REQUEST["id"];
$cont=0;
$impresion="";
//carga datos de la remesa
$sql="SELECT * FROM otros WHERE id='$id_otros' AND estado='A'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 1". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);

$observaciones=$fila["observaciones"];
$hora=$fila["hora"];
$fecha=$fila["fecha"];
$usuario=$fila["id_usuario"];


//identificando usuario
$sql="SELECT nombre FROM usuario WHERE id = '$usuario'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$usuario=$fila["nombre"];

//carga de guias despachadas con la salida
$sql="SELECT * FROM carga_otros WHERE id_otros='$id_otros'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila=mysql_fetch_array($consulta))
	{
		$cont++;
		$id_guia=$fila["id_guia"];
		//carga dato de la Guia
		$sql2="SELECT * FROM guia WHERE id='$id_guia'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$guia=$fila2['hija'];
		$id_consignatario=$fila2['id_consignatario'];
		$piezas=$fila['piezas'];
		$peso=$fila['peso'];
		
		//identificando consignatario
		$sql3="SELECT nombre FROM consignatario WHERE id = '$id_consignatario'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$consignatario=$fila3["nombre"];
		$impresion=$impresion.
					'<tr>
						<td class="celda_tabla_principal celda_boton">'.$cont.'</td>
						<td class="celda_tabla_principal celda_boton">'.$guia.'</td>
						<td class="celda_tabla_principal celda_boton">'.$piezas.'</td>
						<td class="celda_tabla_principal celda_boton">'.$peso.'</td>
						<td class="celda_tabla_principal celda_boton">'.$consignatario.'</td>
					  </tr>
					  ';
	}
?>
<html>
<head>
<script language="javascript">
//Funcion para confirmar la eliminacion de un despacho
function conf_eliminar(url)
{
var respuesta=confirm('ATENCION: Confirma que Desea Eliminar el Despacho?,  Las GUIAS despachadas seran Retornadas al INVENTARIO');
if (respuesta)
	{
		window.location="eliminar_otros1.php?id="+url;
	}
}
</script>
</head>
<body>
<?php
require("menu.php");
?>

<p class="titulo_tab_principal">Despacho de Otras Guias</p>
    <table align="center">
      <tr>
        <td align="center" valign="middle" class="celda_tabla_principal"><font color="#FF0000" size="+2"><strong><?php echo $id_otros ?></strong></font></td>
        <td class="celda_tabla_principal"><strong>DILIGENDIADO POR:</strong> <?php echo $usuario ?></td>
      </tr>
      <tr>
      	<td colspan="2" class="celda_tabla_principal">
        	<div class="letreros_tabla">Observaciones:</div>
            <p><?php echo $observaciones ?></p>
        </td>
      </tr>
    </table>
    <table align="center">
      <tr>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">No.</div></td>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Consignatario</div></td>
      </tr>
      <?php echo $impresion ?>
    </table>
<?php 
//Privilegios modificar
$id_objeto=90; 
include("config/provilegios_objeto.php");  
$activacion1=$activacion;

//Privilegios Eliminar
$id_objeto=91; 
include("config/provilegios_objeto.php");  
$activacion2=$activacion;
?>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button name="terminar" type="button" onClick="document.location='base.php'">
                <img src="imagenes/aceptar-act.png" title="Terminar" /><br />
              <strong>Terminar</strong>
            </button>
            
            <button name="modificar" type="button" <?php echo $activacion1 ?> onClick="document.location='modificar_otros1.php?id=<?php echo $id_otros ?>'">
                <img src="imagenes/settings-act.png" title="Modificar" /><br />
              <strong>Modificar</strong>
            </button>
            
            <button name="eliminar" type="button" <?php echo $activacion2 ?> onClick="conf_eliminar(<?php echo $id_otros ?>);">
                <img src="imagenes/eliminar-act.png" title="Eliminar" /><br />
              <strong>Eliminar</strong>
            </button>
            
            <button name="imprimir" type="button" onClick="window.print();">
                <img src="imagenes/imprimir-act.png" title="Imprimir" /><br />
              <strong>Imprimir</strong>
            </button>
      </td>
    </tr>
</table>    
</body>
</html>