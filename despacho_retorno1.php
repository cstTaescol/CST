<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$impresion="";
//Discriminacion de aerolinea de usuario TIPO 3
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND v.id_aerolinea = '$id_aerolinea_user'";	
//*************************************

if(isset($_POST['guia']))
{
	$guia=$_POST['guia'];
	if ($guia != "")
	{ //Si envian datos en blanco, evita que cargue la totalidad de las guias despachadas.
		$sql="SELECT v.nvuelo, v.nmanifiesto, g.* FROM vuelo v LEFT JOIN guia g ON v.id=g.id_vuelo WHERE (id_tipo_bloqueo='4' OR id_tipo_bloqueo='6' OR id_tipo_bloqueo='3' OR id_tipo_bloqueo='10') AND hija LIKE '%$guia%' $sql_aerolinea";
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		while($fila=mysql_fetch_array($consulta))
		{
			$id_guia=$fila["id"];
			$master=$fila["master"];
			$hija=$fila["hija"];
			$id_disposicion=$fila["id_disposicion"];
			$nvuelo=$fila["nvuelo"];
			$manifiesto=$fila["nmanifiesto"];
			include("config/evaluador_inconsistencias.php");
			switch ($id_disposicion)
			{
				case ($id_disposicion == 10 || $id_disposicion == 11 || $id_disposicion == 18 || $id_disposicion == 22): //despacho a depositos
					$sql2="SELECT id_remesa,id,piezas,peso FROM carga_remesa WHERE id_guia ='$id_guia'";
					$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
					while($fila2=mysql_fetch_array($consulta2))
					{
						$id_remesa=$fila2["id_remesa"];					
						//carga datos de la remesa
						$sql3="SELECT * FROM remesa WHERE id='$id_remesa' AND estado='A'";
						$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila3=mysql_fetch_array($consulta3);
						$fecha=$fila3['fecha'];
						$hora=$fila3['hora'];
						$piezasd=$fila2["piezas"];
						$pesod=$fila2["peso"];
						$id_registro=$fila2["id"];
						$id_vehiculo=$fila3['id_vehiculo'];
						$id_deposito=$fila3['id_deposito'];
						$id_transportador=$fila3['id_transportador'];
	
						//carga datos de deposito
						$sql3="SELECT nombre FROM deposito WHERE id='$id_deposito'";
						$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila3=mysql_fetch_array($consulta3);
						$deposito=$fila3['nombre'];
	
						//carga datos de trasnportador
						$sql3="SELECT nombre FROM transportador WHERE id='$id_transportador'";
						$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila3=mysql_fetch_array($consulta3);
						$transportador=$fila3['nombre'];
						$impresion=$impresion.
						'
						<table width="706" height="193" align="center" class="celda_tabla_principal">
						  <tr>
							<td colspan="2" align="center" valign="top" bgcolor="#003366"><font color="#FFFFFF"><strong>DATOS DEL DESPACHO POR TRANSPORTADORES</strong></font></td>
						  </tr>
						  <tr>
							<td width="285" align="left" valign="top" bgcolor="#003366">
								<font color="#FFFFFF"><strong>GUIA:</strong><br />'.$hija.'<br />
								<strong>CONSOLIDADO:</strong><br />'.$master.'<br />
								<strong>VUELO:</strong>'.$nvuelo.'<br />
								<strong>MANIFIESTO:</strong>'.$manifiesto.'<br />
								<hr />
								<strong>PIEZAS:</strong>'.$piezasd.'<br />
								<strong>PESO :</strong>'.$pesod.'<br />
								<hr />
								<button type="button" onClick="confirmacion('.$id_disposicion.','.$id_guia.','.$id_remesa.','.$id_registro.');">
									Eliminar Despacho <img src="imagenes/eliminar-act.png" title="eliminar" align="absmiddle" />
								</button>
								
								</font>
							</td>
							<td width="387" align="left" valign="top" bgcolor="#003366">
								<font color="#FFFFFF">
								 <strong>REMESA:</strong>'.$id_remesa.'<br />
								<strong>FECHA:</strong>'.$fecha.'<br />
								<strong>HORA:</strong>'.$hora.'<br />
								<strong>DEPOSITO:</strong><br />'.$deposito.'<br />
								<hr />
								<strong>TRANSPORTADOR:</strong><br />'.$transportador.'<br />
								<strong>VEHICULO:</strong>'.$id_vehiculo.'<br />
								</font>
							</td>
						  </tr>
						</table>
						<hr>
						';
					}
				break;
				
				case ($id_disposicion == 12 || $id_disposicion == 16 || $id_disposicion == 17 || $id_disposicion == 24): //despacho a cabotaje
					$sql2="SELECT * FROM cabotaje WHERE id_guia ='$id_guia' AND estado='A'";
					$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
					while($fila2=mysql_fetch_array($consulta2))
					{
						$fecha=$fila2['fecha'];
						$hora=$fila2['hora'];
						$id_registro=$fila2["id"];
						$destinatario=$fila2['destinatario'];
						$piezas=$fila2['piezas'];
						$peso=$fila2['peso'];
						$volumen=$fila2['volumen'];						
						$impresion=$impresion.
						'
						<table width="706" height="193" align="center" class="celda_tabla_principal">
						  <tr>
							<td colspan="2" align="center" valign="top" bgcolor="FF9933"><font color="#FFFFFF"><strong>DATOS DEL DESPACHO POR CABOTAJE</strong></font></td>
						  </tr>
						  <tr>
							<td width="285" align="left" valign="top" bgcolor="FF9933">
								<font color="#FFFFFF"><strong>GUIA:</strong><br />'.$hija.'<br />
								<strong>CONSOLIDADO:</strong><br />'.$master.'<br />
								<strong>VUELO:</strong>'.$nvuelo.'<br />
								<strong>MANIFIESTO:</strong>'.$manifiesto.'<br />
								<hr />
								<strong>PIEZAS:</strong>'.$piezas.'<br />
								<strong>PESO:</strong>'.$peso.'<br />
								<hr />
								<button type="button" onClick="confirmacion('.$id_disposicion.','.$id_guia.','.$id_registro.','.$id_registro.');"/>
									Eliminar Despacho <img src="imagenes/eliminar-act.png" title="eliminar" align="absmiddle" />
								</button>
								
								</font>
							</td>
							<td width="387" align="left" valign="top" bgcolor="FF9933">
								<font color="#FFFFFF">
								 <strong>COD. CABOTAJE:</strong>'.$id_registro.'<br />
								<strong>FECHA:</strong>'.$fecha.'<br />
								<strong>HORA:</strong>'.$hora.'<br />
								<hr />
								<strong>DESTINATARIO:</strong><br />'.$destinatario.'<br />
								</font>
							</td>
						  </tr>
						</table>
						<hr>
						';
					}
				break;
	
				case ($id_disposicion == 13 || $id_disposicion == 14 || $id_disposicion == 23): //despacho a trasbordo
					$sql2="SELECT * FROM trasbordo WHERE id_guia ='$id_guia' AND estado='A'";
					$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
					while($fila2=mysql_fetch_array($consulta2))
					{
						$fecha=$fila2['fecha'];
						$hora=$fila2['hora'];
						$id_registro=$fila2["id"];
						$destinatario=$fila2['destinatario'];
						$piezas=$fila2['piezas'];
						$peso=$fila2['peso'];
						$volumen=$fila2['volumen'];
						
						$impresion=$impresion.
						'
						<table width="706" height="193" align="center" class="celda_tabla_principal">
						  <tr>
							<td colspan="2" align="center" valign="top" bgcolor="9966FF"><font color="#FFFFFF"><strong>DATOS DEL DESPACHO POR TRASBORDO</strong></font></td>
						  </tr>
						  <tr>
							<td width="285" align="left" valign="top" bgcolor="9966FF">
								<font color="#FFFFFF"><strong>GUIA:</strong><br />'.$hija.'<br />
								<strong>CONSOLIDADO:</strong><br />'.$master.'<br />
								<strong>VUELO:</strong>'.$nvuelo.'<br />
								<strong>MANIFIESTO:</strong>'.$manifiesto.'<br />
								<hr />
								<strong>PIEZAS:</strong>'.$piezas.'<br />
								<strong>PESO:</strong>'.$peso.'<br />
								<hr />
								<button type="button" onClick="confirmacion('.$id_disposicion.','.$id_guia.','.$id_registro.','.$id_registro.');"/>
									Eliminar Despacho <img src="imagenes/eliminar-act.png" title="eliminar" align="absmiddle" />
								</button>
								
								</font>
							</td>
							<td width="387" align="left" valign="top" bgcolor="9966FF">
								<font color="#FFFFFF">
								 <strong>COD. TRASBORDO:</strong>'.$id_registro.'<br />
								<strong>FECHA:</strong>'.$fecha.'<br />
								<strong>HORA:</strong>'.$hora.'<br />
								<hr />
								<strong>DESTINATARIO:</strong><br />'.$destinatario.'<br />
								</font>
							</td>
						  </tr>
						</table>
						<hr>
						';
					}
				break;
	
				case ($id_disposicion == 15 || $id_disposicion == 25 || $id_disposicion == 29): //despacho a otros
					$sql2="SELECT id_otros,id,piezas,peso,volumen FROM carga_otros WHERE id_guia ='$id_guia'"; 
					$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
					while($fila2=mysql_fetch_array($consulta2))
					{
						$id_otros=$fila2["id_otros"];
						$id_registro=$fila2["id"];
						$piezas=$fila2["piezas"];	
						$peso=$fila2["peso"];	
						$volumen=$fila2["volumen"];	

						//carga datos de la remesa
						$sql3="SELECT * FROM otros WHERE id='$id_otros' AND estado='A'";
						$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila3=mysql_fetch_array($consulta3);
						$fecha=$fila3['fecha'];
						$hora=$fila3['hora'];
						$observaciones=$fila3["observaciones"];
						$impresion=$impresion.
						'
						<table width="706" height="193" align="center" class="celda_tabla_principal">
						  <tr>
							<td colspan="2" align="center" valign="top" bgcolor="#CC6699"><font color="#FFFFFF"><strong>DATOS DEL DESPACHO POR OTROS</strong></font></td>
						  </tr>
						  <tr>
							<td width="285" align="left" valign="top" bgcolor="#CC6699">
								<font color="#FFFFFF"><strong>GUIA:</strong><br />'.$hija.'<br />
								<strong>PIEZAS:</strong>'.$piezas.'<br />
								<strong>PESO:</strong>'.$peso.'<br />
								<strong>VOLUMEN:</strong>'.$volumen.'<br />
								<strong>CONSOLIDADO:</strong><br />'.$master.'<br />
								<strong>VUELO:</strong>'.$nvuelo.'<br />
								<strong>MANIFIESTO:</strong>'.$manifiesto.'<br />
								<hr />
								<button type="button" onClick="confirmacion('.$id_disposicion.','.$id_guia.','.$id_otros.','.$id_registro.');"/>
									Eliminar Despacho <img src="imagenes/eliminar-act.png" title="eliminar" align="absmiddle" />
								</button>
								
								</font>
							</td>
							<td width="387" align="left" valign="top" bgcolor="#CC6699">
								<font color="#FFFFFF">
								 <strong>COD DESPACHO OTROS:</strong>'.$id_otros.'<br />
								<strong>FECHA:</strong>'.$fecha.'<br />
								<strong>HORA:</strong>'.$hora.'<br />
								<hr>
								<strong>OBSERVACIONES:</strong><br />'.$observaciones.'<br />
								</font>
							</td>
						  </tr>
						</table>
						<hr>
						';
					}
				break;
	
				case ($id_disposicion == 19 || $id_disposicion == 20 || $id_disposicion == 21 || $id_disposicion == 28): //despacho a descargue directo
					$sql2="SELECT * FROM descargue_directo WHERE id_guia ='$id_guia' AND estado='A'";
					$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
					while($fila2=mysql_fetch_array($consulta2))
					{
						$fecha=$fila2['fecha'];
						$hora=$fila2['hora'];
						$id_registro=$fila2["id"];
						$declaracion=$fila2['declaracion'];
						$levante=$fila2['levante'];
						$placa=$fila2['placa'];
						$nombre_entregado=$fila2['nombre_entregado'];
						$telefono_entregado=$fila2['telefono_entregado'];
						$agencia=$fila2['agencia'];
						$piezas=$fila2['piezas'];
						$peso=$fila2['peso'];
						$volumen=$fila2['volumen'];						
						$impresion=$impresion.
						'
						<table width="706" height="193" align="center" class="celda_tabla_principal">
						  <tr>
							<td colspan="2" align="center" valign="top" bgcolor="999966"><font color="#FFFFFF"><strong>DATOS DEL DESPACHO POR DESCARGUE DIRECTO</strong></font></td>
						  </tr>
						  <tr>
							<td width="285" align="left" valign="top" bgcolor="999966">
								<font color="#FFFFFF"><strong>GUIA:</strong><br />'.$hija.'<br />
								<strong>CONSOLIDADO:</strong><br />'.$master.'<br />
								<strong>VUELO:</strong>'.$nvuelo.'<br />
								<strong>MANIFIESTO:</strong>'.$manifiesto.'<br />
								<hr />
								<strong>AGENCIA:</strong>'.$agencia.'<br />
								<strong>LEVANTE :</strong>'.$levante.'<br />
								<hr />
								<button type="button" onClick="confirmacion('.$id_disposicion.','.$id_guia.','.$id_registro.','.$id_registro.');"/>
									Eliminar Despacho <img src="imagenes/eliminar-act.png" title="eliminar" align="absmiddle" />
								</button>
								</font>
							</td>
							<td width="387" align="left" valign="top" bgcolor="999966">
								<font color="#FFFFFF">
								 <strong>COD. DESCARGUE DIRECTO:</strong>'.$id_registro.'<br />
								<strong>FECHA:</strong>'.$fecha.'<br />
								<strong>HORA:</strong>'.$hora.'<br />
								<hr />
								<strong>DECLARACION:</strong>'.$declaracion.'<br />
								<strong>ENTREGADO A:</strong>'.$nombre_entregado.'<br />
								<strong>TELEFONO:</strong>'.$telefono_entregado.'<br />
								</font>
							</td>
						  </tr>
						</table>
						<hr>
						';
					}
				break;
	
				case ($id_disposicion == 26 || $id_disposicion == 27): //despacho a correo
					$sql2="SELECT id_correo,piezas,peso,volumen,id FROM carga_correo WHERE id_guia ='$id_guia'";
					$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 7: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
					while($fila2=mysql_fetch_array($consulta2))
					{
						$piezas=$fila2["piezas"];	
						$peso=$fila2["peso"];	
						$volumen=$fila2["volumen"];	
						$id_correo=$fila2["id_correo"];	
						//carga datos de correo
						$sql3="SELECT * FROM correo WHERE id='$id_correo' AND estado='A'";
						$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila3=mysql_fetch_array($consulta3);
						$fecha=$fila3['fecha'];
						$hora=$fila3['hora'];
						$supervisor=$fila3['supervisor'];
						$jefe=$fila3['jefe'];
						$coordinador=$fila3['coordinador'];
						$id_registro=$fila2["id"];
						$id_aerolinea=$fila3['id_aerolinea'];
						
						//carga datos de vuelo
						$sql3="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
						$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
						$fila3=mysql_fetch_array($consulta3);
						$aerolinea=$fila3['nombre'];
						
						
						$impresion=$impresion.
						'
						<table width="706" height="193" align="center" class="celda_tabla_principal">
						  <tr>
							<td colspan="2" align="center" valign="top" bgcolor="green"><font color="#FFFFFF"><strong>DATOS DEL DESPACHO POR CORREO</strong></font></td>
						  </tr>
						  <tr>
							<td width="285" align="left" valign="top" bgcolor="green">
								<font color="#FFFFFF"><strong>GUIA:</strong><br />'.$hija.'<br />
								<strong>AEROLINEA</strong>'.$aerolinea.'<br />
								<hr />
								<strong>PIEZAS:</strong>'.$piezas.'<br />
								<strong>PESO:</strong>'.$peso.'<br />
								<hr />
								<input type="button" onClick="confirmacion('.$id_disposicion.','.$id_guia.','.$id_correo.','.$id_registro.');"/>
									Eliminar Despacho <img src="imagenes/eliminar-act.png" title="eliminar" align="absmiddle" />
								</button>								
								</font>
							</td>
							<td width="387" align="left" valign="top" bgcolor="green">
								<font color="#FFFFFF">
								 <strong>REP.CORREO:</strong>'.$id_correo.'<br />
								<strong>FECHA:</strong>'.$fecha.'<br />
								<strong>HORA:</strong>'.$hora.'<br />
								<strong>DESTINO:</strong><br />BODEGA DIAN<br />
								<hr />
								<strong>SUPERVISOR:</strong>'.$supervisor.'<br />
								<strong>COORDINADOR:</strong>'.$coordinador.'<br />
								<strong>JEFE:</strong>'.$jefe.'<br />
								</font>
							</td>
						  </tr>
						</table>
						<hr>
						';
					}
				break;
			}
		}		
	} 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<?php
require("menu.php");
//Privilegios Consultar Todo el Modulo
$id_objeto=77; 
include("config/provilegios_modulo.php");  
//---------------------------
?>
<p class="titulo_tab_principal">Retorno de Guias</p>
<form name="buscador" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
<table align="center">
    <tr>
        <td class="celda_tabla_principal">
            <p class="asterisco">Gu&iacute;a No.</p>
        </td>
        <td class="celda_tabla_principal">
			<input type="text"  name="guia" id="guia" tabindex="1" size="35" maxlength="20" />  
            <script>document.forms[0].guia.focus();</script>            
        </td>
        <td class="celda_tabla_principal">
			<button type="submit" tabindex="2">
            	<img src="imagenes/buscar-act.png" title="Buscar" align="absmiddle" />
            </button>
        </td>        
    </tr>
</table>
</form>
<?php echo $impresion?>
</body>
</html>
<script language="javascript">
	//Funcion para confirmar la eliminacion de una guia
	function confirmacion(disposicion,guia,despacho,registro)
	{
	var respuesta=confirm('ATENCION: Confirma que Desea Eliminar el Despacho?');
	if (respuesta)
		{
			window.location="despacho_retorno2.php?id_disposicion="+disposicion+"&id_guia="+guia+"&id_despacho="+despacho+"&id_registro="+registro;
		}
	}
</script>
