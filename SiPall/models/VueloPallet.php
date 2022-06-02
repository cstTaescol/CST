<?php
class VueloPallet
{
	private $db;
	private $idVueloPallet;
	private $vuelo;
	private $pallet;		

	public function __construct()
	{
		$this->db=Database::connect();
	}

	public function getIdVueloPallet() {
	    return $this->idVueloPallet;
	}
	 
	public function setIdVueloPallet($idVueloPallet) {
	    $this->idVueloPallet = $idVueloPallet;
	}
	public function getVuelo() {
	    return $this->vuelo;
	}
	 
	public function setVuelo($vuelo) {
	    $this->vuelo = $vuelo;
	}
	public function getPallet() {
	    return $this->pallet;
	}
	 
	public function setPallet($pallet) {
	    $this->pallet = $pallet;
	}

    public function save(){
        $result=false;
        $sql="INSERT INTO pallet_vuelo_pallet (vuelo,pallet) VALUES ({$this->getVuelo()},{$this->getPallet()})";            
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

    public function getVueloPallet($pallet) {    	
        $sql="SELECT vuelo FROM pallet_vuelo_pallet WHERE pallet={$pallet} ORDER BY id_vuelopallet DESC";
        $consulta = $this->db->query($sql);
        $fila=$consulta->fetch_object();    
        //echo $sql . "<hr>" . $this->db->error; die();            
        return $fila;            
    }

    public function getPalletVuelo() {    	
        $sql="SELECT * FROM pallet_vuelo_pallet WHERE vuelo={$this->getVuelo()}";
        $consulta = $this->db->query($sql);        
        //echo $sql . "<hr>" . $this->db->error; die();            
        return $consulta;            
    }    
}

?>