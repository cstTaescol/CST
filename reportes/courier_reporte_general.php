<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("../config/configuracion.php");
set_time_limit(0); // Quita el limitante de tiempo para realizar una consulta grande
$id_usuario=$_SESSION['id_usuario'];
//Usuario
$sql3="SELECT nombre FROM usuario WHERE id='$id_usuario'";
$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila3=mysql_fetch_array($consulta3);
$usuario=$fila3["nombre"];
//****************************************
/*
	- Se recopilan las opcines seleccionadas
	- Se construllen los titulos
	- Se adicionan las consulta de cada seleccion
	- Se construlle el archivo CSV
*/	

$ano=date("Y");
$mes=date("m");
$dia=date("d");
$hora_registro=date("H:i:s");
$titulos="No.;FECHA CREACION;HORA CREACION;AEROLINEA";
$titulosTabla="<th>No.</th>
				<th>FECHA CREACION</th>
				<th>HORA CREACION</th>
				<th>AEROLINEA</th>";
$cuerpoTabla="";
$rangoini=$_POST['rangoini'];
$rangofin=$_POST['rangofin'];
//Cheks Seleccionados

//no guia
if (isset($_POST['nguia']))
	{
		$cknguia=$_POST['nguia'];
		$titulos=$titulos.";GUIA";
		$titulosTabla=$titulosTabla."<th>GUIA</th>";
	}
else
	{
		$cknguia="";
	}

//cantidad_hijas
if (isset($_POST['cantidad_hijas']))
	{
		$ckcantidad_hijas=$_POST['cantidad_hijas'];
		$titulos=$titulos.";CANT. APREHENSIONES";
		$titulosTabla=$titulosTabla."<th>CANT. APREHENSIONES</th>";
	}
else
	{
		$ckcantidad_hijas="";
	}	

//1168
if (isset($_POST['p1168']))
	{
		$ckp1168=$_POST['p1168'];
		$titulos=$titulos.";1168";
		$titulosTabla=$titulosTabla."<th>1168</th>";
	}
else
	{
		$ckp1168="";
	}

//courier
if (isset($_POST['courier']))
	{
		$ckcourier=$_POST['courier'];
		$titulos=$titulos.";COURIER";
		$titulosTabla=$titulosTabla."<th>COURIER</th>";

	}
else
	{
		$ckcourier="";
	}

//piezas
if (isset($_POST['piezas']))
	{
		$ckpiezas=$_POST['piezas'];
		$titulos=$titulos.";PIEZAS";
		$titulosTabla=$titulosTabla."<th>PIEZAS</th>";
	}
else
	{
		$ckpiezas="";
	}

//peso
if (isset($_POST['peso']))
	{
		$ckpeso=$_POST['peso'];
		$titulos=$titulos.";PESO";
		$titulosTabla=$titulosTabla."<th>PESO</th>";
	}
else
	{
		$ckpeso="";
	}

//linea
if (isset($_POST['linea']))
	{
		$cklinea=$_POST['linea'];
		$titulos=$titulos.";LINEA";
		$titulosTabla=$titulosTabla."<th>LINEA</th>";
	}
else
	{
		$cklinea="";
	}

//vehiculos
if (isset($_POST['vehiculos']))
	{
		$ckvehiculos=$_POST['vehiculos'];
		$titulos=$titulos.";VEHICULOS";
		$titulosTabla=$titulosTabla."<th>VEHICULOS</th>";
	}
else
	{
		$ckvehiculos="";
	}

//conductores
if (isset($_POST['conductores']))
	{
		$ckconductores=$_POST['conductores'];
		$titulos=$titulos.";CONDUCTORES";
		//$titulosTabla=$titulosTabla."<th>CONDUCTORES</th>";
	}
else
	{
		$ckconductores="";
	}

//personalDian
if (isset($_POST['personalDian']))
	{
		$ckpersonalDian=$_POST['personalDian'];
		$titulos=$titulos.";PERSONAL DIAN";
		//$titulosTabla=$titulosTabla."<th>PERSONAL DIAN</th>";
	}
