<?php
class ArticulosAerolinea
{
	private $db;	
	private $idArticulosAerolinea;
	private $aerolinea;
    private $mallas;
	private $correas;
	private $plasticobase;
	private $plasticocapuchon;
	private $pernos;
	private $vinipel;
	private $lazos;
	private $conectores;
	private $maderos;
	private $otros;

	public function __construct()
	{
		$this->db=Database::connect();
	}

	public function getIdArticulosAerolinea() {
	    return $this->idArticulosAerolinea;
	}
	 
	public function setIdArticulosAerolinea($idArticulosAerolinea) {
	    $this->idArticulosAerolinea = $idArticulosAerolinea;
	}

	public function getAerolinea() {
	    return $this->aerolinea;
	}
	 
	public function setAerolinea($aerolinea) {
	    $this->aerolinea = $aerolinea;
	}

   	public function getMallas() {
   	    return $this->mallas;
   	}
   	 
   	public function setMallas($mallas) {
   	    $this->mallas = $mallas;
   	}

	public function getCorreas() {
	    return $this->correas;
	}
	 
	public function setCorreas($correas) {
	    $this->correas = $correas;
	}

	public function getPlasticobase() {
	    return $this->plasticobase;
	}
	 
	public function setPlasticobase($plasticobase) {
	    $this->plasticobase = $plasticobase;
	}

	public function getPlasticocapuchon() {
	    return $this->plasticocapuchon;
	}
	 
	public function setPlasticocapuchon($plasticocapuchon) {
	    $this->plasticocapuchon = $plasticocapuchon;
	}

	public function getPernos() {
	    return $this->pernos;
	}
	 
	public function setPernos($pernos) {
	    $this->pernos = $pernos;
	}

	public function getVinipel() {
	    return $this->vinipel;
	}
	 
	public function setVinipel($vinipel) {
	    $this->vinipel = $vinipel;
	}

	public function getLazos() {
	    return $this->lazos;
	}
	 
	public function setLazos($lazos) {
	    $this->lazos = $lazos;
	}

	public function getConectores() {
	    return $this->conectores;
	}
	 
	public function setConectores($conectores) {
	    $this->conectores = $conectores;
	}

	public function getMaderos() {
	    return $this->maderos;
	}
	 
	public function setMaderos($maderos) {
	    $this->maderos = $maderos;
	}

	public function getOtros() {
	    return $this->otros;
	}
	 
	public function setOtros($otros) {
	    $this->otros = $otros;
	}


	public function getAll(){
		$sql="SELECT * FROM pallet_articulos_aerolinea ORDER BY aerolinea DESC";
		$consulta = $this->db->query($sql);
		return $consulta;
	}

    public function getOne() {            
        $sql="SELECT * FROM pallet_articulos_aerolinea WHERE id_articulosaerolinea ={$this->getIdArticulosAerolinea()}";
        $consulta = $this->db->query($sql);
        $fila=$consulta->fetch_object();                   
        return $fila;            
    } 	

    public function getFullOne() {            
        $sql="SELECT * FROM pallet_articulos_aerolinea WHERE id_articulosaerolinea ={$this->getIdArticulosAerolinea()}";
        $consulta = $this->db->query($sql);
        //echo $sql . "<hr>" . $this->db->error; die();            
        return $consulta;  
    }

    public function save(){
        $result=false;
        $sql="INSERT INTO pallet_articulos_aerolinea VALUES (NULL,
        													{$this->getAerolinea()},
        													{$this->getMallas()},
        													{$this->getCorreas()},
        													{$this->getPlasticobase()},
        													{$this->getPlasticocapuchon()},
        													{$this->getPernos()},
        													{$this->getVinipel()},
        													{$this->getLazos()},
        													{$this->getConectores()},
        													{$this->getMaderos()},
        													0)";            

        $save=$this->db->query($sql);
        //echo $sql . "<hr>" . $this->db->error; die();            
        if ($save) {
            $result=$this->db->insert_id;
        }
        return $result;
    }

    public function getOneByAerolinea() {            
        $sql="SELECT * FROM pallet_articulos_aerolinea WHERE aerolinea ={$this->getAerolinea()}";
        $consulta = $this->db->query($sql);      
        $fila=$consulta->fetch_object();                   
        //echo $sql . "<hr>" . $this->db->error; die();              
        return $fila;            
    } 

    public function updateOne(){
        $result=false;
        $sql="UPDATE pallet_articulos_aerolinea SET mallas={$this->getMallas()},
        											correas={$this->getCorreas()},
        											plasticobase={$this->getPlasticobase()},
        											plasticocapuchon={$this->getPlasticocapuchon()},
        											pernos={$this->getPernos()},
        											vinipel={$this->getVinipel()},
        											lazos={$this->getLazos()},
        											conectores={$this->getConectores()},
        											maderos={$this->getMaderos()}
        										WHERE id_articulosaerolinea = {$this->getIdArticulosAerolinea()}";
        $update=$this->db->query($sql);
        //echo $sql . "<hr>" . $this->db->error; die();            
        if ($update) {
            $result=true;
        }
        return $result;
    }

    public function updateAllByAerolinea(){
        $result=false;
        $sql="UPDATE pallet_articulos_aerolinea SET mallas={$this->getMallas()},
        											correas={$this->getCorreas()},
        											plasticobase={$this->getPlasticobase()},
        											plasticocapuchon={$this->getPlasticocapuchon()},
        											pernos={$this->getPernos()},
        											vinipel={$this->getVinipel()},
        											lazos={$this->getLazos()},
        											conectores={$this->getConectores()},
        											maderos={$this->getMaderos()}
        										WHERE aerolinea = {$this->getAerolinea()}";
        $update=$this->db->query($sql);
        //echo $sql . "<hr>" . $this->db->error; die();            
        if ($update) {
            $result=true;
        }
        return $result;
    }


}