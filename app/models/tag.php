<?php

class Tag extends BaseModel{

	public $id, $name, $algorithms;

	public function __construct($attributes){
    parent::__construct($attributes);
  }

  public static function fetchAll() {
    $query = DB::connection()->prepare('SELECT * FROM Tag');

    $query->execute();
    $rows = $query->fetchAll();
    $tags = array();

    foreach($rows as $row) {
      $algorithms = Algorithm::fetchByTag($row['id']);
      $tags[] = new Tag(array(
        'id' => $row['id'],
        'name' => $row['name'],
        'algorithms' => $algorithms
        ));
    }
    
    return $tags;
  }

  public static function fetchNames() {
    $tags = array();
    $query = DB::connection()->prepare('SELECT name FROM Tag');
    $query->execute();
    $rows = $query->fetchAll();

    foreach ($rows as $row) {
      $tags[] = $row['name'];
    }

    return $tags;
  }

  public function save(){
    $query = DB::connection()->prepare('
      INSERT INTO Tag (name) 
      VALUES (:name) RETURNING id');

    $query->execute(array('name' => $this->name));
    $row = $query->fetch();
    $this->id = $row['id'];
  }

  public static function saveNewTags($newTags){
    $uniqueTags = array();
    foreach ($newTags as $key) {
      $newTag = new Tag(array('name' => $key));
      if($newTag->check_name_available()){
        $newTag->save();
        $uniqueTags[] = $newTag;
      }
    }
    return $uniqueTags;
  }

  public static function fetchTagsByAlgorithm($algorithm_id){
    $query = DB::connection()->prepare('
      SELECT Tag.name AS tag, Tagobject.tag_id AS id 
      FROM Tagobject, Tag 
      WHERE Tagobject.tag_id = Tag.id 
      AND Tagobject.algorithm_id = :algorithm_id
      ');

    $query->execute(array('algorithm_id' => $algorithm_id));
    $rows = $query->fetchAll();
    $tags = array();

    foreach ($rows as $row) {
      $tags[] = new Tag(array(
        'id' => $row['id'],
        'name' => $row['tag']
        ));
    }

    return $tags;
  }

  public static function fetchName($tag_id) {
    $query = DB::connection()->prepare('
      SELECT name FROM Tag 
      WHERE id= :tag_id');
    $query->execute(array('tag_id' => $tag_id));
    $row = $query->fetch();

    return $row['name'];
  }

  public function check_name_available(){
    $query = DB::connection()->prepare('
      SELECT name FROM Tag 
      WHERE name= :tag_name');

    $query->execute(array(
      'tag_name' => $this->name)); 

    $row = $query->fetch();
    if($row['name']){
      return FALSE;
    }else{
      return TRUE;
    }

  }
}
