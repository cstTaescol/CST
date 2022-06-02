<?php
class PalletUbicacion
{
	private $id_ubicacion;
	private $nombre;
	private $estado;	
	private $db;

	public function __construct()
	{
		$this->db=Database::connect();
	}

	public function getId_ubicacion() {
	    return $this->id_ubicacion;
	}
	 
	public function setId_ubicacion($id_ubicacion) {
	    $this->id_ubicacion = $id_ubicacion;
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

/*
	 
	public function setDescripcion($Descripcion) {
	    $this->Descripcion = $this->db->real_escape_string($Descripcion);
	}

*/
	public function getAll(){
		$sql="SELECT * FROM pallet_ubicacion";
		$consulta = $this->db->query($sql);
		return $consulta;
	}

	public function getAllActives(){
		$sql="SELECT * FROM pallet_ubicacion WHERE estado = ";
		$consulta = $this->db->query($sql);
		return $consulta;
	}

}

?>