else
	{
		$ckpersonalDian="";
	}

//personalTaescol
if (isset($_POST['personalTaescol']))
	{
		$ckpersonalTaescol=$_POST['personalTaescol'];
		$titulos=$titulos.";PERSONAL TAESCOL";
		//$titulosTabla=$titulosTabla."<th>PERSONAL TAESCOL</th>";
	}
else
	{
		$ckpersonalTaescol="";
	}

//personalPolfa
if (isset($_POST['personalPolfa']))
	{
		$ckpersonalPolfa=$_POST['personalPolfa'];
		$titulos=$titulos.";PERSONAL POLFA";
		//$titulosTabla=$titulosTabla."<th>PERSONAL POLFA</th>";
	}
else
	{
		$ckpersonalPolfa="";
	}

//personalInvima
if (isset($_POST['personalInvima']))
	{
		$ckpersonalInvima=$_POST['personalInvima'];
		$titulos=$titulos.";PERSONAL INVIMA";
		//$titulosTabla=$titulosTabla."<th>PERSONAL INVIMA</th>";
	}
else
	{
		$ckpersonalInvima="";
	}

//personalIca
if (isset($_POST['personalIca']))
	{
		$ckpersonalIca=$_POST['personalIca'];
		$titulos=$titulos.";PERSONAL ICA";
		//$titulosTabla=$titulosTabla."<th>PERSONAL ICA</th>";
	}
else
	{
		$ckpersonalIca="";
	}

//personalOtros
if (isset($_POST['personalOtros']))
	{
		$ckpersonalOtros=$_POST['personalOtros'];
		$titulos=$titulos.";PERSONAL OTROS";
		//$titulosTabla=$titulosTabla."<th>PERSONAL OTRO</th>";
	}
else
	{
		$ckpersonalOtros="";
	}

//personalCourier
if (isset($_POST['personalCourier']))
	{
		$ckpersonalCourier=$_POST['personalCourier'];
		$titulos=$titulos.";PERSONAL COURIER";
		//$titulosTabla=$titulosTabla."<th>PERSONAL COURIER</th>";
	}
else
	{
		$ckpersonalCourier="";
	}

//datos_llegada
if (isset($_POST['datos_llegada']))
	{
		$ckdatos_llegada=$_POST['datos_llegada'];
		$titulos=$titulos.";FECHA LLEGADA";
		//$titulosTabla=$titulosTabla."<th>FECHA LLEGADA</th>";
	}
else
	{
		$ckdatos_llegada="";
	}

//datos_inicio
if (isset($_POST['datos_inicio']))
	{
		$ckdatos_inicio=$_POST['datos_inicio'];
		$titulos=$titulos.";FECHA INICIO REVISION";
		//$titulosTabla=$titulosTabla."<th>FECHA INICIO REVISION</th>";
	}
else
	{
		$ckdatos_inicio="";
	}

//datos_fin
if (isset($_POST['datos_fin']))
	{
		$ckdatos_fin=$_POST['datos_llegada'];
		$titulos=$titulos.";FECHA FIN REVISION";
		//$titulosTabla=$titulosTabla."<th>FECHA FIN REVISION</th>";
	}
else
	{
		$ckdatos_fin="";
	}

//datos_despacho
if (isset($_POST['datos_despacho']))
	{
		$ckdatos_despacho_fin=$_POST['datos_despacho'];
		$titulos=$titulos.";FECHA DOC ENTREGA";
		//$titulosTabla=$titulosTabla."<th>FECHA DOC ENTREGA</th>";
	}
else
	{
		$ckdatos_despacho_fin="";
	}

//no_despacho
if (isset($_POST['no_despacho']))
	{
		$ckno_despacho=$_POST['no_despacho'];
		$titulos=$titulos.";No. DOC. ENTREGA";
		$titulosTabla=$titulosTabla."<th>No. DOC. ENTREGA</th>";
	}
