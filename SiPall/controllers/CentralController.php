<?php
require_once 'models/Usuario.php';
require_once 'models/Aerolinea.php';
require_once 'models/Ubicacion.php';
require_once 'models/Vuelo.php';
require_once 'models/Pallet.php';
require_once 'models/VueloPallet.php';
require_once 'models/Historial.php';
require_once 'models/Estado.php';
require_once 'models/ArticulosAerolinea.php';
require_once 'helpers/Utils.php';


class CentralController {

    public function start() {
        require_once 'views/layouts/layout1/start.php';
    }

    public function vueloNuevo() {
        //Cargue de Privilegios del modulo
        if(!Utils::contentPrivilegios(156)){
            require_once 'views/layouts/layout2/403.php';
            die();    
        }     
        //Fin Cargue de Privilegios del modulo


        //Carga datos del Usuario
        $id_usuario=$_SESSION['id_usuario'];
        $usuario= new Usuario();  
        $usuario->setIdUsuario($id_usuario);                       
        $usuario = $usuario->getOne();

        //Seleccion de permisos de UsuarioXAerolinea
        $aerolinea = new Aerolinea();
    	$aerolineaUsuario = $usuario->id_aerolinea;
    	if($aerolineaUsuario == "*"){    		
    		$aerolinea=$aerolinea->getAllActives();
    	}
    	else{
    		$aerolinea->setIdAerolinea($aerolineaUsuario); 
    		$aerolinea=$aerolinea->getFullOne();
    	}

        //Cargue de Ubicaciones
        $ubicacion = new Ubicacion();
        $ubicacion = $ubicacion->getAllActives();

        //Cargue de la Vista
        require_once 'views/layouts/layout1/vueloNuevo.php';
    }

    public function vueloDespacho() {
        //Cargue de Privilegios del modulo
        if(!Utils::contentPrivilegios(164)){
            require_once 'views/layouts/layout2/403.php';
            die();    
        }     
        //Fin Cargue de Privilegios del modulo        
        $id_usuario=$_SESSION['id_usuario'];
        $usuario= new Usuario();  
        $usuario->setIdUsuario($id_usuario);                       
        $usuario = $usuario->getOne();

        //Seleccion de permisos de UsuarioXAerolinea
        $aerolinea = new Aerolinea();
        $aerolineaUsuario = $usuario->id_aerolinea;
        if($aerolineaUsuario == "*"){           
            $aerolinea=$aerolinea->getAllActives();
        }
        else{
            $aerolinea->setIdAerolinea($aerolineaUsuario); 
            $aerolinea=$aerolinea->getFullOne();
        }

        //Cargue de Ubicaciones
        $ubicacion = new Ubicacion();
        $ubicacion = $ubicacion->getAllActives();

        //Cargue de la Vista
        require_once 'views/layouts/layout1/vueloDespacho.php';
    }

    public function vueloTransferencia() {   
        //Cargue de Privilegios del modulo
        if(!Utils::contentPrivilegios(165)){
            require_once 'views/layouts/layout2/403.php';
            die();    
        }     
        //Fin Cargue de Privilegios del modulo           
        $id_usuario=$_SESSION['id_usuario'];
        $usuario= new Usuario();  
        $usuario->setIdUsuario($id_usuario);                       
        $usuario = $usuario->getOne();

        //Aerolinea Origen
        //Seleccion de permisos de UsuarioXAerolinea
        $aerolinea = new Aerolinea();
        $aerolineaUsuario = $usuario->id_aerolinea;
        if($aerolineaUsuario == "*"){           
            $aerolinea=$aerolinea->getAllActives();
        }
        else{
            $aerolinea->setIdAerolinea($aerolineaUsuario); 
            $aerolinea=$aerolinea->getFullOne();
        }

        //Aerolinea Destino
        $aerolineaDestino = new Aerolinea();
        $aerolineaDestino = $aerolineaDestino->getAllActives();

        //Cargue de Ubicaciones
        $ubicacion = new Ubicacion();
        $ubicacion = $ubicacion->getAllActives();

        //Cargue de la Vista
        require_once 'views/layouts/layout1/vueloTransferencia.php';
    }

