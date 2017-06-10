<?php

class Tagobject extends BaseModel{

	public $id, $algorithm_id, $tag_id;

	public function __construct($attributes){
    	parent::__construct($attributes);
  }

  public function save(){
    $query = DB::connection()->prepare('INSERT INTO Tagobject (algorithm_id, tag_id) VALUES ( :algorithm_id, :tag_id ) RETURNING id');

    $query->execute(array('algorithm_id' => $this->algorithm_id, 'tag_id' => $this->tag_id));

    $row = $query->fetch();
    $this->id = $row['id'];
  }

  public static function saveByAlgorithmId($algorithm_id, $tagNames){
    foreach($tagNames as $tagName) {
      $query = DB::connection()->prepare('SELECT id FROM Tag WHERE name= :tagName');
      
      //Kint::dump($tagNames);
      $query->execute(array('tagName' => $tagName));
      $tagId = $query->fetch();
      $tagObject = new Tagobject(array('algorithm_id' => $algorithm_id, 'tag_id' => $tagId));
        
      $tagObject->save();
    }
  }

  public static function saveByName($algorithm_id, $tagNames){
    foreach($tagNames as $tag) {
      $query = DB::connection()->prepare('INSERT INTO Tagobject (algorithm_id, tag_id) VALUES (:algorithm_id, (SELECT id FROM Tag WHERE name= :tagName))');
      $query->execute(array('algorithm_id' => $algorithm_id, 'tagName' => $tag));
    }
  }

  public static function deleteByAlgorithmId($algorithm_id){
    $query = DB::connection()->prepare('DELETE FROM Tagobject WHERE algorithm_id= :algorithm_id');
    $query->execute(array('algorithm_id' => $algorithm_id));
  }

  public static function update($algorithm_id, $tagNames){
    Tagobject::deleteByAlgorithmId($algorithm_id);
    Tagobject::saveByName($algorithm_id, $tagNames);
  }
}