else
	{
		$ckno_despacho="";
	}	

//factura
if (isset($_POST['facturacion']))
	{
		$ckfacturacion=$_POST['facturacion'];
		$titulos=$titulos.";No. FACTURA;VALOR;IVA;FACTURADO A;FECHA FACTURA";
	}
else
	{
		$ckfacturacion="";
	}	

//Consulta de guias en el rango de fecha solicitado
$sql="SELECT * FROM guia WHERE fecha_creacion BETWEEN '$rangoini' AND '$rangofin' AND id_tipo_guia='5' ORDER BY fecha_creacion DESC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 01 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);
if ($nfilas > 0)
{
	//Creacion de Archivo
	$cols=4;
	$nombre_archivo="csv/".time()."-".$ano."-".$mes."-".$dia."_reporte_general_courier.csv";
	$CL=fopen("$nombre_archivo","a") or die("Problemas en la creacion del archivo de Plano, consulete con el soporte tecnico" . exit());
	fputs($CL,"$titulos;\n");
	$metadafaFacturacion="";
	for ($i=1; $i<=$nfilas; $i++)
	{
		$cuerpoTabla .= "<tr>"; // Fila para la tabla
		//Consulta Genenal de la Guia
		$fila=mysql_fetch_array($consulta);
		$id_guia=$fila["id"];
		$fecha_creacion=$fila["fecha_creacion"];
		$hora_creacion=$fila["hora"];	
		
		//carga dato adicionales
		$aerolinea=$fila["id_aerolinea"];
		$sql_add="SELECT nombre FROM aerolinea WHERE id='$aerolinea'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 02". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$aerolinea=$fila_add["nombre"];
		//************************

		//Numero guia
		if ($cknguia==1)
			{	
				$cknguia_impresion=";".$fila["master"];				
				$cols++;				
			}
		else
			{
				$cknguia_impresion="";
			}

		//Cantidad de Hijas
		if ($ckcantidad_hijas==1)
			{	
				$personal="";
				$sql_add="SELECT id FROM guia WHERE master='$id_guia' AND id_tipo_guia='6'";
				$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 14". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$nfilas_add=mysql_num_rows($consulta_add);
				$ckcantidad_hijas_impresion=";".$nfilas_add;	
				$cols++;			
			}
		else
			{
				$ckcantidad_hijas_impresion="";
			}

		//ckp1168
		if ($ckp1168=="1")
			{	
				$ckp1168_impresion=";'".$fila["courier_1178"];;
				$cols++;
			}
		else
			{
				$ckp1168_impresion="";
			}

		//courier
		if ($ckcourier==1)
			{	
				$courier=$fila["id_consignatario"];
				$sql_add="SELECT nombre FROM couriers WHERE id='$courier'";
				$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 03". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$fila_add=mysql_fetch_array($consulta_add);
				$ckcourier_impresion=";".$fila_add["nombre"];
				$cols++;
			}
		else
			{
				$ckcourier_impresion="";
			}

		//Piezas
		if ($ckpiezas==1)
			{	
				$piezas_impresion=";".$fila["piezas"];
				$cols++;
			}
		else
			{
				$piezas_impresion="";
			}
		
		//Peso	
		if ($ckpeso==1)
			{	
				$peso_impresion=";".number_format($fila["peso"],2,",",".");
				$cols++;
			}
		else
			{
				$peso_impresion="";
			}

		//Linea
		if ($cklinea==1)
			{	
				$linea=$fila["courier_id_linea"];
				$sql_add="SELECT nombre FROM courier_linea WHERE id='$linea'";
				$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 04". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$fila_add=mysql_fetch_array($consulta_add);
				$cklinea_impresion=";".$fila_add["nombre"];
				$cols++;
			}
		else
			{
				$cklinea_impresion="";
			}

		//Vehiculos
		if ($ckvehiculos==1)
			{	
				$vehiculos="";
				$sql_add="SELECT v.placa, t.id_placa FROM courier_transportador t LEFT JOIN vehiculo_courier v ON t.id_placa = v.id WHERE t.id_guia='$id_guia'";
				$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 05". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila_add=mysql_fetch_array($consulta_add))
				{
					$vehiculos .= ' -'.$fila_add["placa"];
				}
				$ckvehiculos_impresion=";".$vehiculos;
				$cols++;
			}
		else
			{
				$ckvehiculos_impresion="";
			}

		//Conductores
		if ($ckconductores==1)
			{	
				$conductores="";
				$sql_add="SELECT nombre FROM courier_transportador WHERE id_guia='$id_guia'";
				$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 06". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila_add=mysql_fetch_array($consulta_add))
				{
					$conductores .= ' -'.$fila_add["nombre"];
				}
				$ckconductores_impresion=";".$conductores;				
				$cols++;
			}
		else
			{
				$ckconductores_impresion="";
			}

		//Personal Dian
		if ($ckpersonalDian==1)
			{	
				$personal="";
				$sql_add="SELECT f.nombre, f.id_entidad FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia g ON g.id_funcionario = f.id WHERE g.id_guia='$id_guia' AND f.id_entidad='1'";
				$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 07". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila_add=mysql_fetch_array($consulta_add))
				{
					$personal .= ' -'.$fila_add["nombre"];
				}
				$ckpersonalDian_impresion=";".$personal;	
				$cols++;			
			}
		else
			{
				$ckpersonalDian_impresion="";
			}

		//Personal Taescol
		if ($ckpersonalTaescol==1)
			{	
				$personal="";
				$sql_add="SELECT f.nombre, f.id_entidad FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia g ON g.id_funcionario = f.id WHERE g.id_guia='$id_guia' AND f.id_entidad='2'";
				$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 08". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila_add=mysql_fetch_array($consulta_add))
				{
					$personal .= ' -'.$fila_add["nombre"];
				}
				$ckpersonalTaescol_impresion=";".$personal;		
				$cols++;		
			}
		else
			{
				$ckpersonalTaescol_impresion="";
			}

		//Personal Polfa
		if ($ckpersonalPolfa==1)
			{	
				$personal="";
				$sql_add="SELECT f.nombre, f.id_entidad FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia g ON g.id_funcionario = f.id WHERE g.id_guia='$id_guia' AND f.id_entidad='3'";
				$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 09". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila_add=mysql_fetch_array($consulta_add))
				{
					$personal .= ' -'.$fila_add["nombre"];
				}
				$ckpersonalPolfa_impresion=";".$personal;
				$cols++;				
			}
		else
			{
				$ckpersonalPolfa_impresion="";
			}

		//Personal Invima
		if ($ckpersonalInvima==1)
			{	
				$personal="";
				$sql_add="SELECT f.nombre, f.id_entidad FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia g ON g.id_funcionario = f.id WHERE g.id_guia='$id_guia' AND f.id_entidad='4'";
				$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 10". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila_add=mysql_fetch_array($consulta_add))
				{
					$personal .= ' -'.$fila_add["nombre"];
				}
				$ckpersonalInvima_impresion=";".$personal;		
				$cols++;		
			}
		else
			{
				$ckpersonalInvima_impresion="";
			}
	
		//Personal ICA
		if ($ckpersonalIca==1)
			{	
				$personal="";
				$sql_add="SELECT f.nombre, f.id_entidad FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia g ON g.id_funcionario = f.id WHERE g.id_guia='$id_guia' AND f.id_entidad='5'";
				$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 11". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila_add=mysql_fetch_array($consulta_add))
				{
					$personal .= ' -'.$fila_add["nombre"];
				}
				$ckpersonalIca_impresion=";".$personal;				
				$cols++;
			}
		else
			{
				$ckpersonalIca_impresion="";
			}

		//Personal Otros
		if ($ckpersonalOtros==1)
			{	
				$personal="";
				$sql_add="SELECT f.nombre, f.id_entidad FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia g ON g.id_funcionario = f.id WHERE g.id_guia='$id_guia' AND f.id_entidad='6'";
				$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 12". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila_add=mysql_fetch_array($consulta_add))
				{
					$personal .= ' -'.$fila_add["nombre"];
				}
				$ckpersonalOtros_impresion=";".$personal;
				$cols++;				
			}
		else
			{
				$ckpersonalOtros_impresion="";
			}

		//Personal Courier
		if ($ckpersonalCourier==1)
			{	
				$personal="";
				$sql_add="SELECT f.nombre, f.id_entidad FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia g ON g.id_funcionario = f.id WHERE g.id_guia='$id_guia' AND f.id_entidad='7'";
				$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 13". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila_add=mysql_fetch_array($consulta_add))
				{
					$personal .= ' -'.$fila_add["nombre"];
				}
				$ckpersonalCourier_impresion=";".$personal;				
				$cols++;
			}
		else
			{
				$ckpersonalCourier_impresion="";
			}

		//Fecha de llegada	
		if ($ckdatos_llegada==1)
			{	
				$courier_dato_llegada=";".$fila["courier_dato_llegada"];
				$cols++;
			}
		else
			{
				$courier_dato_llegada="";
			}

		//Fecha de Inicio Revision
		if ($ckdatos_inicio==1)
			{	
				$courier_dato_inicio=";".$fila["courier_dato_inicio"];
				$cols++;
			}
		else
			{
				$courier_dato_inicio="";
			}

		//Fecha de Fin Revision
		if ($ckdatos_fin==1)
			{	
				$courier_dato_fin=";".$fila["courier_dato_fin"];
				$cols++;
			}
		else
			{
				$courier_dato_fin="";
			}

		//Fecha de Documento Entrega
		if ($ckdatos_despacho_fin==1)
			{					
				$sql_add="SELECT fecha,hora FROM courier_despacho WHERE id_guia='$id_guia'";
				$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 15". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$fila_add=mysql_fetch_array($consulta_add);
				$ckdatos_despacho_fin_impresion=";".$fila_add["fecha"]." ".$fila_add["hora"];
				$cols++;
			}
		else
			{
				$ckdatos_despacho_fin_impresion="";
			}

		//Fecha de Documento Entrega
		if ($ckno_despacho==1)
			{					
				$sql_add="SELECT id FROM courier_despacho WHERE id_guia='$id_guia'";
				$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 16". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$fila_add=mysql_fetch_array($consulta_add);
				$ckno_despacho_impresion=";".$fila_add['id'];
				$cols++;
			}
		else
			{
				$ckno_despacho_impresion="";
			}

		//Datos de Facturacion
		if ($ckfacturacion==1)
			{					
				$sql_add="SELECT * FROM guia_factura WHERE id_guia='$id_guia'";
				$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 17". mysql_error(). " INFORME AL SOPORTE TECNICO");				
				$nfilas_add=mysql_num_rows($consulta_add);
				if($nfilas_add == 1)
				{
					$fila_add=mysql_fetch_array($consulta_add);
					$ckfacturacion_impresion = ";".$fila_add['nfactura'].";".number_format($fila_add['valor_factura'],0,",",".").";".number_format($fila_add['iva'],0,",",".").";".$fila_add['facturadoa'].";".$fila_add['fecha_factura'];

					//Escritura de fila en el reporte CON FACTURACION de una solo factura *************************************
					fputs($CL,"$i;$fecha_creacion;$hora_creacion;$aerolinea$cknguia_impresion$ckcantidad_hijas_impresion$ckp1168_impresion$ckcourier_impresion$piezas_impresion$peso_impresion$cklinea_impresion$ckvehiculos_impresion$ckconductores_impresion$ckpersonalDian_impresion$ckpersonalTaescol_impresion$ckpersonalPolfa_impresion$ckpersonalInvima_impresion$ckpersonalIca_impresion$ckpersonalOtros_impresion$ckpersonalCourier_impresion$courier_dato_llegada$courier_dato_inicio$courier_dato_fin$ckdatos_despacho_fin_impresion$ckno_despacho_impresion$ckfacturacion_impresion; \n");
				}
				else
				{
					$fila_add=mysql_fetch_array($consulta_add);
					$ckfacturacion_impresion = ";".$fila_add['nfactura'].";".number_format($fila_add['valor_factura'],0,",",".").";".number_format($fila_add['iva'],0,",",".").";".$fila_add['facturadoa'].";".$fila_add['fecha_factura'];
					//Escritura de primera fila en el reporte CON FACTURACION de VARIAS factura *************************************
					fputs($CL,"$i;$fecha_creacion;$hora_creacion;$aerolinea$cknguia_impresion$ckcantidad_hijas_impresion$ckp1168_impresion$ckcourier_impresion$piezas_impresion$peso_impresion$cklinea_impresion$ckvehiculos_impresion$ckconductores_impresion$ckpersonalDian_impresion$ckpersonalTaescol_impresion$ckpersonalPolfa_impresion$ckpersonalInvima_impresion$ckpersonalIca_impresion$ckpersonalOtros_impresion$ckpersonalCourier_impresion$courier_dato_llegada$courier_dato_inicio$courier_dato_fin$ckdatos_despacho_fin_impresion$ckno_despacho_impresion$ckfacturacion_impresion; \n");

					while ($fila_add=mysql_fetch_array($consulta_add)) 
					{
						for ($j=1; $j < $cols; $j++) 
						{ 
							$metadafaFacturacion .= ";";
						}
						$metadafaFacturacion .= ";".$fila_add['nfactura'].";".number_format($fila_add['valor_factura'],0,",",".").";".number_format($fila_add['iva'],0,",",".").";".$fila_add['facturadoa'].";".$fila_add['fecha_factura'];
						//Escritura filas restantes en el reporte CON FACTURACION de VARIAS factura *************************************
						fputs($CL,$metadafaFacturacion."\n");												
					}
					$metadafaFacturacion="";
				}
			}
		else
		{
			//Escritura de fila en el reporte SIN FACTURACION
			fputs($CL,"$i;$fecha_creacion;$hora_creacion;$aerolinea$cknguia_impresion$ckcantidad_hijas_impresion$ckp1168_impresion$ckcourier_impresion$piezas_impresion$peso_impresion$cklinea_impresion$ckvehiculos_impresion$ckconductores_impresion$ckpersonalDian_impresion$ckpersonalTaescol_impresion$ckpersonalPolfa_impresion$ckpersonalInvima_impresion$ckpersonalIca_impresion$ckpersonalOtros_impresion$ckpersonalCourier_impresion$courier_dato_llegada$courier_dato_inicio$courier_dato_fin$ckdatos_despacho_fin_impresion$ckno_despacho_impresion; \n");
		}		
		//Construccion del Cuerpo de la Tabla de Resumen
		$cuerpoTabla .= "<td>".$i."</td>";
		$cuerpoTabla .= "<td>".$fecha_creacion."</td>";
		$cuerpoTabla .= "<td>".$hora_creacion."</td>";
		$cuerpoTabla .= "<td>".$aerolinea."</td>";

		if(str_replace(";","",$cknguia_impresion)!="")
			$cuerpoTabla .= "<td>".str_replace(";","",$cknguia_impresion)."</td>";

		if(str_replace(";","",$ckcantidad_hijas_impresion)!="")
			$cuerpoTabla .= "<td>".str_replace(";","",$ckcantidad_hijas_impresion)."</td>";

		if(str_replace(";'","",$ckp1168_impresion)!="")
			$cuerpoTabla .= "<td>".str_replace(";'","",$ckp1168_impresion)."</td>";

		if(str_replace(";","",$ckcourier_impresion)!="")
			$cuerpoTabla .= "<td>".str_replace(";","",$ckcourier_impresion)."</td>";

		if(str_replace(";","",$piezas_impresion)!="")
			$cuerpoTabla .= "<td>".str_replace(";","",$piezas_impresion)."</td>";

		if(str_replace(";","",$peso_impresion)!="")
			$cuerpoTabla .= "<td>".str_replace(";","",$peso_impresion)."</td>";

		if(str_replace(";","",$cklinea_impresion)!="")
			$cuerpoTabla .= "<td>".str_replace(";","",$cklinea_impresion)."</td>";

		if(str_replace(";","",$ckvehiculos_impresion)!="")
			$cuerpoTabla .= "<td>".str_replace(";","",$ckvehiculos_impresion)."</td>";

		if(str_replace(";","",$ckno_despacho_impresion)!="")
			$cuerpoTabla .= "<td>".str_replace(";","",$ckno_despacho_impresion)."</td>";
				
		$cols=4;
	}
	$cuerpoTabla .= "</tr>";
	fputs($CL,";REPORTE CREADO POR;$usuario; \n");
	fputs($CL,";FECHA;$ano-$mes-$dia; \n");
	fputs($CL,";HORA;$hora_registro; \n");
	fputs($CL,";FIN DEL REPORTE; \n");
	fclose($CL);
	$metadataGeneral= 'El archivo se ha generado de manera Exitosa, oprima el bot&oacute;n para descargarlo.';
}
else
{
	$metadataGeneral= '
		<p align="center">
			<font size="+3"><strong>ATENCION</strong></font>
		</p>
		<hr>
		<br>
		<p align="center">No existen GU&Iacute;AS en EN ESE RANGO para generar un REPORTE</p>
		';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
    <link href="../tema/estilo.css" rel="stylesheet" type="text/css" />  
<!--    
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>    
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet"/>    
-->
<script src="../js/dataTables/jquery-3.3.1.js"></script>
<script src="../js/dataTables/jquery.dataTables.min.js"></script>    
<link href="../js/dataTables/jquery.dataTables.min.css" rel="stylesheet"/>    

    <title>Reporte</title>
</head>
<body>	
	<div id="conenido">		
	    <p class="titulo_tab_principal">Archivo de Reporte</p>
	    <p class="asterisco" align="center"><?php echo $metadataGeneral ?></p>
	    <p align="center">Recomendamos el uso de Microsoft Excel para la lectura de este archivo. <img src="../imagenes/excel.jpg" width="25" height="23" align="absmiddle" /></p>
	    <table width="450" align="center">
	        <tr>
	          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
	        </tr>
	        <tr>
	          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
	                <button name="terminar" type="button" onClick="window.close();">
	                    <img src="../imagenes/aceptar-act.png" title="Terminar" /><br />
	                  <strong>Terminar</strong>
	                </button>
	                            
	                <button name="imprimir" type="button" onClick="document.location='<?php echo $nombre_archivo ?>'">
	                    <img src="../imagenes/descargar-act2.png" title="Descargar" /><br />
	                  <strong>Descargar</strong>
	                </button>
	          </td>
	        </tr>
	    </table>    
	</div>	
	<div id="tablaResumen" style="width: 100%; text-align: center;">
		<p class="asterisco" align="center" style="font-size: 30px;">Tabla de Resumen</p>
		<table id="table_id" class="display" align="center">
		    <thead style="background-color:#566573">
		    	<?php echo $titulosTabla; ?>
		    </thead>
		    <tbody>
		        <?php echo $cuerpoTabla; ?>
		    </tbody>
		</table>	
	</div>
</body>
</html>
<script language="javascript">	
$(document).ready(function () 
{
	$('#table_id').DataTable({
								keys: true,
								responsive: true
							});

});

</script>
