<?php
class Estado
{
	private $db;
	private $idEstado;
	private $nombre;	
	
	public function __construct()
	{
		$this->db=Database::connect();
	}

	public function getIdEstado() {
	    return $this->idEstado;
	}
	 
	public function setIdEstado($idEstado) {
	    $this->idEstado = $idEstado;
	}

	public function getNombre() {
	    return $this->nombre;
	}
	 
	public function setNombre($nombre) {
	    $this->nombre = $nombre;
	}

	public function getAll(){
		$sql="SELECT * FROM pallet_estado ORDER BY nombre";
		$consulta = $this->db->query($sql);
		return $consulta;
	}

    public function getOne() {            
        $sql="SELECT id_estado,nombre FROM pallet_estado WHERE id_estado ={$this->getIdEstado()}";
        $consulta = $this->db->query($sql);
        $fila=$consulta->fetch_object();                   
        return $fila;            
    } 	
}

?>