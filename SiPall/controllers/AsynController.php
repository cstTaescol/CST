<?php
session_start();
require_once '../config/parameters.php';
require_once '../config/db.php';
require_once '../models/Ubicacion.php';
require_once '../models/Usuario.php';
require_once '../models/Aerolinea.php';
require_once '../models/Vuelo.php';
require_once '../models/Pallet.php';
require_once '../models/VueloPallet.php';
require_once '../models/Historial.php';
require_once '../models/PalletNovedad.php';
require_once '../models/ArticulosAerolinea.php';

if (isset($_REQUEST['action'])) {	
	$action=$_REQUEST['action'];	
	//Adicionar al array cada nueva función		
	$listaAcciones=array('False',
						'listPalletSession',
						'addPalletSession',
						'clearAllItemsPalletSession',
						'clearItemsPalletSession',
						'vueloGuardar',
						'consultaNovedadPallet',
						'guardarNovedadPallet',
						'reubicacionPallet',
						'guardarReubicacionPallet',						
						'consultaHistorialPallet',
						'listPalletAero',
						'vueloDespachar',
						'vueloTransferencia',
						'buscarPallet',						
						'identificarVueloUCM',
						'crearUCM',
						'totalesArticulosAerolinea',
						'crearSCM',
						'buscarVuelo',
						'activarPallet',
						'guardarModificarPallet',
						'guardarAjusteManualElementos',
						'reportePallets',
						'guardarNovedadesMultiples'
					);	
	$existe=array_search(strval($action),$listaAcciones);
	if($existe){
		$action();	
	}
	else{
		echo '<h2><span style="color: red">Error:</h2></span><h4>No se reconoce la acción</h4>';	
	}				
}
else{
	echo '<h2><span style="color: red">Error:</h2></span><h4>No se recibió una acción</h4>';	
}


function addPalletSession(){
	if(!isset($_SESSION['pallet'])){
		$_SESSION['pallet']=array();
	}
	$prefijo=isset($_POST['prefijo'])?strtoupper($_POST['prefijo']):false;
	$identificador=isset($_POST['identificador'])?$_POST['identificador']:false;
	$sufijo=isset($_POST['sufijo'])?strtoupper($_POST['sufijo']):false;
	$buen_estado=isset($_POST['buen_estado'])?$_POST['buen_estado']:false;
	$ubicacion=isset($_POST['ubicacion'])?$_POST['ubicacion']:false;
	$contenedor_activo=isset($_POST['contenedor_activo'])?$_POST['contenedor_activo']:false;
	$observaciones=isset($_POST['observaciones'])?$_POST['observaciones']:false;
	if($prefijo && $identificador && $sufijo)
	{						
		$ubicacionDb = new Ubicacion();
		$ubicacionDb->setIdUbicacion($ubicacion);
		$ubicacionDb = $ubicacionDb->getOne();
		$contenedor_activo_text = ($contenedor_activo==1)?"Si":"No";
		$buen_estado_text = ($buen_estado==1)?"Bueno":"Malo";

		$_SESSION['pallet'][]= array(
                                    'numero'=>$prefijo.$identificador.$sufijo,                                    
                                    'ubicacion'=>$ubicacionDb->id_ubicacion,
                                    'ubicacion_text'=>$ubicacionDb->nombre,
                                    'observaciones'=>$observaciones,
                                    'contenedor_activo'=>$contenedor_activo,
                                    'contenedor_activo_text'=>$contenedor_activo_text,
                                    'buen_estado'=>$buen_estado,
                                    'buen_estado_text'=>$buen_estado_text    

                                    );
	}	
	listPalletSession();
}

function listPalletSession(){
	$buffer = '
				<table class="table table-hover">
					<thead class="celda_tabla_principal">
						<tr>					  						  
						  <th><div class="letreros_tabla">Número de Pallet</div></th>
						  <th><div class="letreros_tabla">Estado</div></th>
						  <th><div class="letreros_tabla">Ubicación</div></th>
						  <th><div class="letreros_tabla">Contenedor Activo</div></th>
						  <th><div class="letreros_tabla">Acciones</div></th>
						</tr>
					</thead>
					<tbody>
          	';
    if(isset($_SESSION['pallet'])){
		foreach ($_SESSION['pallet'] as $key=> $datos) {		
			$buffer .= '
						<tr align="center">							
							<td>'.$datos['numero'].'</td>
							<td>'.$datos['buen_estado_text'].'</td>
							<td>'.$datos['ubicacion_text'].'</td>
							<td>'.$datos['contenedor_activo_text'].'</td>							
							<td>
								<button class="btn btn-danger" type="button" onclick="clearItemsPalletSession('.$key.');" title="Quitar Item">
									<img src="'.base_Father.'imagenes/eliminar-act.png" alt="" title="Quitar Item" align="absmiddle"/>
								</button>
							</td>
						</tr>
			';		
		}
	}	
	$buffer .= '<tbody></table>';
	echo $buffer;	
}

function listPalletAero(){
	$buffer = '
				<table class="table table-hover">
					<thead class="celda_tabla_principal">
						<tr>					  						  
						  <th><div class="letreros_tabla">Seleccionar</div></th>
						  <th><div class="letreros_tabla">Número de Pallet</div></th>
						  <th><div class="letreros_tabla">Estado</div></th>
						  <th><div class="letreros_tabla">Ubicación</div></th>
						</tr>
					</thead>
					<tbody>
          	';

	if(isset($_GET['id']) && $_GET['id'] != ""){
		$id_aerolinea=$_GET['id'];
		$pallet = new Pallet();
		$pallet->setAerolinea($id_aerolinea);
		$pallet = $pallet->getAllActivesByAerolinea();
		while($fila = $pallet->fetch_object()){
			$ubicacion = new Ubicacion();
			$ubicacion->setIdUbicacion($fila->ubicacion);
			$ubicacion = $ubicacion->getOne();
			$buen_estado = $fila->buen_estado==1?"Bueno":"Malo";

			$buffer .= '
						<tr align="center">							
							<td>
								<input type="checkbox" name="'.$fila->id_pallet.'" id="'.$fila->id_pallet.'" value="'.$fila->id_pallet.'">
							</td>						
							<td>'.$fila->numero.'</td>
							<td>'.$buen_estado.'</td>
							<td>'.$ubicacion->nombre.'</td>						
						</tr>
			';			
		}		
	}	
	$buffer .= '<tbody></table>';
	echo $buffer;	
}

function clearItemsPalletSession(){	
	$id=isset($_GET['id'])?$_GET['id']:false;
	if($id != 0){
		unset($_SESSION['pallet'][$id]);
		echo listPalletSession();
	}		
	else{
		unset($_SESSION['pallet'][0]);
		echo listPalletSession();
	}			
}

function clearAllItemsPalletSession(){
	unset($_SESSION['pallet']);			
	echo listPalletSession();
}

