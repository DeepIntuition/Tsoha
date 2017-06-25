<?php

class AlgorithmLink extends BaseModel{

	public $id, $algorithmfrom_id, $algorithmto_id;

	public function __construct($attributes){
    	parent::__construct($attributes);
  }

  public static function fetchByAlgorithmId($algorithm_id){
    $query = DB::connection()->prepare('
      SELECT * FROM Algorithmlink 
        WHERE algorithmfrom_id= :algorithm_id');
    $query->execute(array('algorithm_id' => $algorithm_id));
    $rows = $query->fetchAll();
    $queriedLinks = array();

    foreach ($rows as $row) {
      $queriedLinks[] = new AlgorithmLink(array(
        'id' => $row['id'],  
        'algorithmfrom_id' => $algorithm_id,  
        'algorithmto_id' => $row['algorithmto_id']
      ));
    }

    return $queriedLinks;
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

  public static function saveByName($algorithm_id, $algorithmNames){
    foreach($algorithmNames as $name) {
      $query = DB::connection()->prepare('INSERT INTO Algorithmlink (algorithmfrom_id, algorithmto_id) VALUES (:algorithm_id, (SELECT id FROM Algorithm WHERE name= :name))');
      $query->execute(array('algorithm_id' => $algorithm_id, 'name' => $name));
    }
  }

  public static function deleteByAlgorithmId($algorithm_id){
    $query = DB::connection()->prepare('DELETE FROM Algorithmlink WHERE algorithmfrom_id= :algorithm_id');
    $query->execute(array('algorithm_id' => $algorithm_id));
  }

  public static function deleteLink($algorithm_id, $algorithm_to_id){
    $query = DB::connection()->prepare('
      DELETE FROM Algorithmlink 
        WHERE algorithmfrom_id= :algorithm_id 
        AND algorithmto_id= :algorithm_to_id');
    $query->execute(array(
      'algorithm_id' => $algorithm_id,
      'algorithm_to_id' => $algorithm_to_id));
  }

  public static function update($algorithm_id, $algorithmNames){
    AlgorithmLink::deleteByAlgorithmId($algorithm_id);
    AlgorithmLink::saveByName($algorithm_id, $algorithmNames);
  }
}