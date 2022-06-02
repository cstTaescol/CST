<?php 
session_start(); 
set_time_limit (0); // Elimina la restriccion en el tiempo limite para la ejecicion del modulo.
require_once '../config/parameters.php';
require_once '../config/db.php';
require_once '../models/Usuario.php';
require_once '../models/Pallet.php';
require_once '../models/VueloPallet.php';
require_once '../models/Vuelo.php';
require_once '../models/Aerolinea.php';
require_once '../models/Ubicacion.php';

$buffer = "";
//Cargue general de pallets
$pallets = new Pallet();
$pallets = $pallets->getAllActives();

//Carga datos del Usuario
$id_usuario=$_SESSION['id_usuario'];
$usuario= new Usuario();  
$usuario->setIdUsuario($id_usuario);                       
$usuario = $usuario->getOne();

//Carga el listado de pallet en inventario
while ($fila = $pallets->fetch_object()){            
    //Datos del Vuelo
    $vueloPallet = new VueloPallet();
    $vueloPallet = $vueloPallet->getVueloPallet($fila->id_pallet);
    $vuelo =  new vuelo();
    $vuelo->setIdVuelo($vueloPallet->vuelo);
    $vuelo = $vuelo->getOne();
    if ($usuario->id_aerolinea == "*" || $usuario->id_aerolinea == $fila->aerolinea)
    {
	    //Aerolinea del Pallet
	    $aerolinea = new Aerolinea();
	    $aerolinea->setIdAerolinea($fila->aerolinea);
	    $aerolinea = $aerolinea->getOne();

	    //ubicacion
	    $ubicacion = new Ubicacion();
	    $ubicacion->setIdUbicacion($fila->ubicacion);
	    $ubicacion = $ubicacion->getOne();

	    //Contenedor Activo y Buen Estado
	    $contenedor_activo = $fila->contenedor_activo == 1?"SI":"NO";
	    $buen_estado = $fila->buen_estado == 1?"SI":"NO";     

	    //Alerta en Color por numero de días de estadía
	    date_default_timezone_set("America/Bogota");
	    $date1 = new DateTime($vuelo->fecha_vuelo);
	    $date2 = new DateTime("now");
	    $diff = $date1->diff($date2);
	    $buffer .= '<tr>';	   
		    $buffer .= '<td align="left">'. $fila->numero.'</td>';
		    $buffer .= '<td align="left">'. $aerolinea->nombre.'</td>';
		    $buffer .= '<td align="left">'. $vuelo->no_vuelo.'</td>';
		    $buffer .= '<td align="left">'. $vuelo->fecha_vuelo.'</td>';
		    $buffer .= '<td align="left">'. $ubicacion->nombre.'</td>';
		    $buffer .= '<td align="left">'. $buen_estado.'</td>';                       
		    $buffer .= '<td align="left">'. $contenedor_activo.'</td>';    
		    $buffer .= '<td align="left">'. $diff->days.'</td>';            
	    $buffer .= '</tr>';            
	}
}
$fechaReporte=date("Y-m-d");
$horaReporte=date("G:i:s");

//Salida del archivo


$filename='Indentario-'.$fechaReporte.'.xls';
header("Content-type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

?>
	<table border="1">
		<tr>
			<th align="center" colspan="8"><h2>Reporte Inventario <?=$fechaReporte?>(<?=$horaReporte?>)</h2></th>			
		</tr>
		<tr>
			<td>N&uacute;mero de Pallet</td>
			<td>Aerol&iacute;nea</td>
			<td>No Vuelo</td>
			<td>Fecha</td>
			<td>Ubicaci&oacute;n</td>
			<td>Buen Estado</td>
			<td>Contenedor Activo</td>
			<td>D&iacute;as en Bodega</td>
		</tr>
		<?=$buffer?>
	</table>