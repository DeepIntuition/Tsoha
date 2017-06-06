<?php

class Tag extends BaseModel{

	public $id, $name;

	public function __construct($attributes){
    	parent::__construct($attributes);
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
  		$query = DB::connection()->prepare('INSERT INTO Tag (name) VALUES (:name) RETURNING id');

      	$query->execute(array('name' => $this->name));
      	$row = $query->fetch();
      	$this->id = $row['id'];
  	}

  	public static function saveNewTags($newTags){
  		foreach ($newTags as $key) {
  			$newTag = new Tag(array('name' => $key));
  			$newTag->save();
  		}
  	}
}