function vueloGuardar() {        
	if(isset($_SESSION['pallet'])){
	        if(count($_SESSION['pallet']) == 0){
	            echo 'error|*|<h2><span style="color: red">Error:</h2></span><h4>Debe adicionar los Pallets"</h4>';
	        }
	        else
	        {
	            $id_usuario=$_SESSION['id_usuario']; 
	            $destino = 'inventarioGeneral';
	            $mallas=false;
	            $mallas_bueno=0;
	            $mallas_malo=0;
	            $correas=false;
	            $correas_bueno=0;
	            $correas_malo=0;
	            $plasticobase=false;
	            $plasticobase_bueno=0;
	            $plasticobase_malo=0;
	            $plasticocapuchon=false;
	            $plasticocapuchon_bueno=0;
	            $plasticocapuchon_malo=0;
	            $pernos=false;
	            $pernos_bueno=0;
	            $pernos_malo=0;
	            $vinipel=false;
	            $vinipel_bueno=0;
	            $vinipel_malo=0;
	            $lazos=false;
	            $lazos_bueno=0;
	            $lazos_malo=0;
	            $conectores=false;
	            $conectores_bueno=0;
	            $conectores_malo=0;
	            $maderos=false;
	            $maderos_bueno=0;
	            $maderos_malo=0;
	            $otros=false;
	            $otros_cuales=null;

	            //Validacion de Datos recibidos
	            $id_aerolinea=isset($_POST['id_aerolinea'])?$_POST['id_aerolinea']:false;
	            $no_vuelo=isset($_POST['no_vuelo'])?strtoupper($_POST['no_vuelo']):false;
	            $matricula=isset($_POST['matricula'])?strtoupper($_POST['matricula']):false;
	            $fecha_vuelo=isset($_POST['fecha_vuelo'])?strtoupper($_POST['fecha_vuelo']):false;

	            $mallas=isset($_POST['mallas'])?$_POST['mallas']:false;
	            if($mallas == 1){
	                $mallas_bueno=isset($_POST['mallas_bueno'])?$_POST['mallas_bueno']:0;
	                $mallas_malo=isset($_POST['mallas_malo'])?$_POST['mallas_malo']:0;                            
	            }

	            $correas=isset($_POST['correas'])?$_POST['correas']:false;
	            if($correas == 1){
	                $correas_bueno=isset($_POST['correas_bueno'])?$_POST['correas_bueno']:0;
	                $correas_malo=isset($_POST['correas_malo'])?$_POST['correas_malo']:0;                            
	            }

	            $plasticobase=isset($_POST['plasticobase'])?$_POST['plasticobase']:false;
	            if($plasticobase == 1){
	                $plasticobase_bueno=isset($_POST['plasticobase_bueno'])?$_POST['plasticobase_bueno']:0;
	                $plasticobase_malo=isset($_POST['plasticobase_malo'])?$_POST['plasticobase_malo']:0;                            
	            }                        


	            $plasticocapuchon=isset($_POST['plasticocapuchon'])?$_POST['plasticocapuchon']:false;
	            if($plasticocapuchon == 1){
	                $plasticocapuchon_bueno=isset($_POST['plasticocapuchon_bueno'])?$_POST['plasticocapuchon_bueno']:0;
	                $plasticocapuchon_malo=isset($_POST['plasticocapuchon_malo'])?$_POST['plasticocapuchon_malo']:0;                            
	            }                           


	            $pernos=isset($_POST['pernos'])?$_POST['pernos']:false;
	            if($pernos == 1){
	                $pernos_bueno=isset($_POST['pernos_bueno'])?$_POST['pernos_bueno']:0;
	                $pernos_malo=isset($_POST['pernos_malo'])?$_POST['pernos_malo']:0;                            
	            }   


	            $vinipel=isset($_POST['vinipel'])?$_POST['vinipel']:false;
	            if($vinipel == 1){
	                $vinipel_bueno=isset($_POST['vinipel_bueno'])?$_POST['vinipel_bueno']:0;
	                $vinipel_malo=isset($_POST['vinipel_malo'])?$_POST['vinipel_malo']:0;                            
	            }                          


	            $lazos=isset($_POST['lazos'])?$_POST['lazos']:false;
	            if($lazos == 1){
	                $lazos_bueno=isset($_POST['lazos_bueno'])?$_POST['lazos_bueno']:0;
	                $lazos_malo=isset($_POST['lazos_malo'])?$_POST['lazos_malo']:0;                            
	            }                         


	            $conectores=isset($_POST['conectores'])?$_POST['conectores']:false;
	            if($conectores == 1){
	                $conectores_bueno=isset($_POST['conectores_bueno'])?$_POST['conectores_bueno']:0;
	                $conectores_malo=isset($_POST['conectores_malo'])?$_POST['conectores_malo']:0;                            
	            }     


	            $maderos=isset($_POST['maderos'])?$_POST['maderos']:false;
	            if($maderos == 1){
	                $maderos_bueno=isset($_POST['maderos_bueno'])?$_POST['maderos_bueno']:0;
	                $maderos_malo=isset($_POST['maderos_malo'])?$_POST['maderos_malo']:0;                            
	            }    


	            $otros=isset($_POST['otros'])?$_POST['otros']:false;
	            if($otros == 1){
	                $otros_cuales=isset($_POST['otros_cuales'])?$_POST['otros_cuales']:null;                         
	            } 

	            if($id_aerolinea && $no_vuelo && $id_usuario && $fecha_vuelo){
	                $vuelo = new Vuelo();
	                $vuelo->setAerolinea($id_aerolinea);
	                $vuelo->setNo_vuelo($no_vuelo);
	                $vuelo->setMatricula($matricula);
	                $vuelo->setFecha_vuelo($fecha_vuelo);
	                $vuelo->setMallas($mallas);
	                $vuelo->setMallas_bueno($mallas_bueno);
	                $vuelo->setMallas_malo($mallas_malo);                            
	                $vuelo->setCorreas($correas);
	                $vuelo->setCorreas_bueno($correas_bueno);
	                $vuelo->setCorreas_malo($correas_malo);
	                $vuelo->setPlasticobase($plasticobase);
	                $vuelo->setPlasticobase_bueno($plasticobase_bueno);
	                $vuelo->setPlasticobase_malo($plasticobase_malo);
	                $vuelo->setPlasticocapuchon($plasticocapuchon);
	                $vuelo->setPlasticocapuchon_bueno($plasticocapuchon_bueno);
	                $vuelo->setPlasticocapuchon_malo($plasticocapuchon_malo);
	                $vuelo->setPernos($pernos);
	                $vuelo->setPernos_bueno($pernos_bueno);
	                $vuelo->setPernos_malo($pernos_malo);
	                $vuelo->setVinipel($vinipel);
	                $vuelo->setVinipel_bueno($vinipel_bueno);
	                $vuelo->setVinipel_malo($vinipel_malo);
	                $vuelo->setLazos($lazos);
	                $vuelo->setLazos_bueno($lazos_bueno);
	                $vuelo->setLazos_malo($lazos_malo);
	                $vuelo->setConectores($conectores);
	                $vuelo->setConectores_bueno($conectores_bueno);
	                $vuelo->setConectores_malo($conectores_malo);
	                $vuelo->setMaderos($maderos);
	                $vuelo->setMaderos_bueno($maderos_bueno);
	                $vuelo->setMaderos_malo($maderos_malo);
	                $vuelo->setOtros($otros);
	                $vuelo->setOtros_cuales($otros_cuales);
	                $vuelo->setEstado(6);
	                $vuelo = $vuelo->save();       

	                //Guardado de Pallets
	                foreach ($_SESSION['pallet'] as $key=> $datos) {  
	                    $pallet = new Pallet();      
	                    $pallet->setNumero($datos['numero']);	                    
						$pallet->setAerolinea($id_aerolinea);
						$pallet->setUbicacion($datos['ubicacion']);
						$pallet->setObservaciones($datos['observaciones']);
						$pallet->setContenedor_activo($datos['contenedor_activo']);
						$pallet->setBuen_estado($datos['buen_estado']);	                   
						$pallet->setEstado(5);

						$siExiste = $pallet->getIfExists();
	                    if ($siExiste){
							//obetener datos del pallet pre-existente
							$estadoActual=$siExiste->estado;
							if($estadoActual != 5){
								//Actualizar Datos del Pallet pre-existente cuando llega en un nuevo vuelo
								$pallet->setIdPallet($siExiste->id_pallet); 								
								$pallet = $pallet->update();
								$pallet = $siExiste->id_pallet;								
							}else{
								//Guarda Pallet Repetido adicionado sigla de repeticiones al numero de pallet	
								$nuevoNumero=$datos['numero'].'(Rep)';
								$pallet->setNumero($nuevoNumero);	  
			                    $pallet=$pallet->save();
			                    //Guardado en Historial
			                    guardarHistorialPallet($pallet,'Pallet repetido creado',$id_usuario);	

			                    //Cambia la redireccion hacia Adicion de Novedades de Pallet Obligatoria
			                    $destino = 'palletRepetido';		
			                    $_SESSION['palletRepetido'][]= array(
								                                    'id_pallet'=>$pallet,
								                                    'numero'=>$nuevoNumero								                                    
								                                    );
							}
	                    }else{		                      
		                    //Guarda nuevo Pallet
		                    $pallet=$pallet->save();
		                    //Guardado en Historial
		                    guardarHistorialPallet($pallet,'Pallet creado',$id_usuario);
	                    }

	                    //Guardado Relacional
	                    $vuelo_pallet = new VueloPallet();
	                    $vuelo_pallet->setVuelo($vuelo);
	                    $vuelo_pallet->setPallet($pallet);
	                    $vuelo_pallet->save();

	                    //Datos de Aerolínea con la que llega
	                    $aerolinea = new Aerolinea();
	                    $aerolinea->setIdAerolinea($id_aerolinea);
	                    $aerolinea = $aerolinea->getOne();

	                    //Guardado en Historial
		                guardarHistorialPallet($pallet,'Pallet Recibico con la Aerolínea:<br>'.$aerolinea->nombre.'<br>Vuelo No: '.$no_vuelo.'<br>Fecha: '.$fecha_vuelo.'',$id_usuario);
	                }

	                //Guardado de Inventario de Artículos	                
	                $articulosAerlinea = new ArticulosAerolinea();
	                $articulosAerlinea->setAerolinea($id_aerolinea);
	                $preexistencia = $articulosAerlinea->getOneByAerolinea();
	                if ($preexistencia){
	                	$articulosAerlinea->setMallas($mallas_bueno + $mallas_malo + $preexistencia->mallas);
	                	$articulosAerlinea->setCorreas($correas_bueno + $correas_malo + $preexistencia->correas);
	                	$articulosAerlinea->setPlasticobase($plasticobase_bueno + $plasticobase_malo + $preexistencia->plasticobase);
	                	$articulosAerlinea->setPlasticocapuchon($plasticocapuchon_bueno + $plasticocapuchon_malo + $preexistencia->plasticocapuchon);
	                	$articulosAerlinea->setPernos($pernos_bueno + $pernos_malo + $preexistencia->pernos);
	                	$articulosAerlinea->setVinipel($vinipel_bueno + $vinipel_malo + $preexistencia->vinipel);
	                	$articulosAerlinea->setLazos($lazos_bueno + $lazos_malo + $preexistencia->lazos);
	                	$articulosAerlinea->setConectores($conectores_bueno + $conectores_malo + $preexistencia->conectores);
	                	$articulosAerlinea->setMaderos($maderos_bueno + $maderos_malo + $preexistencia->maderos);
	                	$articulosAerlinea->setIdArticulosAerolinea($preexistencia->id_articulosaerolinea);
	                	$articulosAerlinea->updateOne();
	                }else{
	                	$articulosAerlinea->setMallas($mallas_bueno + $mallas_malo);
	                	$articulosAerlinea->setCorreas($correas_bueno + $correas_malo);
	                	$articulosAerlinea->setPlasticobase($plasticobase_bueno + $plasticobase_malo);
	                	$articulosAerlinea->setPlasticocapuchon($plasticocapuchon_bueno + $plasticocapuchon_malo);
	                	$articulosAerlinea->setPernos($pernos_bueno + $pernos_malo);
	                	$articulosAerlinea->setVinipel($vinipel_bueno + $vinipel_malo);
	                	$articulosAerlinea->setLazos($lazos_bueno + $lazos_malo);
	                	$articulosAerlinea->setConectores($conectores_bueno + $conectores_malo);
	                	$articulosAerlinea->setMaderos($maderos_bueno + $maderos_malo);	                	
	                	$articulosAerlinea->save();
	                }
	            }
	            if ($vuelo > 0){                        
	                unset($_SESSION['pallet']);     
	                echo 'url|*|'.base_url.'Central/'.$destino;                        
	                //echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.base_url.'Central/inventarioGeneral">';                        
	                exit;
	            }
	            else{                        
	                echo 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se ha guardado el registro</h4>';
	            }
	        }
	}
	else{
	    echo 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No ha creado los Pallets</h4>';
	}            
}