    public function inventarioGeneral() {    
        set_time_limit (0); // Elimina la restriccion en el tiempo limite para la ejecicion del modulo.
        //Cargue de Privilegios del modulo
        if(!Utils::contentPrivilegios(157)){
            require_once 'views/layouts/layout2/403.php';
            die();    
        }
        $privilegioNovedad = Utils::contentPrivilegios(160) ? '' : 'disabled="disabled"';
        $privilegioInformacion = Utils::contentPrivilegios(159) ? '' : 'disabled="disabled"';
        $privilegioReubicacion = Utils::contentPrivilegios(158) ? '' : 'disabled="disabled"';        
        //Fin Cargue de Privilegios del modulo

        $buffer="";        
        $contPallets=0;
        $bufferArticulos="";
        $totalMallas=0;
        $totalCorrreas=0;
        $totalPlasticobase=0;
        $totalPlasticocapuchon=0;
        $totalPernos=0;
        $totalVinipel=0;
        $totalLazos=0;
        $totalConectores=0;
        $totalMaderos=0;
        $totalPallets=0;

        //Carga datos del Usuario
        $id_usuario=$_SESSION['id_usuario'];
        $usuario= new Usuario();  
        $usuario->setIdUsuario($id_usuario);                       
        $usuario = $usuario->getOne();

        //Cargue general de pallets
        $pallets = new Pallet();
        $pallets = $pallets->getAllActives();

        //Carga las ubicaciones disponibles para la Reubicacion
        $ubicacion = new Ubicacion();
        $ubicacionReubicacion=$ubicacion->getAllActives();            

        //Carga el listado de pallet en inventario
        while ($fila = $pallets->fetch_object()){            
            //Datos del Vuelo
            $vueloPallet = new vueloPallet();
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
                if($diff->days <= 7){
                    $colorFila="#FFFFFF";
                }
                else if($diff->days <= 16){
                    $colorFila="#FF9900";
                }
                else{
                    $colorFila="#6666CC";   
                }
            
                $buffer .= '<tr style="background-color:'.$colorFila.'">';
                $buffer .= '<td align="left"><div class="letreros_tabla">                        
                                <form method="POST" action="'.base_url.'Central/consultaPallet">
                                    <input type="hidden" name="pallet" value="'.$fila->id_pallet.'">  
                                    <button type="button" title="Re-Ubicación" data-toggle="modal" data-target="#reubicacionPallet" onclick="prepareFormReubicacion('.$fila->id_pallet.')" '.$privilegioReubicacion.'>R
                                    </button>                                                             
                                    <button type="submit" title="Información" '.$privilegioInformacion.'>i</button>             
                                    <button type="button" title="Novedad" data-toggle="modal" data-target="#addNovedadPallet" onclick="prepareFormNovedad('.$fila->id_pallet.')" '.$privilegioNovedad.'>N</button>
                                </form>                            
                            </div></td>';
                $buffer .= '<td align="left">'. $fila->numero.'</td>';
                $buffer .= '<td align="left">'. $aerolinea->nombre.'</td>';
                $buffer .= '<td align="left">'. $vuelo->no_vuelo.'</td>';
                $buffer .= '<td align="left">'. $vuelo->fecha_vuelo.'</td>';
                $buffer .= '<td align="left">'. $ubicacion->nombre.'</td>';
                $buffer .= '<td align="left">'. $buen_estado.'</td>';                       
                $buffer .= '<td align="left">'. $contenedor_activo.'</td>';    
                $buffer .= '<td align="left">'. $diff->days.'</td>';            
                $buffer .= "</tr>";
                $totalPallets ++;
            }                  
        }

