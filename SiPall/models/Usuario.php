<?php
class Usuario
{
	private $idUsuario;
	private $nombre;
	private $estado;	
	private $id_aerolinea;
	private $db;

	public function __construct()
	{
		$this->db=Database::connect();
	}

	public function getIdUsuario() {
	    return $this->idUsuario;
	}
	 
	public function setIdUsuario($idUsuario) {
	    $this->idUsuario = $idUsuario;
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
	public function getId_aerolinea() {
	    return $this->id_aerolinea;
	}
	 
	public function setId_aerolinea($id_aerolinea) {
	    $this->id_aerolinea = $id_aerolinea;
	}

	public function getAll(){
		$sql="SELECT * FROM usuario";
		$consulta = $this->db->query($sql);
		return $consulta;
	}

	public function getAllActives(){
		$sql="SELECT * FROM usuario WHERE estado ='A'";
		$consulta = $this->db->query($sql);
		return $consulta;
	}

    public function getOne() {            
        $sql="SELECT * FROM usuario WHERE id ={$this->getIdUsuario()}";
        $consulta = $this->db->query($sql);
        $fila=$consulta->fetch_object();  
        //echo $sql . "<hr>" . $this->db->error; die();            
        return $fila;            
    } 	


}

?>