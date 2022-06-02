<?php
class Historial
{
	private $db;
	private $idHistorial;
	private $pallet;
	private $descripcion;
	private $fechahora_creacion;
	private $usuario;
	private $estado;	

	public function __construct()
	{
		$this->db=Database::connect();
	}

	public function getIdHistorial() {
	    return $this->idHistorial;
	}
	 
	public function setIdHistorial($idHistorial) {
	    $this->idHistorial = $idHistorial;
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
	    $this->descripcion = $this->db->real_escape_string($descripcion);
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
	public function getEstado() {
	    return $this->estado;
	}
	 
	public function setEstado($estado) {
	    $this->estado = $estado;
	}

    public function save(){
        $result=false;
        $sql="INSERT INTO pallet_historial (pallet,descripcion,fechahora_creacion,usuario,estado) VALUES ({$this->getPallet()},'{$this->getDescripcion()}',NOW(),{$this->getUsuario()},{$this->getEstado()})";            
        $save=$this->db->query($sql);
        //echo $sql . "<hr>" . $this->db->error; die();            
        if ($save) {
            $result=$this->db->insert_id;           
        }
        return $result;
    }

	public function getAllByPallet(){
		$sql="SELECT descripcion,fechahora_creacion,usuario,estado FROM pallet_historial WHERE pallet = {$this->getPallet()} ORDER BY id_historial DESC";		
		//echo $sql . "<hr>" . $this->db->error; die();            
		$consulta = $this->db->query($sql);		
		return $consulta;
	}    

/*


	public function getAllByAerolinea($aerolinea){
		$sql="SELECT COUNT(id_pallet) AS cantidad FROM pallet WHERE estado = 5 AND aerolinea = {$aerolinea}";
		//echo $sql . "<hr>" . $this->db->error; die();            
		$consulta = $this->db->query($sql);
		$fila = $consulta->fetch_object(); 
		return $fila;
	}

*/

}

?>