function guardarNovedadPallet() {
	$id_pallet=isset($_POST['id_pallet'])?$_POST['id_pallet']:false;
	$descripcion=isset($_POST['descripcion'])?$_POST['descripcion']:false;
	$id_usuario=$_SESSION['id_usuario'];  
	$buffer = "";
	if($id_pallet && $descripcion && $id_usuario){
		$palletNovedad = new PalletNovedad();
		$palletNovedad->setPallet($id_pallet);  
		$palletNovedad->setDescripcion($descripcion);  
		$palletNovedad->setUsuario($id_usuario);  
		$palletNovedad = $palletNovedad->save();					
	    if ($palletNovedad > 0){                        	       
	        //Guardado en Historial
	        guardarHistorialPallet($id_pallet,'Novedad Agregada: '.$descripcion,$id_usuario);
	        listarNovedadesPallet($id_pallet);
	        exit;                       	        	        
	    }
	    else{                        
	        $buffer .= 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se ha guardado el registro</h4>';
	    }
		
	}else{
		$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se ha recibido el dato</h4>';
	}	
	echo $buffer;
}	

function consultaNovedadPallet() {	
	if(isset($_GET['id'])){
		$id_pallet=$_GET['id'];
		listarNovedadesPallet($id_pallet);
	}else{
    	$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se ha recibido el dato</h4>';
	} 
}

function reubicacionPallet() {	
	if(isset($_GET['id'])){
		$id_pallet=$_GET['id'];		
		$buffer = "tupla|*|";

		//Consulta del Pallet        	        	
		$pallet = new Pallet();
		$pallet->setIdPallet($id_pallet);        	        	
		$pallet = $pallet->getOne();
		$numero=$pallet->numero;
		$buffer .= $numero;
	}else{
    	$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se ha recibido el dato</h4>';
	}
	echo $buffer;
}

function guardarReubicacionPallet() {	
    //Validacion de Datos recibidos
    $buffer = "";
    $id_usuario=$_SESSION['id_usuario'];
    $id_ubicacion=isset($_POST['reubicacion'])?$_POST['reubicacion']:false;
    $id_pallet=isset($_POST['id_pallet_Reubicacion'])?strtoupper($_POST['id_pallet_Reubicacion']):false;
    if($id_ubicacion && $id_pallet && $id_usuario){		
		//Consulta del Pallet        	        	
		$pallet = new Pallet();
		$pallet->setIdPallet($id_pallet); 
		$updatePallet = $pallet->updateOne("ubicacion",$id_ubicacion);
		if($updatePallet){
			$pallet = $id_pallet;
			$ubicacion = new Ubicacion();
			$ubicacion->setIdUbicacion($id_ubicacion);
			$ubicacion = $ubicacion->getOne();
			//Guardado en Historial
			guardarHistorialPallet($pallet,'Pallet Reubicado en la posición:<br>'.$ubicacion->nombre,$id_usuario);	

			$buffer .= 'url|*|'.base_url.'Central/inventarioGeneral'; 
		}else{
			$buffer .= 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se ha guardado el registro</h4>';	
		}
    }else{
    	$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se ha recibido el dato</h4>';
    }
	echo $buffer;
}

function consultaHistorialPallet(){
	if(isset($_GET['id'])){
		$id_pallet=$_GET['id'];
		$buffer = "tupla";
		//Consulta del Pallet        	        	
		$pallet = new Pallet();
		$pallet->setIdPallet($id_pallet);        	        	
		$pallet = $pallet->getOne();
		$numero=$pallet->numero;
		$buffer .= '|*|'.$numero.'';

		//Consulta de las novedades del pallet
		$historial = new Historial();
		$historial->setPallet($id_pallet);
		$historial = $historial->getAllByPallet();	
		$buffer .= '|*|';
		$buffer .= '<table class="table table-striped">
						<thead>
						  <tr>						    
						    <th>Descripcion</th>
						    <th>Fecha/Usuario</th>
						  </tr>
						</thead>
						<tbody>';

		while ($fila = $historial->fetch_object()){
			$usuario = new Usuario();
			$usuario->setIdUsuario($fila->usuario);
			$usuario = $usuario->getOne();

			$buffer .= '<tr>			        
					        <td>'.$fila->descripcion.'</td>
					        <td>'.$fila->fechahora_creacion.'<br>'.$usuario->nombre.'</td>
					      </tr>';	
		}
		$buffer .= '</tbody></table>';		

	}else{
    	$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se ha recibido el dato</h4>';
	} 
	echo $buffer;	
}

