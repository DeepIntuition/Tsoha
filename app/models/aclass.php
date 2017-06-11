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
}