        //Carga el inventario de articulos de las aerolíneas
        $articulosAerolinea = new ArticulosAerolinea();
        $articulosAerolinea = $articulosAerolinea->getAll();
        while ($fila = $articulosAerolinea->fetch_object()) {            
            //Seleccion de permisos de UsuarioXAerolinea para mostrar datos
            if ($usuario->id_aerolinea == "*" || $usuario->id_aerolinea == $fila->aerolinea)
            {
                $aerolinea = new Aerolinea();
                $aerolinea->setIdAerolinea($fila->aerolinea);
                $aerolinea = $aerolinea->getOne();
                //Conteno de Pallets de la aerolinea
                $nPallets = new Pallet();
                $nPallets->setAerolinea($fila->aerolinea);
                $nPallets = $nPallets->getCountByAerolinea();

                //Construccion de respuesta
                $bufferArticulos .= '<tr style="background-color:#ffffff">';
                $bufferArticulos .= '<td align="left">'.$aerolinea->nombre.'</td>';
                $bufferArticulos .= '<td align="right">'. $nPallets->cantidad.'</td>';
                $bufferArticulos .= '<td align="right">'. $fila->mallas.'</td>';
                $bufferArticulos .= '<td align="right">'. $fila->correas.'</td>';
                $bufferArticulos .= '<td align="right">'. $fila->plasticobase.'</td>';
                $bufferArticulos .= '<td align="right">'. $fila->plasticocapuchon.'</td>';
                $bufferArticulos .= '<td align="right">'. $fila->pernos.'</td>';
                $bufferArticulos .= '<td align="right">'. $fila->vinipel.'</td>';
                $bufferArticulos .= '<td align="right">'. $fila->lazos.'</td>';
                $bufferArticulos .= '<td align="right">'. $fila->conectores.'</td>';
                $bufferArticulos .= '<td align="right">'. $fila->maderos.'</td>';
                $bufferArticulos .= '</tr>';            
                $totalMallas += $fila->mallas;
                $totalCorrreas += $fila->correas;
                $totalPlasticobase += $fila->plasticobase;
                $totalPlasticocapuchon += $fila->plasticocapuchon;
                $totalPernos += $fila->pernos;
                $totalVinipel += $fila->vinipel;
                $totalLazos += $fila->lazos;
                $totalConectores += $fila->conectores;
                $totalMaderos += $fila->maderos;
            }
        }

        $bufferArticulos .= '<tr style="background-color:#ffffff">';
        $bufferArticulos .= '<td align="center" class="celda_tabla_principal">TOTAL</td>';
        $bufferArticulos .= '<td align="right" class="celda_tabla_principal">'. $totalPallets.'</td>';
        $bufferArticulos .= '<td align="right" class="celda_tabla_principal">'. $totalMallas.'</td>';
        $bufferArticulos .= '<td align="right" class="celda_tabla_principal">'. $totalCorrreas.'</td>';
        $bufferArticulos .= '<td align="right" class="celda_tabla_principal">'. $totalPlasticobase.'</td>';
        $bufferArticulos .= '<td align="right" class="celda_tabla_principal">'. $totalPlasticocapuchon.'</td>';
        $bufferArticulos .= '<td align="right" class="celda_tabla_principal">'. $totalPernos.'</td>';
        $bufferArticulos .= '<td align="right" class="celda_tabla_principal">'. $totalVinipel.'</td>';
        $bufferArticulos .= '<td align="right" class="celda_tabla_principal">'. $totalLazos.'</td>';
        $bufferArticulos .= '<td align="right" class="celda_tabla_principal">'. $totalConectores.'</td>';
        $bufferArticulos .= '<td align="right" class="celda_tabla_principal">'. $totalMaderos.'</td>';
        $bufferArticulos .= '</tr>';         
        //Fin invetario de artículos