function vueloDespachar() {
	$id_usuario=$_SESSION['id_usuario'];   
    //Validacion de Datos recibidos
    $id_aerolinea=isset($_POST['id_aerolinea'])?$_POST['id_aerolinea']:false;
    $no_vuelo=isset($_POST['no_vuelo'])?strtoupper($_POST['no_vuelo']):false;
    $matricula=isset($_POST['matricula'])?strtoupper($_POST['matricula']):false;
    $fecha_vuelo=isset($_POST['fecha_vuelo'])?strtoupper($_POST['fecha_vuelo']):false;  
    //$buffer = "tupla|*| Hola mundoss";

	if($id_aerolinea && $no_vuelo && $id_usuario && $fecha_vuelo){
		$cont = 0;		
		$palletSearch = new Pallet();
		$palletSearch->setAerolinea($id_aerolinea);
		$palletSearch = $palletSearch->getAllActivesByAerolinea();		
		while($fila = $palletSearch->fetch_object()){
			if(isset($_POST["$fila->id_pallet"])){
				$cont++;				
			}			
		}
		
		if ($cont != 0){
	            $mallas=false;
	            $mallas_bueno=0;
	            $mallas_malo=0;
	            $correas=false;
	            $correas_bueno=0;
	            $correas_malo=0;
	            $plasticobase=false;
	            $plasticobase_bueno=0;
	            $plasticobase_malo=0;
	            $plasticocapuchon=false;
	            $plasticocapuchon_bueno=0;
	            $plasticocapuchon_malo=0;
	            $pernos=false;
	            $pernos_bueno=0;
	            $pernos_malo=0;
	            $vinipel=false;
	            $vinipel_bueno=0;
	            $vinipel_malo=0;
	            $lazos=false;
	            $lazos_bueno=0;
	            $lazos_malo=0;
	            $conectores=false;
	            $conectores_bueno=0;
	            $conectores_malo=0;
	            $maderos=false;
	            $maderos_bueno=0;
	            $maderos_malo=0;
	            $otros=false;
	            $otros_cuales=null;

	            $mallas=isset($_POST['mallas'])?$_POST['mallas']:false;
	            if($mallas == 1){
	                $mallas_bueno=isset($_POST['mallas_bueno'])?$_POST['mallas_bueno']:0;
	                $mallas_malo=isset($_POST['mallas_malo'])?$_POST['mallas_malo']:0;                            
	            }

	            $correas=isset($_POST['correas'])?$_POST['correas']:false;
	            if($correas == 1){
	                $correas_bueno=isset($_POST['correas_bueno'])?$_POST['correas_bueno']:0;
	                $correas_malo=isset($_POST['correas_malo'])?$_POST['correas_malo']:0;                            
	            }

	            $plasticobase=isset($_POST['plasticobase'])?$_POST['plasticobase']:false;
	            if($plasticobase == 1){
	                $plasticobase_bueno=isset($_POST['plasticobase_bueno'])?$_POST['plasticobase_bueno']:0;
	                $plasticobase_malo=isset($_POST['plasticobase_malo'])?$_POST['plasticobase_malo']:0;                            
	            }                        


	            $plasticocapuchon=isset($_POST['plasticocapuchon'])?$_POST['plasticocapuchon']:false;
	            if($plasticocapuchon == 1){
	                $plasticocapuchon_bueno=isset($_POST['plasticocapuchon_bueno'])?$_POST['plasticocapuchon_bueno']:0;
	                $plasticocapuchon_malo=isset($_POST['plasticocapuchon_malo'])?$_POST['plasticocapuchon_malo']:0;                            
	            }                           


	            $pernos=isset($_POST['pernos'])?$_POST['pernos']:false;
	            if($pernos == 1){
	                $pernos_bueno=isset($_POST['pernos_bueno'])?$_POST['pernos_bueno']:0;
	                $pernos_malo=isset($_POST['pernos_malo'])?$_POST['pernos_malo']:0;                            
	            }   


	            $vinipel=isset($_POST['vinipel'])?$_POST['vinipel']:false;
	            if($vinipel == 1){
	                $vinipel_bueno=isset($_POST['vinipel_bueno'])?$_POST['vinipel_bueno']:0;
	                $vinipel_malo=isset($_POST['vinipel_malo'])?$_POST['vinipel_malo']:0;                            
	            }                          


	            $lazos=isset($_POST['lazos'])?$_POST['lazos']:false;
	            if($lazos == 1){
	                $lazos_bueno=isset($_POST['lazos_bueno'])?$_POST['lazos_bueno']:0;
	                $lazos_malo=isset($_POST['lazos_malo'])?$_POST['lazos_malo']:0;                            
	            }                         


	            $conectores=isset($_POST['conectores'])?$_POST['conectores']:false;
	            if($conectores == 1){
	                $conectores_bueno=isset($_POST['conectores_bueno'])?$_POST['conectores_bueno']:0;
	                $conectores_malo=isset($_POST['conectores_malo'])?$_POST['conectores_malo']:0;                            
	            }     


	            $maderos=isset($_POST['maderos'])?$_POST['maderos']:false;
	            if($maderos == 1){
	                $maderos_bueno=isset($_POST['maderos_bueno'])?$_POST['maderos_bueno']:0;
	                $maderos_malo=isset($_POST['maderos_malo'])?$_POST['maderos_malo']:0;                            
	            }    


	            $otros=isset($_POST['otros'])?$_POST['otros']:false;
	            if($otros == 1){
	                $otros_cuales=isset($_POST['otros_cuales'])?$_POST['otros_cuales']:null;                         
	            } 

                $vuelo = new Vuelo();
                $vuelo->setAerolinea($id_aerolinea);
                $vuelo->setNo_vuelo($no_vuelo);
                $vuelo->setMatricula($matricula);
                $vuelo->setFecha_vuelo($fecha_vuelo);
                $vuelo->setMallas($mallas);
                $vuelo->setMallas_bueno($mallas_bueno);
                $vuelo->setMallas_malo($mallas_malo);                            
                $vuelo->setCorreas($correas);
                $vuelo->setCorreas_bueno($correas_bueno);
                $vuelo->setCorreas_malo($correas_malo);
                $vuelo->setPlasticobase($plasticobase);
                $vuelo->setPlasticobase_bueno($plasticobase_bueno);
                $vuelo->setPlasticobase_malo($plasticobase_malo);
                $vuelo->setPlasticocapuchon($plasticocapuchon);
                $vuelo->setPlasticocapuchon_bueno($plasticocapuchon_bueno);
                $vuelo->setPlasticocapuchon_malo($plasticocapuchon_malo);
                $vuelo->setPernos($pernos);
                $vuelo->setPernos_bueno($pernos_bueno);
                $vuelo->setPernos_malo($pernos_malo);
                $vuelo->setVinipel($vinipel);
                $vuelo->setVinipel_bueno($vinipel_bueno);
                $vuelo->setVinipel_malo($vinipel_malo);
                $vuelo->setLazos($lazos);
                $vuelo->setLazos_bueno($lazos_bueno);
                $vuelo->setLazos_malo($lazos_malo);
                $vuelo->setConectores($conectores);
                $vuelo->setConectores_bueno($conectores_bueno);
                $vuelo->setConectores_malo($conectores_malo);
                $vuelo->setMaderos($maderos);
                $vuelo->setMaderos_bueno($maderos_bueno);
                $vuelo->setMaderos_malo($maderos_malo);
                $vuelo->setOtros($otros);
                $vuelo->setOtros_cuales($otros_cuales);
                $vuelo->setEstado(7);
                $vuelo = $vuelo->save();

                if($vuelo){  					
					$palletSearch = new Pallet();
					$palletSearch->setAerolinea($id_aerolinea);
					$palletSearch = $palletSearch->getAllActivesByAerolinea();										
					while($fila = $palletSearch->fetch_object()){
						if(isset($_POST["$fila->id_pallet"])){
							$cont++;
							$pallet = new Pallet();
							$pallet->setIdPallet($fila->id_pallet);
							$pallet->updateOne("estado",7);
							if($pallet){
								$pallet=$fila->id_pallet;
							}							

			                //Guardado Relacional
			                $vuelo_pallet = new VueloPallet();
			                $vuelo_pallet->setVuelo($vuelo);
			                $vuelo_pallet->setPallet($pallet);
			                $vuelo_pallet->save();

			                //Datos de Aerolínea con la que llega
			                $aerolinea = new Aerolinea();
			                $aerolinea->setIdAerolinea($id_aerolinea);
			                $aerolinea = $aerolinea->getOne();

			                //Guardado en Historial
			                guardarHistorialPallet($pallet,'Pallet Despachado con la Aerolínea<br>'.$aerolinea->nombre.'<br>Vuelo No '.$no_vuelo.'<br>Fecha '.$fecha_vuelo.'',$id_usuario);				
						}			
					}
	                //Guardado de Inventario de Artículos	                
	                $articulosAerlinea = new ArticulosAerolinea();
	                $articulosAerlinea->setAerolinea($id_aerolinea);
	                $preexistencia = $articulosAerlinea->getOneByAerolinea();
	                if ($preexistencia){
	                	$articulosAerlinea->setMallas($preexistencia->mallas - ($mallas_bueno + $mallas_malo));
	                	$articulosAerlinea->setCorreas($preexistencia->correas - ($correas_bueno + $correas_malo));
	                	$articulosAerlinea->setPlasticobase(($preexistencia->plasticobase - $plasticobase_bueno + $plasticobase_malo));
	                	$articulosAerlinea->setPlasticocapuchon($preexistencia->plasticocapuchon - ($plasticocapuchon_bueno + $plasticocapuchon_malo));
	                	$articulosAerlinea->setPernos(($preexistencia->pernos - $pernos_bueno + $pernos_malo));
	                	$articulosAerlinea->setVinipel($preexistencia->vinipel - ($vinipel_bueno + $vinipel_malo));
	                	$articulosAerlinea->setLazos(($preexistencia->lazos - $lazos_bueno + $lazos_malo));
	                	$articulosAerlinea->setConectores($preexistencia->conectores - ($conectores_bueno + $conectores_malo));
	                	$articulosAerlinea->setMaderos($preexistencia->maderos - ($maderos_bueno + $maderos_malo));
	                	$articulosAerlinea->setIdArticulosAerolinea($preexistencia->id_articulosaerolinea);
	                	$articulosAerlinea->updateOne();
	                }
							
					//Salida de Guardado Exitoso
					$buffer = 'url|*|'.base_url.'Central/inventarioGeneral';

				}			
		}else{
			$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No seleccionó ningún Pallet</h4>';
		}	        
	}
	else{
	    $buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se han recibido los datos</h4>';
	}
	echo $buffer;
}

