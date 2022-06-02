<?php
class Vuelo
{
	private $db;	
	private $idVuelo;
	private $aerolinea;
	private $no_vuelo;
	private $matricula;
	private $fecha_vuelo;
	private $fechahora_creacion;
    private $mallas;
    private $mallas_bueno;
    private $mallas_malo;
	private $correas;
	private $correas_bueno;
	private $correas_malo;
	private $plasticobase;
	private $plasticobase_bueno;
	private $plasticobase_malo;
	private $plasticocapuchon;
	private $plasticocapuchon_bueno;
	private $plasticocapuchon_malo;
	private $pernos;
	private $pernos_bueno;
	private $pernos_malo;
	private $vinipel;
	private $vinipel_bueno;
	private $vinipel_malo;
	private $lazos;
	private $lazos_bueno;
	private $lazos_malo;
	private $conectores;
	private $conectores_bueno;
	private $conectores_malo;
	private $maderos;
	private $maderos_bueno;
	private $maderos_malo;
	private $otros;
	private $otros_cuales;
	private $estado;

	public function __construct()
	{
		$this->db=Database::connect();
	}

	public function getIdVuelo() {
	    return $this->idVuelo;
	}
	 
	public function setIdVuelo($idVuelo) {
	    $this->idVuelo = $idVuelo;
	}

	public function getAerolinea() {
	    return $this->aerolinea;
	}
	 
	public function setAerolinea($aerolinea) {
	    $this->aerolinea = $aerolinea;
	}

	public function getNo_vuelo() {
	    return $this->no_vuelo;
	}
	 
	public function setNo_vuelo($no_vuelo) {
	    $this->no_vuelo = $this->db->real_escape_string($no_vuelo);
	}

	public function getMatricula() {
	    return $this->matricula;
	}
	 
	public function setMatricula($matricula) {
	    $this->matricula = $matricula;
	}

	public function getFecha_vuelo() {
	    return $this->fecha_vuelo;
	}
	 
	public function setFecha_vuelo($fecha_vuelo) {
	    $this->fecha_vuelo = $fecha_vuelo;
	}

	public function getFechahora_creacion() {
	    return $this->fechahora_creacion;
	}
	 
	public function setFechahora_creacion($fechahora_creacion) {
	    $this->fechahora_creacion = $fechahora_creacion;
	}
   	public function getMallas() {
   	    return $this->mallas;
   	}
   	 
   	public function setMallas($mallas) {
   	    $this->mallas = $mallas;
   	}
    public function getMallas_bueno() {
        return $this->mallas_bueno;
    }
     
    public function setMallas_bueno($mallas_bueno) {
        $this->mallas_bueno = $mallas_bueno;
    }
    
    public function getMallas_malo() {
        return $this->mallas_malo;
    }
     
    public function setMallas_malo($mallas_malo) {
        $this->mallas_malo = $mallas_malo;
    }
	public function getCorreas() {
	    return $this->correas;
	}
	 
	public function setCorreas($correas) {
	    $this->correas = $correas;
	}
	public function getCorreas_bueno() {
	    return $this->correas_bueno;
	}
	 
	public function setCorreas_bueno($correas_bueno) {
	    $this->correas_bueno = $correas_bueno;
	}
	public function getCorreas_malo() {
	    return $this->correas_malo;
	}
	 
	public function setCorreas_malo($correas_malo) {
	    $this->correas_malo = $correas_malo;
	}
	public function getPlasticobase() {
	    return $this->plasticobase;
	}
	 
	public function setPlasticobase($plasticobase) {
	    $this->plasticobase = $plasticobase;
	}
	public function getPlasticobase_bueno() {
	    return $this->plasticobase_bueno;
	}
	 
	public function setPlasticobase_bueno($plasticobase_bueno) {
	    $this->plasticobase_bueno = $plasticobase_bueno;
	}
	public function getPlasticobase_malo() {
	    return $this->plasticobase_malo;
	}
	 
