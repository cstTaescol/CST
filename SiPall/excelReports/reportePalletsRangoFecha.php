<?php
session_start(); 
require_once '../config/parameters.php';
require_once '../config/db.php';
require_once '../models/Usuario.php';
require_once '../models/Vuelo.php';
require_once '../models/Aerolinea.php';
require_once '../models/Pallet.php';
require_once '../models/VueloPallet.php';

$buffer = "";	
$rangoini=isset($_GET['rangoini'])?$_GET['rangoini']:false;
$rangofin=isset($_GET['rangofin'])?$_GET['rangofin']:false;	
$tipoReporte=isset($_GET['tipoReporte'])?$_GET['tipoReporte']:false;

//Carga datos del Usuario
$id_usuario=$_SESSION['id_usuario'];
$usuario= new Usuario();  
$usuario->setIdUsuario($id_usuario);                       
$usuario = $usuario->getOne();
	
if($rangoini && $rangofin && $tipoReporte){				
	$vuelo = new Vuelo();
	$vuelo = $vuelo->getRangoFecha($rangoini,$rangofin);
	//Se consultan los vuelos recibidos en ese rango de fecha
	while ($filaVuelo = $vuelo->fetch_object()) {
		if ($usuario->id_aerolinea == "*" || $usuario->id_aerolinea == $filaVuelo->aerolinea)
		{	
			//Se identifica aerolinea
			$aerolinea = new Aerolinea();
			$aerolinea->setIdAerolinea($filaVuelo->aerolinea);			
			$aerolinea = $aerolinea->getOne();	

			switch($filaVuelo->estado){
				case 6:
					if($tipoReporte == "recibido" || $tipoReporte == "*"){
						$tipo = "Recibido";
						$vueloPallet = new VueloPallet();
						$vueloPallet->setVuelo($filaVuelo->id_vuelo);
						$listaPallets = $vueloPallet->getPalletVuelo();
						while ($filaPallets = $listaPallets->fetch_object()) {
							$pallet = new Pallet();
							$pallet->setIdPallet($filaPallets->pallet);
							$pallet = $pallet->getOne();

							$buffer .= '<tr>							    
										    <td class="celda_tabla_principal celda_boton">'.$pallet->numero.'</td>
										    <td class="celda_tabla_principal celda_boton">'.$aerolinea->nombre.'</td>
										    <td class="celda_tabla_principal celda_boton">'.$filaVuelo->fecha_vuelo.'</td>
										    <td class="celda_tabla_principal celda_boton">'.$filaVuelo->no_vuelo.'</td>
										    <td class="celda_tabla_principal celda_boton">'.$tipo.'</td>
										</tr>							  
					  				';
						}
					}					
				break;

				case 7:
					if($tipoReporte == "despachado" || $tipoReporte == "*"){
						$tipo = "Despachado";
						$vueloPallet = new VueloPallet();
						$vueloPallet->setVuelo($filaVuelo->id_vuelo);
						$listaPallets = $vueloPallet->getPalletVuelo();
						while ($filaPallets = $listaPallets->fetch_object()) {
							$pallet = new Pallet();
							$pallet->setIdPallet($filaPallets->pallet);
							$pallet = $pallet->getOne();

							$buffer .= '<tr>							    
										    <td class="celda_tabla_principal celda_boton">'.$pallet->numero.'</td>
										    <td class="celda_tabla_principal celda_boton">'.$aerolinea->nombre.'</td>
										    <td class="celda_tabla_principal celda_boton">'.$filaVuelo->fecha_vuelo.'</td>
										    <td class="celda_tabla_principal celda_boton">'.$filaVuelo->no_vuelo.'</td>
										    <td class="celda_tabla_principal celda_boton">'.$tipo.'</td>
										</tr>							  
					  				';
						}
					}					
				break;
			}
		}	
	}
	
	//Salida del archivo
	$filename='ReportePallets-'.$rangoini.'-'.$rangofin.'.xls';
	header("Content-type: application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	?>	
	<h2>Listado de Pallets</h2>
	<table border="1">
		<tbody>
			<tr>
				<td><strong>Pallet</strong></td>
				<td><strong>Aerolinea</strong></td>
				<td><strong>Fecha Vuelo</strong></td>
				<td><strong>No. Vuelo</strong></td>
				<td><strong>Tipo</strong></td>
			</tr>
			<?=$buffer?>				
		</tbody>
	</table>
	<?php
}
else{
	echo '<h2><span style="color: red">Error:</h2></span><h4>No se recibieron los datos</h4>';
}
?>