function vueloTransferencia() {
	$id_usuario=$_SESSION['id_usuario'];   
    //Validacion de Datos recibidos
    $id_aerolinea=isset($_POST['id_aerolinea'])?$_POST['id_aerolinea']:false;
    $id_aerolineaDestino=isset($_POST['id_aerolineaDestino'])?$_POST['id_aerolineaDestino']:false;
    $no_vuelo=isset($_POST['no_vuelo'])?strtoupper($_POST['no_vuelo']):'N/A';
    $fecha_vuelo=isset($_POST['fecha_vuelo'])?strtoupper($_POST['fecha_vuelo']):false;  
    
	if($id_aerolinea && $no_vuelo && $id_usuario && $fecha_vuelo && $id_aerolineaDestino){
		$cont = 0;		
		$palletSearch = new Pallet();
		$palletSearch->setAerolinea($id_aerolinea);
		$palletSearch = $palletSearch->getAllActivesByAerolinea();		
		while($fila = $palletSearch->fetch_object()){
			if(isset($_POST["$fila->id_pallet"])){
				$cont++;				
			}			
		}
		
		if ($cont != 0){
	            $mallas=false;
	            $mallas_bueno=0;
	            $mallas_malo=0;
	            $correas=false;
	            $correas_bueno=0;
	            $correas_malo=0;
	            $plasticobase=false;
	            $plasticobase_bueno=0;
	            $plasticobase_malo=0;
	            $plasticocapuchon=false;
	            $plasticocapuchon_bueno=0;
	            $plasticocapuchon_malo=0;
	            $pernos=false;
	            $pernos_bueno=0;
	            $pernos_malo=0;
	            $vinipel=false;
	            $vinipel_bueno=0;
	            $vinipel_malo=0;
	            $lazos=false;
	            $lazos_bueno=0;
	            $lazos_malo=0;
	            $conectores=false;
	            $conectores_bueno=0;
	            $conectores_malo=0;
	            $maderos=false;
	            $maderos_bueno=0;
	            $maderos_malo=0;
	            $otros=false;
	            $otros_cuales=null;

	            $mallas=isset($_POST['mallas'])?$_POST['mallas']:false;
	            if($mallas == 1){
	                $mallas_bueno=isset($_POST['mallas_bueno'])?$_POST['mallas_bueno']:0;
	                $mallas_malo=isset($_POST['mallas_malo'])?$_POST['mallas_malo']:0;                            
	            }

	            $correas=isset($_POST['correas'])?$_POST['correas']:false;
	            if($correas == 1){
	                $correas_bueno=isset($_POST['correas_bueno'])?$_POST['correas_bueno']:0;
	                $correas_malo=isset($_POST['correas_malo'])?$_POST['correas_malo']:0;                            
	            }

	            $plasticobase=isset($_POST['plasticobase'])?$_POST['plasticobase']:false;
	            if($plasticobase == 1){
	                $plasticobase_bueno=isset($_POST['plasticobase_bueno'])?$_POST['plasticobase_bueno']:0;
	                $plasticobase_malo=isset($_POST['plasticobase_malo'])?$_POST['plasticobase_malo']:0;                            
	            }                        


	            $plasticocapuchon=isset($_POST['plasticocapuchon'])?$_POST['plasticocapuchon']:false;
	            if($plasticocapuchon == 1){
	                $plasticocapuchon_bueno=isset($_POST['plasticocapuchon_bueno'])?$_POST['plasticocapuchon_bueno']:0;
	                $plasticocapuchon_malo=isset($_POST['plasticocapuchon_malo'])?$_POST['plasticocapuchon_malo']:0;                            
	            }                           


	            $pernos=isset($_POST['pernos'])?$_POST['pernos']:false;
	            if($pernos == 1){
	                $pernos_bueno=isset($_POST['pernos_bueno'])?$_POST['pernos_bueno']:0;
	                $pernos_malo=isset($_POST['pernos_malo'])?$_POST['pernos_malo']:0;                            
	            }   

	            $vinipel=isset($_POST['vinipel'])?$_POST['vinipel']:false;
	            if($vinipel == 1){
	                $vinipel_bueno=isset($_POST['vinipel_bueno'])?$_POST['vinipel_bueno']:0;
	                $vinipel_malo=isset($_POST['vinipel_malo'])?$_POST['vinipel_malo']:0;                            
	            }                          


	            $lazos=isset($_POST['lazos'])?$_POST['lazos']:false;
	            if($lazos == 1){
	                $lazos_bueno=isset($_POST['lazos_bueno'])?$_POST['lazos_bueno']:0;
	                $lazos_malo=isset($_POST['lazos_malo'])?$_POST['lazos_malo']:0;                            
	            }                         


	            $conectores=isset($_POST['conectores'])?$_POST['conectores']:false;
	            if($conectores == 1){
	                $conectores_bueno=isset($_POST['conectores_bueno'])?$_POST['conectores_bueno']:0;
	                $conectores_malo=isset($_POST['conectores_malo'])?$_POST['conectores_malo']:0;                            
	            }     


	            $maderos=isset($_POST['maderos'])?$_POST['maderos']:false;
	            if($maderos == 1){
	                $maderos_bueno=isset($_POST['maderos_bueno'])?$_POST['maderos_bueno']:0;
	                $maderos_malo=isset($_POST['maderos_malo'])?$_POST['maderos_malo']:0;                            
	            }    


	            $otros=isset($_POST['otros'])?$_POST['otros']:false;
	            if($otros == 1){
	                $otros_cuales=isset($_POST['otros_cuales'])?$_POST['otros_cuales']:null;                         
	            } 

                $vuelo = new Vuelo();
                $vuelo->setAerolinea($id_aerolineaDestino);
                $vuelo->setNo_vuelo($no_vuelo);
                $vuelo->setFecha_vuelo($fecha_vuelo);
                $vuelo->setMallas($mallas);
                $vuelo->setMallas_bueno($mallas_bueno);
                $vuelo->setMallas_malo($mallas_malo);                            
                $vuelo->setCorreas($correas);
                $vuelo->setCorreas_bueno($correas_bueno);
                $vuelo->setCorreas_malo($correas_malo);
                $vuelo->setPlasticobase($plasticobase);
                $vuelo->setPlasticobase_bueno($plasticobase_bueno);
                $vuelo->setPlasticobase_malo($plasticobase_malo);
                $vuelo->setPlasticocapuchon($plasticocapuchon);
                $vuelo->setPlasticocapuchon_bueno($plasticocapuchon_bueno);
                $vuelo->setPlasticocapuchon_malo($plasticocapuchon_malo);
                $vuelo->setPernos($pernos);
                $vuelo->setPernos_bueno($pernos_bueno);
                $vuelo->setPernos_malo($pernos_malo);
                $vuelo->setVinipel($vinipel);
                $vuelo->setVinipel_bueno($vinipel_bueno);
                $vuelo->setVinipel_malo($vinipel_malo);
                $vuelo->setLazos($lazos);
                $vuelo->setLazos_bueno($lazos_bueno);
                $vuelo->setLazos_malo($lazos_malo);
                $vuelo->setConectores($conectores);
                $vuelo->setConectores_bueno($conectores_bueno);
                $vuelo->setConectores_malo($conectores_malo);
                $vuelo->setMaderos($maderos);
                $vuelo->setMaderos_bueno($maderos_bueno);
                $vuelo->setMaderos_malo($maderos_malo);
                $vuelo->setOtros($otros);
                $vuelo->setOtros_cuales($otros_cuales);
                $vuelo->setEstado(8);
                $vuelo = $vuelo->save();

                if($vuelo){  					
					$palletSearch = new Pallet();
					$palletSearch->setAerolinea($id_aerolinea);
					$palletSearch = $palletSearch->getAllActivesByAerolinea();										
					while($fila = $palletSearch->fetch_object()){
						if(isset($_POST["$fila->id_pallet"])){							
							$pallet = new Pallet();
							$pallet->setIdPallet($fila->id_pallet);
							$pallet->updateOne("estado",5);
							$pallet->updateOne("aerolinea",$id_aerolineaDestino);							
							if($pallet){
								$pallet=$fila->id_pallet;
							}							

			                //Guardado Relacional
			                $vuelo_pallet = new VueloPallet();
			                $vuelo_pallet->setVuelo($vuelo);
			                $vuelo_pallet->setPallet($pallet);
			                $vuelo_pallet->save();

			                //Datos de Aerolínea con la que llega
			                $aerolinea = new Aerolinea();
			                $aerolinea->setIdAerolinea($id_aerolineaDestino);
			                $aerolinea = $aerolinea->getOne();

			                //Guardado en Historial
			                guardarHistorialPallet($pallet,'Pallet Transferido a la Aerolínea<br>'.$aerolinea->nombre.'<br>Vuelo No '.$no_vuelo.'<br>Fecha '.$fecha_vuelo.'',$id_usuario);						
						}			
					}
					//Salida de Guardado Exitoso
					$buffer = 'url|*|'.base_url.'Central/inventarioGeneral';
				}
			
		}else{
			$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No seleccionó ningún Pallet</h4>';
		}	        
	}
	else{
	    $buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se han recibido los datos</h4>';
	}
	echo $buffer;
}