	public function setPlasticobase_malo($plasticobase_malo) {
	    $this->plasticobase_malo = $plasticobase_malo;
	}
	public function getPlasticocapuchon() {
	    return $this->plasticocapuchon;
	}
	 
	public function setPlasticocapuchon($plasticocapuchon) {
	    $this->plasticocapuchon = $plasticocapuchon;
	}
	public function getPlasticocapuchon_bueno() {
	    return $this->plasticocapuchon_bueno;
	}
	 
	public function setPlasticocapuchon_bueno($plasticocapuchon_bueno) {
	    $this->plasticocapuchon_bueno = $plasticocapuchon_bueno;
	}
	public function getPlasticocapuchon_malo() {
	    return $this->plasticocapuchon_malo;
	}
	 
	public function setPlasticocapuchon_malo($plasticocapuchon_malo) {
	    $this->plasticocapuchon_malo = $plasticocapuchon_malo;
	}
	public function getPernos() {
	    return $this->pernos;
	}
	 
	public function setPernos($pernos) {
	    $this->pernos = $pernos;
	}
	public function getPernos_bueno() {
	    return $this->pernos_bueno;
	}
	 
	public function setPernos_bueno($pernos_bueno) {
	    $this->pernos_bueno = $pernos_bueno;
	}
	public function getPernos_malo() {
	    return $this->pernos_malo;
	}
	 
	public function setPernos_malo($pernos_malo) {
	    $this->pernos_malo = $pernos_malo;
	}
	public function getVinipel() {
	    return $this->vinipel;
	}
	 
	public function setVinipel($vinipel) {
	    $this->vinipel = $vinipel;
	}
	public function getVinipel_bueno() {
	    return $this->vinipel_bueno;
	}
	 
	public function setVinipel_bueno($vinipel_bueno) {
	    $this->vinipel_bueno = $vinipel_bueno;
	}
	public function getVinipel_malo() {
	    return $this->vinipel_malo;
	}
	 
	public function setVinipel_malo($vinipel_malo) {
	    $this->vinipel_malo = $vinipel_malo;
	}
	public function getLazos() {
	    return $this->lazos;
	}
	 
	public function setLazos($lazos) {
	    $this->lazos = $lazos;
	}
	public function getLazos_bueno() {
	    return $this->lazos_bueno;
	}
	 
	public function setLazos_bueno($lazos_bueno) {
	    $this->lazos_bueno = $lazos_bueno;
	}
	public function getLazos_malo() {
	    return $this->lazos_malo;
	}
	 
	public function setLazos_malo($lazos_malo) {
	    $this->lazos_malo = $lazos_malo;
	}
	public function getConectores() {
	    return $this->conectores;
	}
	 
	public function setConectores($conectores) {
	    $this->conectores = $conectores;
	}
	public function getConectores_bueno() {
	    return $this->conectores_bueno;
	}
	 
	public function setConectores_bueno($conectores_bueno) {
	    $this->conectores_bueno = $conectores_bueno;
	}
	public function getConectores_malo() {
	    return $this->conectores_malo;
	}
	 
	public function setConectores_malo($conectores_malo) {
	    $this->conectores_malo = $conectores_malo;
	}
	public function getMaderos() {
	    return $this->maderos;
	}
	 
	public function setMaderos($maderos) {
	    $this->maderos = $maderos;
	}
	public function getMaderos_bueno() {
	    return $this->maderos_bueno;
	}
	 
	public function setMaderos_bueno($maderos_bueno) {
	    $this->maderos_bueno = $maderos_bueno;
	}
	public function getMaderos_malo() {
	    return $this->maderos_malo;
	}
	 
	public function setMaderos_malo($maderos_malo) {
	    $this->maderos_malo = $maderos_malo;
	}
	public function getOtros() {
	    return $this->otros;
	}
	 
	public function setOtros($otros) {
	    $this->otros = $otros;
	}
	public function getOtros_cuales() {
	    return $this->otros_cuales;
	}
	 
