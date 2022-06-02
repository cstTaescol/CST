<?php 
require_once '../config/parameters.php';
require_once '../config/db.php';
require_once '../models/Vuelo.php';
require_once '../models/Estado.php';
require_once '../models/Aerolinea.php';
require_once '../models/Pallet.php';
require_once '../models/VueloPallet.php';

$id_vuelo=isset($_GET['id'])?$_GET['id']:false;        
if($id_vuelo){
    $buffer = "";
    $cont = 0;
    $vuelo = new Vuelo();
    $vuelo->setIdVuelo($id_vuelo);
    $vuelo = $vuelo->getOne();
    $mayasTotal = $vuelo->mallas_bueno + $vuelo->mallas_malo;
    $correasTotal = $vuelo->correas_bueno + $vuelo->correas_malo;
    $plasticobaseTotal = $vuelo->plasticobase_bueno + $vuelo->plasticobase_malo;
    $plasticocapuchonTotal = $vuelo->plasticocapuchon_bueno + $vuelo->plasticocapuchon_malo;
    $pernosTotal = $vuelo->pernos_bueno + $vuelo->pernos_malo;
    $vinipelTotal = $vuelo->vinipel_bueno + $vuelo->vinipel_malo;
    $lazosTotal = $vuelo->lazos_bueno + $vuelo->lazos_malo;
    $conectoresTotal = $vuelo->conectores_bueno + $vuelo->conectores_malo;
    $maderosTotal = $vuelo->maderos_bueno + $vuelo->maderos_malo;

    $estado = new Estado();
    $estado->setIdEstado($vuelo->estado);
    $estado = $estado->getOne();

    $aerolinea = new Aerolinea();
    $aerolinea->setIdAerolinea($vuelo->aerolinea);
    $aerolinea = $aerolinea->getOne();

    $vuelo_pallet = new VueloPallet();
    $vuelo_pallet->setVuelo($id_vuelo);
    $listaPallets = $vuelo_pallet->getPalletVuelo();
    //Se consultas los pallets asociados a ese vuelo
    while ($filaVueloPallet = $listaPallets->fetch_object()) {
        //Se identifican los datos del Pallet
        $pallet = new Pallet();
        $pallet->setIdPallet($filaVueloPallet->pallet);
        $pallet = $pallet->getOne();
        $contenedorActivo = $pallet->contenedor_activo == true ? "SI" : "NO";
        $buenEstado = $pallet->buen_estado == true ? "SI" : "NO";                                
        $buffer .= '<tr>                                    
                        <td>'.++$cont.'</td>
                        <td>'.$pallet->numero.'</td>
                        <td>'.$buenEstado.'</td>
                        <td>'.$contenedorActivo.'</td>
                    </tr>';
    }
	//Salida del archivo
	$filename='ReporteVuelo-'.$vuelo->no_vuelo.'.xls';
	//header("Pragma: public");	
	//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	//header("Content-type: application/x-msdownload");
	header("Content-type: application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	?>
	<table border="1">
		<tr>
			<th align="center" colspan="2"><h2>Reporte Vuelo No <?=$vuelo->no_vuelo?></h2></th>
		</tr>
		<tr>
		<td>N&uacute;mero</td>
			<td><?=$vuelo->no_vuelo?></td>
		</tr>
		<tr>
			<td>Aerol&iacute;nea</div></td>
			<td><?=$aerolinea->nombre?></td>
		</tr>
		<tr>
			<td>Matr&iacute;cula</div></td>
			<td><?=$vuelo->matricula?></td>
		</tr>
		<tr>
			<td>Fecha del Vuelo</div></td>
			<td><?=$vuelo->fecha_vuelo?></td>
		</tr>
		<tr>
			<td>Fecha de Creaci&oacute;n</div></td>
			<td><?=$vuelo->fechahora_creacion?></td>
		</tr>
	</table>


	<table border="1">
		<tbody>
			<tr>
				<th align="center" colspan="4"><h2>Pallets del Vuelo</h2></th>
			</tr>
			<tr>
				<td><strong>No</strong></td>
				<td><strong>N&uacute;mero Pallet</strong></td>
				<td><strong>En Buen Estado?</strong></td>
				<td><strong>Contenedor Activo?</strong></td>
			</tr>
			<?=$buffer?>				
		</tbody>
	</table>
	<table border="1">
	  <tr>
		<th align="center" colspan="4"><h2>Art&iacute;culos del Vuelo</h2></th>
	  </tr>

	  <tr>
	    <td><strong>Elemento</strong></td>
	    <td><strong>Buenos</strong></td>
	    <td><strong>Malos</strong></td>
	    <td><strong>Total</strong></td>    
	  </tr>
	  <tr>
	    <td>Mallas</td>
	    <td align="right"><?=$vuelo->mallas_bueno?></td>
	    <td align="right"><?=$vuelo->mallas_malo?></td>
	    <td align="right"><?=$mayasTotal?></td>   
	  </tr>
	  <tr>
	    <td>Correas</td>
	    <td align="right"><?=$vuelo->correas_bueno?></td>
	    <td align="right"><?=$vuelo->correas_malo?></td>
	    <td align="right"><?=$correasTotal?></td>   
	  </tr>
	  <tr>
	    <td>Plastico Base</td>
	    <td align="right"><?=$vuelo->plasticobase_bueno?></td>
	    <td align="right"><?=$vuelo->plasticobase_malo?></td>
	    <td align="right"><?=$plasticobaseTotal?></td>   
	  </tr>  
	  <tr>
	    <td>Plastico Capuch&oacute;n</td>
	    <td align="right"><?=$vuelo->plasticocapuchon_bueno?></td>
	    <td align="right"><?=$vuelo->plasticocapuchon_malo?></td>
	    <td align="right"><?=$plasticocapuchonTotal?></td>   
	  </tr> 
	  <tr>
	    <td>Pernos</td>
	    <td align="right"><?=$vuelo->pernos_bueno?></td>
	    <td align="right"><?=$vuelo->pernos_malo?></td>
	    <td align="right"><?=$pernosTotal?></td>   
	  </tr> 
	  <tr>
	    <td>Vinipel</td>
	    <td align="right"><?=$vuelo->vinipel_bueno?></td>
	    <td align="right"><?=$vuelo->vinipel_malo?></td>
	    <td align="right"><?=$vinipelTotal?></td>   
	  </tr> 
	  <tr>
	    <td>Lazos</td>
	    <td align="right"><?=$vuelo->lazos_bueno?></td>
	    <td align="right"><?=$vuelo->lazos_malo?></td>
	    <td align="right"><?=$lazosTotal?></td>   
	  </tr> 
	  <tr>
	    <td>Conectores</td>
	    <td align="right"><?=$vuelo->conectores_bueno?></td>
	    <td align="right"><?=$vuelo->conectores_malo?></td>
	    <td align="right"><?=$conectoresTotal?></td>   
	  </tr> 
	  <tr>
	    <td>Maderos</td>
	    <td align="right"><?=$vuelo->maderos_bueno?></td>
	    <td align="right"><?=$vuelo->maderos_malo?></td>
	    <td align="right"><?=$maderosTotal?></td>   
	  </tr>                       
	</table>
	<table border="1">
	  <tr>
		<th align="center" colspan="4"><h2>Otros</h2></th>
	  </tr>
	  <tr>
	    <td><strong>Cuales:</strong></td>
	    <td colspan="3" align="right"><?=$vuelo->otros_cuales?></td>   
	  </tr>
	</table>
	<?php
}
else{
	echo '<h2><span style="color: red">Error:</h2></span><h4>No se recibieron los datos</h4>';
}
?>