function buscarPallet(){
	$buffer = "";
	$coincidencia=isset($_POST['coincidencia'])?$_POST['txtcoincidencia']:false;
	$rango=isset($_POST['rango'])?$_POST['rango']:false;

    //Carga datos del Usuario
    $id_usuario=$_SESSION['id_usuario'];
    $usuario= new Usuario();  
    $usuario->setIdUsuario($id_usuario);                       
    $usuario = $usuario->getOne();

	if($coincidencia){		
		$pallet = new Pallet();
		$pallet->setNumero($coincidencia);
		$pallet = $pallet->getCoincidencia();
		$buffer .= 'tupla|*|<table align="center">
							  <tr>
							    <td class="celda_tabla_principal"><div class="letreros_tabla">...</div></td>
							    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Pallet</div></td>
							    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolínea</div></td>
							  </tr>							  
	  				';	  	   	    
		while ($fila = $pallet->fetch_object()) {
			//Seleccion de permisos de UsuarioXAerolinea para mostrar datos
			if ($usuario->id_aerolinea == "*" || $usuario->id_aerolinea == $fila->aerolinea)
			{	
				$aerolinea = new Aerolinea();
				$aerolinea->setIdAerolinea($fila->aerolinea);
				$aerolinea = $aerolinea->getOne();			
				$buffer .= '                                            
								<tr>
									<td class="celda_tabla_principal celda_boton">
										<form method="POST" action="'.base_url.'Central/consultaPallet">
											<input type="hidden" name="pallet" value="'.$fila->id_pallet.'"> 
											<button type="submit" title="Información"><img src="'.base_Father.'imagenes/buscar-act.png"/></button>
										</form>
									</td>
									<td class="celda_tabla_principal celda_boton">'.$fila->numero.'</td>
									<td class="celda_tabla_principal celda_boton">'.$aerolinea->nombre.'</td>
								</tr>	
							
							';
			}
		}
		$buffer .= '</table>';
	}else if ($rango){
		$rangoini=$_POST['rangoini'];
		$rangofin=$_POST['rangofin'];
		$vuelo = new Vuelo();
		$vuelo = $vuelo->getRangoFecha($rangoini,$rangofin);
		$buffer .= 'tupla|*|<table align="center">
							  <tr>
							    <td class="celda_tabla_principal"><div class="letreros_tabla">...</div></td>
							    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Pallet</div></td>
							    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolínea</div></td>
							  </tr>							  
	  				';			
		//Se consultan los vuelos recibidos en ese rango de fecha
		while ($filaVuelo = $vuelo->fetch_object()) {
			//Seleccion de permisos de UsuarioXAerolinea para mostrar datos
			if ($usuario->id_aerolinea == "*" || $usuario->id_aerolinea == $filaVuelo->aerolinea)
			{
				$fecha_vuelo = $filaVuelo->fecha_vuelo;
				$vuelo_pallet = new VueloPallet();
				$vuelo_pallet->setVuelo($filaVuelo->id_vuelo);
				$listaPallets = $vuelo_pallet->getPalletVuelo();
				//Se consultas los pallets asociados a ese vuelo
				while ($filaVueloPallet = $listaPallets->fetch_object()) {				
					//Se identifica el vuelo
					$esteVuelo = new Vuelo();
					$esteVuelo->setIdVuelo($filaVueloPallet->vuelo);				
					$esteVuelo = $esteVuelo->getOne();

					//Se identifica aerolinea
					$aerolinea = new Aerolinea();
					$aerolinea->setIdAerolinea($esteVuelo->aerolinea);
					$aerolinea = $aerolinea->getOne();	

					//Se identifica el número del pallet
					$pallet = new Pallet();
					$pallet->setIdPallet($filaVueloPallet->pallet);
					$pallet = $pallet->getOne();
					
					$buffer .= '	                        	                            
									<tr>
										<td class="celda_tabla_principal celda_boton">
											<form method="POST" action="'.base_url.'Central/consultaPallet">
												<input type="hidden" name="pallet" value="'.$filaVueloPallet->pallet.'"> 
												<button type="submit" title="Información"><img src="'.base_Father.'imagenes/buscar-act.png"/></button>
											</form>
										</td>
										<td class="celda_tabla_principal celda_boton">'.$pallet->numero.'</td>
										<td class="celda_tabla_principal celda_boton">'.$aerolinea->nombre.'</td>
									</tr>	
								
								';
				}						
			}
		}
		$buffer .= '</table>';
	}else{
		$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se han recibido los datos</h4>';
	}	
	echo $buffer;
}

function buscarVuelo(){
	$buffer = "";
	$coincidencia=isset($_POST['coincidencia'])?$_POST['txtcoincidencia']:false;
	$rango=isset($_POST['rango'])?$_POST['rango']:false;

    //Carga datos del Usuario
    $id_usuario=$_SESSION['id_usuario'];
    $usuario= new Usuario();  
    $usuario->setIdUsuario($id_usuario);                       
    $usuario = $usuario->getOne();

	if($coincidencia || $rango){
		if($coincidencia){		
			$vuelo = new Vuelo();
			$vuelo->setNo_vuelo($coincidencia);
			$vuelo = $vuelo->getCoincidencia();
		}else if ($rango){
			$rangoini=$_POST['rangoini'];
			$rangofin=$_POST['rangofin'];
			$vuelo = new Vuelo();
			$vuelo = $vuelo->getRangoFecha($rangoini,$rangofin);
		}
		$buffer .= 'tupla|*|<table align="center">
							  <tr>							    
							    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Vuelo</div></td>
							    <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
							    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolínea</div></td>							    
							    <td class="celda_tabla_principal"><div class="letreros_tabla">...</div></td>
							  </tr>							  
	  				';	  	   	    

		while ($fila = $vuelo->fetch_object()) {
			//Seleccion de permisos de UsuarioXAerolinea para mostrar datos
			if ($usuario->id_aerolinea == "*" || $usuario->id_aerolinea == $fila->aerolinea)
			{
				$aerolinea = new Aerolinea();
				$aerolinea->setIdAerolinea($fila->aerolinea);
				$aerolinea = $aerolinea->getOne();
				$buffer .= '                                            
								<tr>
									<td class="celda_tabla_principal celda_boton">'.$fila->no_vuelo.'</td>
									<td class="celda_tabla_principal celda_boton">'.$fila->fecha_vuelo.'</td>
									<td class="celda_tabla_principal celda_boton">'.$aerolinea->nombre.'</td>
									<td class="celda_tabla_principal celda_boton">
										<form method="POST" action="'.base_url.'Central/consultaVuelo">
											<input type="hidden" name="id_vuelo" value="'.$fila->id_vuelo.'"> 
											<button type="submit" title="Información"><img src="'.base_Father.'imagenes/buscar-act.png"/></button>
										</form>
									</td>								
								</tr>	
							
							';
			}
		}
		$buffer .= '</table>';
	}else{
		$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se han recibido los datos</h4>';
	}	
	echo $buffer;
}

function identificarVueloUCM(){
	$buffer = "";
	$id_aerolinea=isset($_POST['id_aerolinea'])?$_POST['id_aerolinea']:false;
	$rangoini=isset($_POST['rangoini'])?$_POST['rangoini']:false;
	$rangofin=isset($_POST['rangofin'])?$_POST['rangofin']:false;	
	$tipoReporte=isset($_POST['tipoReporte'])?$_POST['tipoReporte']:false;
	
	if($id_aerolinea && $rangoini && $rangofin && $tipoReporte){		
		
		if($tipoReporte == "recibido"){
			$estado=6;
			$txtUCM="IN";		
		}
		else{
			$estado=7;
			$txtUCM="OUT";		
		}		

		$vuelo = new Vuelo();
		$vuelo->setEstado($estado);
		$vuelo->setAerolinea($id_aerolinea);
		$vuelo = $vuelo->getVuelosRecibidosRangoFecha($rangoini,$rangofin);
		$buffer .= 'tupla|*|
							<table align="center">
							  <tr>
							    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Vuelo</div></td>
							    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
							    <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha Vuelo</div></td>
							    <td class="celda_tabla_principal"><div class="letreros_tabla">Acción</div></td>
							  </tr>							  
	  				';			
		//Se consultan los vuelos recibidos en ese rango de fecha
		while ($filaVuelo = $vuelo->fetch_object()) {
			$fecha_vuelo = $filaVuelo->fecha_vuelo;
			
			//Se identifica aerolinea
			$aerolinea = new Aerolinea();
			$aerolinea->setIdAerolinea($filaVuelo->aerolinea);			
			$aerolinea = $aerolinea->getOne();	

			$buffer .= '	                        	                            
							<tr>
								<td class="celda_tabla_principal celda_boton">'.$filaVuelo->no_vuelo.'</td>
								<td class="celda_tabla_principal celda_boton">'.$aerolinea->nombre.'</td>
								<td class="celda_tabla_principal celda_boton">'.$fecha_vuelo.'</td>
								<td class="celda_tabla_principal celda_boton">
									<button type="button" onclick="crearUCM('.$filaVuelo->id_vuelo.')">
										<img src="'.base_Father.'imagenes/aceptar-act.png"/>
									</button>
								</td>										
							</tr>							
						';																
		}
		$buffer .= '</table>';
	}else{
		$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se han recibido los datos completos</h4>';
	}	
	echo $buffer;	
}

