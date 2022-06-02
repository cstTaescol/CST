<?php
class Ubicacion
{
	private $db;
	private $idUbicacion;
	private $nombre;
	private $estado;	
	
	public function __construct()
	{
		$this->db=Database::connect();
	}

	public function getIdUbicacion() {
	    return $this->idUbicacion;
	}
	 
	public function setIdUbicacion($idUbicacion) {
	    $this->idUbicacion = $idUbicacion;
	}

	public function getNombre() {
	    return $this->nombre;
	}
	 
	public function setNombre($nombre) {
	    $this->nombre = $nombre;
	}
	public function getEstado() {
	    return $this->estado;
	}
	 
	public function setEstado($estado) {
	    $this->estado = $estado;
	}


	public function getAll(){
		$sql="SELECT * FROM pallet_ubicacion ORDER BY nombre";
		$consulta = $this->db->query($sql);
		return $consulta;
	}

	public function getAllActives(){
		$sql="SELECT * FROM pallet_ubicacion WHERE estado =1 ORDER BY nombre ASC";
		$consulta = $this->db->query($sql);
		return $consulta;
	}

    public function getOne() {            
        $sql="SELECT id_ubicacion,nombre,estado FROM pallet_ubicacion WHERE id_ubicacion ={$this->getIdUbicacion()} ORDER BY nombre ASC";
        $consulta = $this->db->query($sql);
        $fila=$consulta->fetch_object();                   
        return $fila;            
    } 	

    public function getFullOne() {            
        $sql="SELECT * FROM pallet_ubicacion WHERE id_ubicacion ={$this->getIdUbicacion()} ORDER BY nombre ASC";
        $consulta = $this->db->query($sql);
        //echo $sql . "<hr>" . $this->db->error; die();            
        return $consulta;            
    }     

}

?>