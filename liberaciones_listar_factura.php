<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_guia=$_REQUEST['id_guia'];
$subtipo=$_REQUEST['subtipo'];
//Consulta del listado Facturas asociadas a la guia de Guias 
$sql3="SELECT * FROM guia_factura WHERE id_guia='$id_guia' AND estado='A'";
$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$impresion_factura='
	<table align="center">
		<tr>
			<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">No Factura</div>
			<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Valor</div>
			<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">IVA</div>
			<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Fecha Factura</div>
			<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Facturado A:</div>
			<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Modificar</div>
			<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Eliminar</div>
		</tr>
';
while ($fila3=mysql_fetch_array($consulta3))
{
	$nfactura=$fila3["nfactura"];
	$valor_factura=$fila3["valor_factura"];
	$iva=$fila3["iva"];
	$fecha_factura=$fila3["fecha_factura"];
	$facturadoa=$fila3["facturadoa"];
	$id_registro=$fila3["id"];
	
	$impresion_factura .='
		<tr>
			<td align="right" class="celda_tabla_principal celda_boton">'.$nfactura.'</td>
			<td align="right" class="celda_tabla_principal celda_boton">'.number_format($valor_factura,0,",",".").'</td>
			<td align="right" class="celda_tabla_principal celda_boton">'.number_format($iva,0,",",".").'</td>
			<td align="center" class="celda_tabla_principal celda_boton">'.$fecha_factura.'</td>
			<td align="left" class="celda_tabla_principal celda_boton">'.$facturadoa.'</td>
			<td align="center" class="celda_tabla_principal celda_boton">
				<button onClick="document.location=\'liberaciones_modificar_eliminar_factura.php?id='.$id_registro.'&metodo=actualizar&id_guia='.$id_guia.'&subtipo='.$subtipo.'\'">
					<img src="imagenes/editar-act.png" title="Modificar Factura">
				</button> 
			</td>
			<td align="center" class="celda_tabla_principal celda_boton">
				<button onClick="conf_eliminar('.$id_registro.','.$nfactura.')">
					<img src="imagenes/eliminar-act.png" title="Eliminar Factura">
				</button> 			
			</td>
		</tr>
	';
}
$impresion_factura .= '</table>';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script language="javascript">
        function abrir(url)
        {
            popupWin = window.open(url,'','directories, status, scrollbars, resizable, dependent, width=640, height=480, left=100, top=100')
            //  popupWin = window.open('pdf_remesa.php','nombre_ventana','menubar, toolbar, location, directories, status, scrollbars, resizable, dependent, width=640, height=480, left=0, top=0')
        }
        
        //Funcion para confirmar la eliminacion de una guia
        function conf_eliminar(id_registro,nfactura)
        {
        var respuesta=confirm('ATENCION: Confirma que ¿Desea Eliminar la Factura?');
        if (respuesta)
            {
                window.location="liberaciones_modificar_eliminar_factura.php?id="+id_registro+"&id_guia="+<?php echo $id_guia ?>+"&subtipo=<?php echo $subtipo ?>&metodo=eliminar&nfactura="+nfactura;
            }
        }
        </script>
    </head>
    <body>
        <?php require("menu.php"); ?>
        <p class="titulo_tab_principal">Listado de Facturas</p>
        <?php echo $impresion_factura; ?>
    </body>
</html>
