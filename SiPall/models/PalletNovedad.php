<?php
class PalletNovedad
{
	private $db;
	private $idNovedad;
	private $pallet;
	private $descripcion;
	private $fechahora_creacion;	
	private $usuario;

	public function __construct()
	{
		$this->db=Database::connect();
	}

	public function getIdNovedad() {
	    return $this->idNovedad;
	}
	 
	public function setIdNovedad($idNovedad) {
	    $this->idNovedad = $idNovedad;
	}
	public function getPallet() {
	    return $this->pallet;
	}
	 
	public function setPallet($pallet) {
	    $this->pallet = $pallet;
	}
	public function getDescripcion() {
	    return $this->descripcion;
	}
	 
	public function setDescripcion($descripcion) {
	    $this->descripcion = $descripcion;
	}
	public function getFechahora_creacion() {
	    return $this->fechahora_creacion;
	}
	 
	public function setFechahora_creacion($fechahora_creacion) {
	    $this->fechahora_creacion = $fechahora_creacion;
	}
	public function getUsuario() {
	    return $this->usuario;
	}
	 
	public function setUsuario($usuario) {
	    $this->usuario = $usuario;
	}

    public function getOne() {            
        $sql="SELECT * FROM pallet_novedad WHERE id_novedad={$this->getIdNovedad()}";
        $consulta = $this->db->query($sql);
        $fila = $consulta->fetch_object();          
        return $fila;            
    } 	

    public function getAllByPallet() {            
        $sql="SELECT * FROM pallet_novedad WHERE pallet={$this->getPallet()} ORDER BY fechahora_creacion DESC";
        $consulta = $this->db->query($sql);        
        return $consulta;            
    }     

    public function save(){
        $result=false;
        $sql="INSERT INTO pallet_novedad (pallet,descripcion,fechahora_creacion,usuario) VALUES ({$this->getPallet()},'{$this->getDescripcion()}',NOW(),{$this->getUsuario()})";            
        $save=$this->db->query($sql);
        //echo $sql . "<hr>" . $this->db->error; die();            
        if ($save) {
            $result=$this->db->insert_id;           
        }
        return $result;
    }    


}
?>