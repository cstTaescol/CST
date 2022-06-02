<?php
class Pallet
{
	private $db;
	private $idPallet;
	private $numero;
	private $aerolinea;
	private $ubicacion;	
	private $fechahora_creacion;
	private $observaciones;
	private $contenedor_activo;
	private $buen_estado;
	private $estado;
	
	public function __construct()
	{
		$this->db=Database::connect();
	}

	public function getIdPallet() {
	    return $this->idPallet;
	}
	 
	public function setIdPallet($idPallet) {
	    $this->idPallet = $idPallet;
	}

	public function getNumero() {
	    return $this->numero;
	}
	 
	public function setNumero($numero) {
	    $this->numero = $numero;
	}

	public function getAerolinea() {
	    return $this->aerolinea;
	}
	 
	public function setAerolinea($aerolinea) {
	    $this->aerolinea = $aerolinea;
	}

	public function getUbicacion() {
	    return $this->ubicacion;
	}
	 
	public function setUbicacion($ubicacion) {
	    $this->ubicacion = $ubicacion;
	}
	
	public function getFechahora_creacion() {
	    return $this->fechahora_creacion;
	}
	 
	public function setFechahora_creacion($fechahora_creacion) {
	    $this->fechahora_creacion = $fechahora_creacion;
	}

	public function getObservaciones() {
	    return $this->observaciones;
	}
	 
	public function setObservaciones($observaciones) {
	    $this->observaciones = $this->db->real_escape_string($observaciones);
	}
	public function getContenedor_activo() {
	    return $this->contenedor_activo;
	}
	 
	public function setContenedor_activo($contenedor_activo) {
	    $this->contenedor_activo = $contenedor_activo;
	}
	public function getBuen_estado() {
	    return $this->buen_estado;
	}
	 
	public function setBuen_estado($buen_estado) {
	    $this->buen_estado = $buen_estado;
	}

	public function getEstado() {
	    return $this->estado;
	}
	 
	public function setEstado($estado) {
	    $this->estado = $estado;
	}

	public function getAll(){
		$sql="SELECT * FROM pallet";
		$consulta = $this->db->query($sql);
		return $consulta;
	}

	public function getAllActives(){
		$sql="SELECT * FROM pallet WHERE estado = 5 ORDER BY aerolinea ASC";
		//echo $sql . "<hr>" . $this->db->error; die();            
		$consulta = $this->db->query($sql);
		return $consulta;
	}

    public function getOne() {            
        $sql="SELECT * FROM pallet WHERE id_pallet={$this->getIdPallet()}";
        $consulta = $this->db->query($sql);
        $fila = $consulta->fetch_object();          
        return $fila;            
    } 	

	public function getCountByAerolinea(){
		$sql="SELECT COUNT(id_pallet) AS cantidad FROM pallet WHERE estado = 5 AND aerolinea = {$this->getAerolinea()}";
		//echo $sql . "<hr>" . $this->db->error; die();            
		$consulta = $this->db->query($sql);
		$fila = $consulta->fetch_object(); 
		return $fila;
	}

	public function getCountBuenosByAerolinea(){
		$sql="SELECT COUNT(id_pallet) AS cantidad FROM pallet WHERE estado = 5 AND buen_estado = TRUE AND aerolinea = {$this->getAerolinea()}";
		//echo $sql . "<hr>" . $this->db->error; die();            
		$consulta = $this->db->query($sql);
		$fila = $consulta->fetch_object(); 
		return $fila;
	}

	public function getAllActivesByAerolinea(){
		$sql="SELECT * FROM pallet WHERE estado = 5 AND aerolinea = {$this->getAerolinea()}";
		//echo $sql . "<hr>" . $this->db->error; die();            
		$consulta = $this->db->query($sql);		
		return $consulta;
	}	

    public function getFullOne() {            
        $sql="SELECT id,nombre FROM pallet WHERE id={$this->getIdPallet()}";
        $consulta = $this->db->query($sql);
        //echo $sql . "<hr>" . $this->db->error; die();            
        return $consulta;            
    }

    public function save(){
        $result=false;
        $sql="INSERT INTO pallet (numero,aerolinea,ubicacion,fechahora_creacion,observaciones,contenedor_activo,buen_estado,estado) VALUES ('{$this->getNumero()}',{$this->getAerolinea()},{$this->getUbicacion()},NOW(),'{$this->getObservaciones()}',{$this->getContenedor_activo()},{$this->getBuen_estado()},{$this->getEstado()})";            
        $save=$this->db->query($sql);
        //echo $sql . "<hr>" . $this->db->error; die();            
        if ($save) {
            $result=$this->db->insert_id;           
        }
        return $result;
    }

    public function getRecentNewId(){
        $id=$this->db->insert_id;
        return $id;               
    }   

    public function updateOne($campo,$valor){
        $result=false;
        $sql="UPDATE pallet SET $campo=$valor WHERE id_pallet={$this->getIdPallet()}";                    
        $update=$this->db->query($sql);
        //echo $sql . "<hr>" . $this->db->error; die();            
        if ($update) {
            $result=true;           
        }
        return $result;
    } 

    public function update(){
        $result=false;
        $sql="UPDATE pallet SET aerolinea={$this->getAerolinea()}, ubicacion={$this->getUbicacion()}, observaciones='{$this->getObservaciones()}', contenedor_activo={$this->getContenedor_activo()}, buen_estado={$this->getBuen_estado()}, estado={$this->getEstado()} WHERE id_pallet={$this->getIdPallet()}";                    
        $update=$this->db->query($sql);
        //echo $sql . "<hr>" . $this->db->error; die();            
        if ($update) {
            $result=true;           
        }
        return $result;
    }        

	public function getIfExists(){
		$sql="SELECT * FROM pallet WHERE numero = '{$this->getNumero()}'";		
		//echo $sql . "<hr>" . $this->db->error; die();            
		$consulta = $this->db->query($sql);
		$fila = $consulta->fetch_object(); 
		return $fila;
	}


	public function getCoincidencia(){
		$sql="SELECT * FROM pallet WHERE numero LIKE '%{$this->getNumero()}%' ORDER BY id_pallet DESC";
		//echo $sql . "<hr>" . $this->db->error; die();            
		$consulta = $this->db->query($sql);
		return $consulta;
	}

	public function getCount(){		
		$sql = "SELECT COUNT(*) as NRegistros FROM pallet WHERE numero = '{$this->getNumero()}'";
		//echo $sql . "<hr>" . $this->db->error; die();            
		$consulta = $this->db->query($sql);
		$fila = $consulta->fetch_object(); 
		return $fila;
	}

}

?>