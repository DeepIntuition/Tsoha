<?php

class AClass extends BaseModel{

	public $id, $name;

	public function __construct($attributes){
    	parent::__construct($attributes);
  }

  public static function fetchNames() {
		$query = DB::connection()->prepare('SELECT name FROM Class');
    $query->execute();

  	$rows = $query->fetchAll();
    $names = array();

  	foreach ($rows as $row) {
      $names[] = $row['name'];
    }

  	return $names;
	}

  public static function contains($name){
    $names = AClass::fetchNames();
    if (in_array($name, $names)) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public static function fetchClassByAlgorithm($algorithm_id){
    $query = DB::connection()->prepare('SELECT Class.name AS name FROM Algorithm, Class WHERE Algorithm.class_id = Class.id AND Algorithm.id = :algorithm_id');

    $query->execute(array('algorithm_id' => $algorithm_id));
    $row = $query->fetch();
    $class = array();

    if($row){      
      $class = $row['name'];
    }

    return $class;  
  }

  public function validate_class_name(){
    $errors = array();
    $name_string = $this->name;
    $errors = array_merge($errors, $this->validate_not_null($name_string));
    $errors = array_merge($errors, $this->validate_string_max_length($name_string, 50));
    if(!self::check_name_available($name_string)) {
      $errors[] = 'Class already exists!';
    }
    return $errors;
  }

  public function save(){
    $query = DB::connection()->prepare('
      INSERT INTO Class (name) 
      VALUES (:name) RETURNING id');

    $query->execute(array('name' => $this->name));
    $row = $query->fetch();
    $this->id = $row;
  }

  public static function check_name_available($class_name){
    $query = DB::connection()->prepare('
      SELECT name, id FROM Class  
      WHERE name= :name');

    $query->execute(array('name' => $class_name));
    $row = $query->fetch();

    if($row['id']) {
      return FALSE;
    }
    return TRUE;
  }
}