	public function setOtros_cuales($otros_cuales) {
	    $this->otros_cuales = $this->db->real_escape_string($otros_cuales);
	}
	public function getEstado() {
	    return $this->estado;
	}
	 
	public function setEstado($estado) {
	    $this->estado = $this->db->real_escape_string($estado);
	}

	public function getAll(){
		$sql="SELECT * FROM pallet_vuelo ORDER BY fechahora_creacion DESC";
		$consulta = $this->db->query($sql);
		return $consulta;
	}

    public function getOne() {            
        $sql="SELECT * FROM pallet_vuelo WHERE id_vuelo ={$this->getIdVuelo()}";
        $consulta = $this->db->query($sql);
        //echo $sql . "<hr>" . $this->db->error; die();                    
        $fila=$consulta->fetch_object();                   
        return $fila;                    
    } 	
    
    public function getFullOne() {            
        $sql="SELECT * FROM pallet_vuelo WHERE id_vuelo ={$this->getIdVuelo()}";
        $consulta = $this->db->query($sql);
        //echo $sql . "<hr>" . $this->db->error; die();            
        return $consulta;  
    }

    public function save(){
        $result=false;
        $sql="INSERT INTO pallet_vuelo VALUES (NULL,{$this->getAerolinea()},'{$this->getNo_vuelo()}','{$this->getMatricula()}',CAST('{$this->getFecha_vuelo()}' AS DATE),NOW(),{$this->getMallas()},{$this->getMallas_bueno()},{$this->getMallas_malo()},{$this->getCorreas()},{$this->getCorreas_bueno()},{$this->getCorreas_malo()},{$this->getPlasticobase()},{$this->getPlasticobase_bueno()},{$this->getPlasticobase_malo()},{$this->getPlasticocapuchon()},{$this->getPlasticocapuchon_bueno()},{$this->getPlasticocapuchon_malo()},{$this->getPernos()},{$this->getPernos_bueno()},{$this->getPernos_malo()},{$this->getVinipel()},{$this->getVinipel_bueno()},{$this->getVinipel_malo()},{$this->getLazos()},{$this->getLazos_bueno()},{$this->getLazos_malo()},{$this->getConectores()},{$this->getConectores_bueno()},{$this->getConectores_malo()},{$this->getMaderos()},{$this->getMaderos_bueno()},{$this->getMaderos_malo()},{$this->getOtros()},'{$this->getOtros_cuales()}',{$this->getEstado()})";            

        $save=$this->db->query($sql);
        //echo $sql . "<hr>" . $this->db->error; die();            
        if ($save) {
            $result=$this->db->insert_id;
        }
        return $result;
    }	

    public function delete(){
        $result=false;
        $sql = "DELETE FROM pagos WHERE id={$this->getId()}";
        $delete=$this->db->query($sql);
        if($delete){
            $result=true;
        }
        return $result;
    }

    public function getRangoFecha($fechaini,$fechafin) {            
        $sql="SELECT * FROM pallet_vuelo WHERE fecha_vuelo BETWEEN '$fechaini' AND '$fechafin' ORDER BY fecha_vuelo DESC";
        $consulta = $this->db->query($sql);        
        return $consulta;            
    }

    public function getVuelosRecibidosRangoFecha($fechaini,$fechafin) {            
        $sql="SELECT * FROM pallet_vuelo WHERE estado = {$this->getEstado()} AND aerolinea = {$this->getAerolinea()} AND fecha_vuelo BETWEEN '$fechaini' AND '$fechafin' ORDER BY  fecha_vuelo DESC";
        $consulta = $this->db->query($sql);        
        //echo $sql . "<hr>" . $this->db->error; die();     
        return $consulta;            
    }   

    public function getCoincidencia() {            
        $sql="SELECT * FROM pallet_vuelo WHERE no_vuelo LIKE '%{$this->getNo_vuelo()}%' ORDER BY fecha_vuelo DESC";
        $consulta = $this->db->query($sql);        
        return $consulta;            
    }


}