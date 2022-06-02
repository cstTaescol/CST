<?php
class Aerolinea
{
	private $idAerolinea;
	private $nombre;
	private $estado;	
	private $db;

	public function __construct()
	{
		$this->db=Database::connect();
	}

	public function getIdAerolinea() {
	    return $this->idAerolinea;
	}
	 
	public function setIdAerolinea($idAerolinea) {
	    $this->idAerolinea = $idAerolinea;
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
		$sql="SELECT * FROM aerolinea AND importacion=true";
		$consulta = $this->db->query($sql);
		return $consulta;
	}

	public function getAllActives(){
		$sql="SELECT * FROM aerolinea WHERE estado ='A' AND importacion=true";
		$consulta = $this->db->query($sql);
		return $consulta;
	}

    public function getOne() {            
        $sql="SELECT id,nombre FROM aerolinea WHERE estado ='A' AND importacion=true AND id ={$this->getIdAerolinea()}";
        $consulta = $this->db->query($sql);
        $fila=$consulta->fetch_object();                   
        return $fila;            
    } 	

    public function getFullOne() {            
        $sql="SELECT * FROM aerolinea WHERE estado ='A' AND importacion=true AND id ={$this->getIdAerolinea()}";
        $consulta = $this->db->query($sql);
        //echo $sql . "<hr>" . $this->db->error; die();            
        return $consulta;            
    }     

}

?>