        require_once 'views/layouts/layout1/inventario_general.php';
    }

    public function despachoGeneral() {
        //Cargue de Privilegios del modulo
        if(!Utils::contentPrivilegios(157)){
            require_once 'views/layouts/layout2/403.php';
            die();    
        }
        //Fin Cargue de Privilegios del modulo

        $id_usuario=$_SESSION['id_usuario'];
        $usuario= new Usuario();  
        $usuario->setIdUsuario($id_usuario);                       
        $usuario = $usuario->getOne();

        //Seleccion de permisos de UsuarioXAerolinea
        $aerolinea = new Aerolinea();
        $aerolineaUsuario = $usuario->id_aerolinea;
        if($aerolineaUsuario == "*"){           
            $aerolinea=$aerolinea->getAllActives();
        }
        else{
            $aerolinea->setIdAerolinea($aerolineaUsuario); 
            $aerolinea=$aerolinea->getFullOne();
        }

        //Cargue de Ubicaciones
        $ubicacion = new Ubicacion();
        $ubicacion = $ubicacion->getAllActives();

        $buffer="";
        $pallets = new Pallet();
        $pallets = $pallets->getAllActives();
        $cont=1;
        while ($fila = $pallets->fetch_object()){            
            $buffer .= '<tr style="background-color: #ffffff"">';
            $buffer .= '<td align="left"><div class="letreros_tabla">
                            <input type="checkbox" name="pallet_'.$cont.' value="'.$fila->id_pallet.'">
                        </div></td>';
            $buffer .= '<td align="left">'. $fila->numero.'</td>';
            $buffer .= "</tr>";
        }
        //Cargue de la Vista
        require_once 'views/layouts/layout1/despachoGeneral.php';
    }

    public function modificarPallet() {
        //Cargue de Privilegios del modulo
        if(!Utils::contentPrivilegios(162)){
            require_once 'views/layouts/layout2/403.php';
            die();    
        }       
        //Fin Cargue de Privilegios del modulo
        $id_pallet=isset($_POST['pallet'])?$_POST['pallet']:false;        
        if($id_pallet){
            $pallet = new Pallet();
            $pallet->setIdPallet($id_pallet);
            $pallet = $pallet->getOne();
            $estado = new Estado();
            $estado->setIdEstado($pallet->estado);
            $estado = $estado->getOne();

            $aerolinea = new Aerolinea();
            $aerolinea->setIdAerolinea($pallet->aerolinea);
            $aerolinea = $aerolinea->getAllActives();

            $ubicacion = new Ubicacion();
            $ubicacion->setIdUbicacion($pallet->ubicacion);
            $ubicacion = $ubicacion->getAllActives();

            require_once 'views/layouts/layout1/modificarPallet.php';
        }
        else{
            require_once 'views/layouts/layout1/inventario_general.php';       
        }        
    }

    public function consultaPallet() {
        //Cargue de Privilegios del modulo
        if(!Utils::contentPrivilegios(159)){
            require_once 'views/layouts/layout2/403.php';
            die();    
        }
        $privilegioModificar = Utils::contentPrivilegios(162) ? '' : 'disabled="disabled"';
        $privilegioDesactivar = Utils::contentPrivilegios(161) ? '' : 'disabled="disabled"';
        $privilegioHistorial = Utils::contentPrivilegios(163) ? '' : 'disabled="disabled"';        
        $privilegioNovedad = Utils::contentPrivilegios(160) ? '' : 'disabled="disabled"';        
        //Fin Cargue de Privilegios del modulo

        $id_pallet=isset($_POST['pallet'])?$_POST['pallet']:false;        
        if($id_pallet){
            $pallet = new Pallet();
            $pallet->setIdPallet($id_pallet);
            $pallet = $pallet->getOne();

            $contenedorActivo = $pallet->contenedor_activo == true ? "SI" : "NO";
            $buenEstado = $pallet->buen_estado == true ? "SI" : "NO";

            $estado = new Estado();
            $estado->setIdEstado($pallet->estado);
            $estado = $estado->getOne();

            $aerolinea = new Aerolinea();
            $aerolinea->setIdAerolinea($pallet->aerolinea);
            $aerolinea = $aerolinea->getOne();

            $ubicacion = new Ubicacion();
            $ubicacion->setIdUbicacion($pallet->ubicacion);
            $ubicacion = $ubicacion->getOne();

            require_once 'views/layouts/layout1/consultaPallet.php';
        }
        else{
            require_once 'views/layouts/layout1/inventario_general.php';       
        }        
    }

    public function consultaVuelo() {
        //Cargue de Privilegios del modulo
        $privilegioRepVuelo = Utils::contentPrivilegios(170) ? '' : 'disabled="disabled"';
        //Fin Cargue de Privilegios del modulo        


        $id_vuelo=isset($_POST['id_vuelo'])?$_POST['id_vuelo']:false;        
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
                                <td class="celda_tabla_principal celda_boton">'.++$cont.'</td>
                                <td class="celda_tabla_principal celda_boton">'.$pallet->numero.'</td>
                                <td class="celda_tabla_principal celda_boton">'.$buenEstado.'</td>
                                <td class="celda_tabla_principal celda_boton">'.$contenedorActivo.'</td>
                            </tr>';
            }                       
            require_once 'views/layouts/layout1/consultaVuelo.php';
        }
        else{
            require_once 'views/layouts/layout1/inventario_general.php';       
        }        
    }

    public function buscarPallet() {
        //Cargue de Privilegios del modulo
        if(!Utils::contentPrivilegios(166)){
            require_once 'views/layouts/layout2/403.php';
            die();    
        }
        //Fin Cargue de Privilegios del modulo         
        require_once 'views/layouts/layout1/buscarPallet.php';
    }

    public function buscarVuelo() {
        //Cargue de Privilegios del modulo
        if(!Utils::contentPrivilegios(167)){
            require_once 'views/layouts/layout2/403.php';
            die();    
        }
        //Fin Cargue de Privilegios del modulo        
        require_once 'views/layouts/layout1/buscarVuelo.php';
    }

    public function selectorReportes() {
        //Cargue de Privilegios del modulo
        $privilegioRepSCM = Utils::contentPrivilegios(168) ? '' : 'disabled="disabled"';
        $privilegioRepUCM = Utils::contentPrivilegios(169) ? '' : 'disabled="disabled"';
        $privilegioBuscarVuelo = Utils::contentPrivilegios(167) ? '' : 'disabled="disabled"';  
        $privilegioReportePallets = Utils::contentPrivilegios(172) ? '' : 'disabled="disabled"';    
        $privilegioReporteInventario = Utils::contentPrivilegios(173) ? '' : 'disabled="disabled"';        
        //Fin Cargue de Privilegios del modulo        
        require_once 'views/layouts/layout1/selectorReportes.php';
    }

    public function reporteRecibidoDespachado() {
        $id_usuario=$_SESSION['id_usuario'];
        $usuario= new Usuario();  
        $usuario->setIdUsuario($id_usuario);                       
        $usuario = $usuario->getOne();

        //Seleccion de permisos de UsuarioXAerolinea
        $aerolinea = new Aerolinea();
        $aerolineaUsuario = $usuario->id_aerolinea;
        if($aerolineaUsuario == "*"){           
            $aerolinea=$aerolinea->getAllActives();
        }
        else{
            $aerolinea->setIdAerolinea($aerolineaUsuario); 
            $aerolinea=$aerolinea->getFullOne();
        }        
        require_once 'views/layouts/layout1/reporteRecibidoDespachado.php';
    }
    
    public function reporteUCM() {
        $id_usuario=$_SESSION['id_usuario'];
        $usuario= new Usuario();  
        $usuario->setIdUsuario($id_usuario);                       
        $usuario = $usuario->getOne();

        //Seleccion de permisos de UsuarioXAerolinea
        $aerolinea = new Aerolinea();
        $aerolineaUsuario = $usuario->id_aerolinea;
        if($aerolineaUsuario == "*"){           
            $aerolinea=$aerolinea->getAllActives();
        }
        else{
            $aerolinea->setIdAerolinea($aerolineaUsuario); 
            $aerolinea=$aerolinea->getFullOne();
        }        
        require_once 'views/layouts/layout1/reporteUCM.php';
    }

    public function reporteSCM() {
        $id_usuario=$_SESSION['id_usuario'];
        $usuario= new Usuario();  
        $usuario->setIdUsuario($id_usuario);                       
        $usuario = $usuario->getOne();

        //Seleccion de permisos de UsuarioXAerolinea
        $aerolinea = new Aerolinea();
        $aerolineaUsuario = $usuario->id_aerolinea;
        if($aerolineaUsuario == "*"){           
            $aerolinea=$aerolinea->getAllActives();
        }
        else{
            $aerolinea->setIdAerolinea($aerolineaUsuario); 
            $aerolinea=$aerolinea->getFullOne();
        }

        //Cargue de Ubicaciones
        $ubicacion = new Ubicacion();
        $ubicacion = $ubicacion->getAllActives();

        $buffer="";
        $pallets = new Pallet();
        $pallets = $pallets->getAllActives();
        $cont=1;
        while ($fila = $pallets->fetch_object()){            
            $buffer .= '<tr style="background-color: #ffffff"">';
            $buffer .= '<td align="left"><div class="letreros_tabla">
                            <input type="checkbox" name="pallet_'.$cont.' value="'.$fila->id_pallet.'">
                        </div></td>';
            $buffer .= '<td align="left">'. $fila->numero.'</td>';
            $buffer .= "</tr>";
        }
        //Cargue de la Vista               
        require_once 'views/layouts/layout1/reporteSCM.php';
    }    

    public function ajusteManualElemetos() {
        //Cargue de Privilegios del modulo
        if(!Utils::contentPrivilegios(171)){
            require_once 'views/layouts/layout2/403.php';
            die();    
        }        
        $bufferArticulos = "";
        //Carga datos del Usuario
        $id_usuario=$_SESSION['id_usuario'];
        $usuario= new Usuario();  
        $usuario->setIdUsuario($id_usuario);                       
        $usuario = $usuario->getOne();        
        //Carga el inventario de articulos de las aerolíneas
        $articulosAerolinea = new ArticulosAerolinea();
        $articulosAerolinea = $articulosAerolinea->getAll();
        while ($fila = $articulosAerolinea->fetch_object()) {
            if ($usuario->id_aerolinea == "*" || $usuario->id_aerolinea == $fila->aerolinea)
            {
                $aerolinea = new Aerolinea();
                $aerolinea->setIdAerolinea($fila->aerolinea);
                $aerolinea = $aerolinea->getOne();
                //Conteno de Pallets de la aerolinea
                $nPallets = new Pallet();

                $nPallets->setAerolinea($fila->aerolinea);
                $nPallets = $nPallets->getCountByAerolinea();
                //Construccion de respuesta
                $bufferArticulos .= '<tr style="background-color:#ffffff">';            
                $bufferArticulos .= '<td align="left">'.$aerolinea->nombre.'</td>';
                $bufferArticulos .= '<td align="right">'. $nPallets->cantidad.'</td>';
                $bufferArticulos .= '<td align="right"><input type="number" id="mallas_'.$aerolinea->id.'" name="mallas_'.$aerolinea->id.'"  class="form-control" value="'. $fila->mallas.'" min="0" required="true" onkeypress="return numeric(event)"></td>';
                $bufferArticulos .= '<td align="right"><input type="number" id="correas_'.$aerolinea->id.'" name="correas_'.$aerolinea->id.'"  class="form-control" value="'. $fila->correas.'" min="0" required="true" onkeypress="return numeric(event)"></td>';
                $bufferArticulos .= '<td align="right"><input type="number" id="plasticobase_'.$aerolinea->id.'" name="plasticobase_'.$aerolinea->id.'"  class="form-control" value="'. $fila->plasticobase.'" min="0" required="true" onkeypress="return numeric(event)"></td>';
                $bufferArticulos .= '<td align="right"><input type="number" id="plasticocapuchon_'.$aerolinea->id.'" name="plasticocapuchon_'.$aerolinea->id.'"  class="form-control" value="'. $fila->plasticocapuchon.'" min="0" required="true" onkeypress="return numeric(event)"></td>';
                $bufferArticulos .= '<td align="right"><input type="number" id="pernos_'.$aerolinea->id.'" name="pernos_'.$aerolinea->id.'"  class="form-control" value="'. $fila->pernos.'" min="0" required="true" onkeypress="return numeric(event)"></td>';
                $bufferArticulos .= '<td align="right"><input type="number" id="vinipel_'.$aerolinea->id.'" name="vinipel_'.$aerolinea->id.'"  class="form-control" value="'. $fila->vinipel.'" min="0" required="true" onkeypress="return numeric(event)"></td>';
                $bufferArticulos .= '<td align="right"><input type="number" id="lazos_'.$aerolinea->id.'" name="lazos_'.$aerolinea->id.'"  class="form-control" value="'. $fila->lazos.'" min="0" required="true" onkeypress="return numeric(event)"></td>';
                $bufferArticulos .= '<td align="right"><input type="number" id="conectores_'.$aerolinea->id.'" name="conectores_'.$aerolinea->id.'"  class="form-control" value="'. $fila->conectores.'" min="0" required="true" onkeypress="return numeric(event)"></td>';
                $bufferArticulos .= '<td align="right"><input type="number" id="maderos_'.$aerolinea->id.'" name="maderos_'.$aerolinea->id.'"  class="form-control" value="'. $fila->maderos.'" min="0" required="true" onkeypress="return numeric(event)"></td>';
                $bufferArticulos .= '</tr>';            
            }
        }        

        //Cargue de la Vista               
        require_once 'views/layouts/layout1/ajusteManualElemetos.php';
    }

    public function reportePallets() {
        //Cargue de Privilegios del modulo
        if(!Utils::contentPrivilegios(172)){
            require_once 'views/layouts/layout2/403.php';
            die();    
        }
        //Fin Cargue de Privilegios del modulo           
        require_once 'views/layouts/layout1/reportePallets.php';
    }

    public function palletRepetido(){
        if(isset($_SESSION['palletRepetido'])){
            $buffer = "";
            $cont=0;
            foreach ($_SESSION['palletRepetido'] as $key=> $datos) {        
                $cont++;
                $buffer .= '
                            <tr align="center">
                                <td class="celda_tabla_principal celda_boton">'.$datos['numero'].'</td>                         
                                <td class="celda_tabla_principal celda_boton">
                                    <input type="hidden" name="id_pallet_'.$cont.'" id="id_pallet_'.$cont.'" value="'.$datos['id_pallet'].'">
                                    <input type="text" name="descripcion_'.$cont.'" id="descripcion_'.$cont.'" class="form-control" required="true">
                                </td>
                            </tr>
                ';                      
            }
        }

        require_once 'views/layouts/layout1/palletRepetido.php';
    }

}
?>