function crearUCM(){
	$buffer = "";
	$id_vuelo=isset($_REQUEST['id'])?$_REQUEST['id']:false;
	if($id_vuelo){
		$vuelo = new Vuelo();
		$vuelo->setIdVuelo($id_vuelo);
		$vuelo = $vuelo->getOne();
		$tipoVuelo = $vuelo->estado;
		
		switch ($tipoVuelo) {
			case 6:
				$txtUCM = "IN";
				break;

			case 7:
				$txtUCM = "OUT";
				break;
			
			default:
				$txtUCM = "N/A";
				break;
		}

		//Formateo de Fecha del tipo del informe		
		//setlocale(LC_TIME, "es_CO");	
		setlocale(LC_ALL, 'es_CO');	
		$fechaUCM = date("d", strtotime($vuelo->fecha_vuelo)) . strtoupper(date("M", strtotime($vuelo->fecha_vuelo)));

		$buffer .= 'UCM-'.$txtUCM.'<br>';	
		$buffer .= $vuelo->no_vuelo.'/'.$fechaUCM.'.'.$vuelo->matricula.'.BOG<br><br>';			
		$buffer .= $txtUCM.'<br>';	

		//Se consultan Pallets Pertenecientes al vuelo		
		$vuelo_pallet = new VueloPallet();
		$vuelo_pallet->setVuelo($id_vuelo);
		$listaPallets = $vuelo_pallet->getPalletVuelo();
		//Se consultas los pallets asociados a ese vuelo
		while ($filaVueloPallet = $listaPallets->fetch_object()) {
					//Se identifica el número del pallet
					$pallet = new Pallet();
					$pallet->setIdPallet($filaVueloPallet->pallet);
					$pallet = $pallet->getOne();
					$palletsOntenidos[] = $pallet->numero;					
		}
		//Se organizan los pallet por orden ascendete y se formatea la presentación
		sort($palletsOntenidos);
		$prefijoBuffer ="";
		$contLinea = 0;
		foreach ($palletsOntenidos as $key => $val) {
			$prefijo = str_split($val, 3);
			if($prefijoBuffer == $prefijo[0]){
		    	if($contLinea < 6){
			    	$buffer .= "." . $val;				    	
			    	$contLinea++;
			    	$prefijoBuffer = $prefijo[0];
		    	}
		    	else{
			    	$buffer .= ".T".$contLinea;
			    	$buffer .= "<br>";
			    	$buffer .= "." . $val;
			    	$contLinea=1;
			    	$prefijoBuffer = $prefijo[0];
		    	}			    				    	
		    }else{
		    	$buffer .= $prefijoBuffer != "" ? ".T".$contLinea : ""; //Evalua la primera vez que se entra al ciclo
		    	$buffer .= "<br>";		    				    	
		    	$buffer .= "." . $val;
		    	$contLinea=1;
		    	$prefijoBuffer = $prefijo[0];			    	
		    }							
		}
		$buffer .= ".T".$contLinea;															
	}else{
		$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se han recibido los datos completos</h4>';
	}	
	echo $buffer;	
}

function crearSCM(){	
	$id_aerolinea=isset($_REQUEST['id'])?$_REQUEST['id']:false;
	if($id_aerolinea){
		$buffer = "SCM<br>BOG";
		//Formateo de Fecha del tipo del informe
		//setlocale(LC_ALL, 'es_CO');
		$buffer .= strtoupper(strftime("%d%b"));
		$buffer .= "/001<br>";
		//Se consultan Pallets que están en bodega Pertenecientes a la aerolinea
		$pallets = new Pallet();
		$pallets->setAerolinea($id_aerolinea);
		$pallets = $pallets->getAllActivesByAerolinea();											
		while($pallet = $pallets->fetch_object()){
			$palletsOntenidos[] = $pallet->numero;	
		}
		//Se organizan los pallet por orden ascendete y se formatea la presentación
		if(isset($palletsOntenidos)){
			sort($palletsOntenidos);
			$prefijoBuffer ="";
			$contLinea = 0;
			foreach ($palletsOntenidos as $key => $val) {
				$prefijo = str_split($val, 3);
				if($prefijoBuffer == $prefijo[0]){
			    	if($contLinea < 6){
				    	$buffer .= "/" . $val;				    	
				    	$contLinea++;
				    	$prefijoBuffer = $prefijo[0];
			    	}
			    	else{
				    	$buffer .= ".T".$contLinea;
				    	$buffer .= "<br>";
				    	$buffer .= "." . $val;
				    	$contLinea=1;
				    	$prefijoBuffer = $prefijo[0];
			    	}			    				    	
			    }else{
			    	$buffer .= $prefijoBuffer != "" ? ".T".$contLinea : ""; //Evalua la primera vez que se entra al ciclo
			    	$buffer .= "<br>";		    				    	
			    	$buffer .= "." . $val;
			    	$contLinea=1;
			    	$prefijoBuffer = $prefijo[0];			    	
			    }							
			}
			$buffer .= ".T".$contLinea;			
		}else{
			$buffer = 'error|*|<h2><span style="color: orange">Atención:</h2></span><h4>No existen registros asociados</h4>';
		}								
	}else{
		$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se han recibido los datos completos</h4>';
	}	
	echo $buffer;	
}

function totalesArticulosAerolinea(){	
	if(isset($_GET['id'])){
		$id_aerolinea=$_GET['id'];	
		$articulosAerlinea = new ArticulosAerolinea();
		$articulosAerlinea->setAerolinea($id_aerolinea);
		$articulosAerlinea = $articulosAerlinea->getOneByAerolinea();
        if ($articulosAerlinea){
        	$buffer = 'tupla';
        	$buffer .= '|*|'.$articulosAerlinea->mallas;
        	$buffer .= '|*|'.$articulosAerlinea->correas;
        	$buffer .= '|*|'.$articulosAerlinea->plasticobase;
        	$buffer .= '|*|'.$articulosAerlinea->plasticocapuchon;
        	$buffer .= '|*|'.$articulosAerlinea->pernos;
        	$buffer .= '|*|'.$articulosAerlinea->vinipel;
        	$buffer .= '|*|'.$articulosAerlinea->lazos;
        	$buffer .= '|*|'.$articulosAerlinea->conectores;
        	$buffer .= '|*|'.$articulosAerlinea->maderos;        	
        }
        else{
        	$buffer = 'error|*|<h2><span style="color: orange">Alerta:</h2></span><h4>No se encontraron Elementos para Procesar</h4>';	
        }
	}
	else{
		$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se han recibido los datos</h4>';
	}
	echo $buffer;
}

function activarPallet(){	
	$id_pallet=isset($_REQUEST['id'])?$_REQUEST['id']:false;	
	$id_usuario=$_SESSION['id_usuario'];  
	$buffer = "";
	if($id_pallet && $id_usuario){
		$pallet = new Pallet();
		$pallet->setIdPallet($id_pallet);  
		$estePallet=$pallet->getOne();  		
		switch ($estePallet->estado) {
			case 5: //En Bodega
				$update=$pallet->updateOne("estado",2);
				if($update){
					guardarHistorialPallet($id_pallet,'Cambia a estado Inactivo',$id_usuario);					
					$buffer .= 'tupla|*|Inactivo|*|Registro actualizado con Exito';		
				}
			break;

			case 2: //Inactivo
				$update=$pallet->updateOne("estado",5);
				if($update){
					guardarHistorialPallet($id_pallet,'Cambia a estado En Bodega',$id_usuario);					
					$buffer .= 'tupla|*|En Bodega|*|Registro actualizado con Exito';		
				}
			break;				

			default:
				$buffer .= 'error|*|<h2><span style="color: orange">Atención:</h2></span><h4>No puede cambiar el estado del Pallet</h4>';
			break;
		}		
	}else{
		$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se ha recibido los datos completos</h4>';
	}
	echo $buffer;	

}

