<?php

class AlgorithmLink extends BaseModel{

	public $id, $algorithmfrom_id, $algorithmto_id;

	public function __construct($attributes){
    	parent::__construct($attributes);
  }

  public function save(){
    $query = DB::connection()->prepare('INSERT INTO Algorithmlink (algorithmfrom_id, algorithmto_id) VALUES (:algorithm_id, :algorithmto_id) RETURNING id');

    $query->execute(array('algorithm_id' => $this->algorithmfrom_id, 'algorithmto_id' => $this->algorithmto_id));
    $row = $query->fetch();
    $this->id = $row['id'];
  }

  public static function saveByAlgorithmId($algorithmfrom_id, $algorithmNames){
    
    foreach($algorithmNames as $algoName) {
      $query = DB::connection()->prepare('SELECT id FROM Algorithm WHERE name= :algoName');
    
      $query->execute(array('algoName' => $algoName));
        $algoId = $query->fetch();
        $algorithmLink = new AlgorithmLink(array('algorithmfrom_id' => $algorithmfrom_id, 'algorithmto_id' => $algoId));
        
      $algorithmLink->save();
    }
  }
}