function guardarModificarPallet(){
    //Validacion de Datos recibidos
    $buffer = "";
    $id_usuario=$_SESSION['id_usuario'];    
    $id_pallet=isset($_POST['id_pallet'])?strtoupper($_POST['id_pallet']):false;
    if($id_pallet && $id_usuario){		
    	//Captura de datos del formulario de actualización
    	$numero=strtoupper($_POST['numero']);
    	$aerolineaForm=$_POST['aerolinea'];
       	$ubicacionForm=$_POST['ubicacion'];
       	$observaciones=$_POST['observaciones'];
       	$contenedor_activo=$_POST['contenedor_activo'];
       	$buen_estado=$_POST['buen_estado'];

		//Consulta del Pallet        	        	
		$pallet = new Pallet();
		$pallet->setIdPallet($id_pallet); 
		$palletActual = $pallet->getOne();

		//Actualizacion de datos si son  modificados
		if ($palletActual->numero != $numero){
			$updatePallet = $pallet->updateOne("numero","'$numero'");
			guardarHistorialPallet($id_pallet,'No. de pallet modificado:<br>De:'.$palletActual->numero.'<br>A:'.$numero,$id_usuario);
		}

		//Actualizacion de datos si son modificados
		if ($palletActual->aerolinea != $aerolineaForm){
			$updatePallet = $pallet->updateOne("aerolinea",$aerolineaForm);			
			$aerolinea = new Aerolinea();			
			$aerolinea->setIdAerolinea($palletActual->aerolinea);
			$aerolineaActual = $aerolinea->getOne();
			$aerolinea->setIdAerolinea($aerolineaForm);
			$aerolineaNueva = $aerolinea->getOne();
			guardarHistorialPallet($id_pallet,'Aerolínea modificada:<br>De:'.$aerolineaActual->nombre.'<br>A:'.$aerolineaNueva->nombre,$id_usuario);
		}

		//Actualizacion de datos si son modificados
		if ($palletActual->ubicacion != $ubicacionForm){
			$updatePallet = $pallet->updateOne("ubicacion",$ubicacionForm);			
			$ubicacion = new Ubicacion();			
			$ubicacion->setIdUbicacion($palletActual->ubicacion);
			$ubicacionActual = $ubicacion->getOne();
			$ubicacion->setIdUbicacion($ubicacionForm);
			$ubicacionNueva = $ubicacion->getOne();
			guardarHistorialPallet($id_pallet,'Ubicación modificada:<br>De:'.$ubicacionActual->nombre.'<br>A:'.$ubicacionNueva->nombre,$id_usuario);
		}

		//Actualizacion de datos si son  modificados
		if ($palletActual->observaciones != $observaciones){
			$updatePallet = $pallet->updateOne("observaciones","'$observaciones'");
			guardarHistorialPallet($id_pallet,'Observaciones modificadas:<br>De:'.$palletActual->observaciones.'<br>A:'.$observaciones,$id_usuario);
		}

		//Actualizacion de datos si son  modificados
		if ($palletActual->contenedor_activo != $contenedor_activo){
			$updatePallet = $pallet->updateOne("contenedor_activo",$contenedor_activo);
			if($contenedor_activo == 1){
				$datoActual="No";
				$datoNuevo="Si";
			}
			else{
				$datoActual="Si";
				$datoNuevo="No";
			}
			guardarHistorialPallet($id_pallet,'¿Es Contenedor Activo? modificado:<br>De:'.$datoActual.'<br>A:'.$datoNuevo,$id_usuario);
		}

		//Actualizacion de datos si son  modificados
		if ($palletActual->buen_estado != $buen_estado){
			$updatePallet = $pallet->updateOne("buen_estado",$buen_estado);
			if($buen_estado == 1){
				$datoActual="No";
				$datoNuevo="Si";
			}
			else{
				$datoActual="Si";
				$datoNuevo="No";
			}
			guardarHistorialPallet($id_pallet,'¿Está en buen estado? modificado:<br>De:'.$datoActual.'<br>A:'.$datoNuevo,$id_usuario);
		}

		$buffer .= 'tupla|*|Datos Actualizados Correctamente'; 
    }else{
    	$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se ha recibido el dato</h4>';
    }
	echo $buffer;

}

function guardarAjusteManualElementos(){               
        $saveUpdate = false;
        //Carga el inventario de articulos de las aerolíneas
        $articulosAerolinea = new ArticulosAerolinea();
        $articulosAerolinea = $articulosAerolinea->getAll();
        while ($fila = $articulosAerolinea->fetch_object()) {        	
        		$mallas=isset($_POST['mallas_'.$fila->aerolinea])?$_POST['mallas_'.$fila->aerolinea]:0;
        		$correas=isset($_POST['correas_'.$fila->aerolinea])?$_POST['correas_'.$fila->aerolinea]:0;
        		$plasticobase=isset($_POST['plasticobase_'.$fila->aerolinea])?$_POST['plasticobase_'.$fila->aerolinea]:0;
        		$plasticocapuchon=isset($_POST['plasticocapuchon_'.$fila->aerolinea])?$_POST['plasticocapuchon_'.$fila->aerolinea]:0;
        		$pernos=isset($_POST['pernos_'.$fila->aerolinea])?$_POST['pernos_'.$fila->aerolinea]:0;
        		$vinipel=isset($_POST['vinipel_'.$fila->aerolinea])?$_POST['vinipel_'.$fila->aerolinea]:0;
        		$lazos=isset($_POST['lazos_'.$fila->aerolinea])?$_POST['lazos_'.$fila->aerolinea]:0;
        		$conectores=isset($_POST['conectores_'.$fila->aerolinea])?$_POST['conectores_'.$fila->aerolinea]:0;
        		$maderos=isset($_POST['maderos_'.$fila->aerolinea])?$_POST['maderos_'.$fila->aerolinea]:0;

        		//Actualiza los datos de los items de la aerolínea
        		$articuloActualizar = new ArticulosAerolinea();
        		$articuloActualizar->setMallas($mallas);
        		$articuloActualizar->setCorreas($correas);
        		$articuloActualizar->setPlasticobase($plasticobase);
        		$articuloActualizar->setPlasticocapuchon($plasticocapuchon);
        		$articuloActualizar->setPernos($pernos);
        		$articuloActualizar->setVinipel($vinipel);
        		$articuloActualizar->setLazos($lazos);
        		$articuloActualizar->setConectores($conectores);
        		$articuloActualizar->setMaderos($maderos);
        		$articuloActualizar->setAerolinea($fila->aerolinea);
        		$saveUpdate=$articuloActualizar->updateAllByAerolinea();       		
        }
        
        if($saveUpdate){
			$buffer = 'tupla|*|Datos Actualizados Correctamente'; 
	    }else{
	    	$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>Error en la actualización de datos</h4>';
	    }	    
        echo $buffer;
}

function reportePallets(){
	$buffer = "";	
	$rangoini=isset($_POST['rangoini'])?$_POST['rangoini']:false;
	$rangofin=isset($_POST['rangofin'])?$_POST['rangofin']:false;	
	$tipoReporte=isset($_POST['tipoReporte'])?$_POST['tipoReporte']:false;

    //Carga datos del Usuario
    $id_usuario=$_SESSION['id_usuario'];
    $usuario= new Usuario();  
    $usuario->setIdUsuario($id_usuario);                       
    $usuario = $usuario->getOne();	
	
	if($rangoini && $rangofin && $tipoReporte){				
		$buffer .= 'tupla|*|
							<table align="center">
							  <tr>							    
							    <td class="celda_tabla_principal"><div class="letreros_tabla">Pallet</div></td>
							    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
							    <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha Vuelo</div></td>
							    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Vuelo</div></td>
							    <td class="celda_tabla_principal"><div class="letreros_tabla">Tipo</div></td>							    
							  </tr>							  
	  				';
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
		$buffer .= '</table>';
	}else{
		$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se han recibido los datos completos</h4>';
	}	
	echo $buffer;	
}


function guardarNovedadesMultiples() {
	$id_usuario=$_SESSION['id_usuario']; 
	$cantidadRegistros=isset($_POST['cantidadRegistros'])?$_POST['cantidadRegistros']:false;	
	//obtener los datos, hacer el recorrido, guardar la novedad, redireccionar a inventario
	$buffer = 'url|*|'.base_url.'Central/inventarioGeneral';
	if($cantidadRegistros && $id_usuario){
		for ($i = 1; $i <= $cantidadRegistros; $i++) { 
			$id_pallet=$_POST['id_pallet_'.$i];
			$descripcion=$_POST['descripcion_'.$i];
			$palletNovedad = new PalletNovedad();
			$palletNovedad->setPallet($id_pallet);  
			$palletNovedad->setDescripcion($descripcion);  
			$palletNovedad->setUsuario($id_usuario);  
			$palletNovedad = $palletNovedad->save();			
		    if ($palletNovedad > 0){                        	       
		        //Guardado en Historial
		        guardarHistorialPallet($id_pallet,'Novedad Agregada: '.$descripcion,$id_usuario);  
		        unset($_SESSION['pallet']);    	        
		    }
		    else{                        
		        $buffer .= 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se ha guardado el registro</h4>';
		    }			
		}		
	}else{
		$buffer = 'error|*|<h2><span style="color: red">Error:</h2></span><h4>No se ha recibido el dato</h4>';
	}
	echo $buffer;
}

//Funciones Recurrentes------------------------------------------------------
function listarNovedadesPallet($id_pallet){	
	$buffer = "tupla";
	//Consulta del Pallet        	        	
	$pallet = new Pallet();
	$pallet->setIdPallet($id_pallet);        	        	
	$pallet = $pallet->getOne();
	$numero=$pallet->numero;
	$buffer .= '|*|'.$numero.'';

	//Consulta de las novedades del pallet
	$palletNovedad = new PalletNovedad();
	$palletNovedad->setPallet($id_pallet);
	$palletNovedad = $palletNovedad->getAllByPallet();	
	$buffer .= '|*|';
	$buffer .= '<table class="table table-striped">
					<thead>
					  <tr>						    
					    <th>Descripcion</th>
					    <th>Fecha/Usuario</th>
					  </tr>
					</thead>
					<tbody>';

	while ($fila = $palletNovedad->fetch_object()){
		$usuario = new Usuario();
		$usuario->setIdUsuario($fila->usuario);
		$usuario = $usuario->getOne();

		$buffer .= '<tr>			        
				        <td>'.$fila->descripcion.'</td>
				        <td>'.$fila->fechahora_creacion.'<br>'.$usuario->nombre.'</td>
				      </tr>';	
	}
	$buffer .= '</tbody></table>';
	echo $buffer;
}

function guardarHistorialPallet($pallet,$descripcion,$id_usuario){
    //Guardado Historial
    $historial = new Historial();
    $historial->setPallet($pallet);
    $historial->setDescripcion($descripcion);
    $historial->setUsuario($id_usuario);
    $historial->setEstado(1);
    $historial=$historial->save();
}
//Fin Funciones Recurrentes------------------------------------------------------


?>
