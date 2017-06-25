<?php

class Implementation extends BaseModel{
	public $id, $name, $algorithm_id, $contributor_id, $planguage, $date, $description;

	public function __construct($attributes){
		parent::__construct($attributes);
    $this->validators = array('validate_description','validate_programminglanguage', 'validate_contributor');
	}

	public static function fetchByAlgorithm($algorithm_id){
 		$query = DB::connection()->prepare('
   		SELECT Implementation.id AS id,
   			Contributor.name AS added_by, 
   			Implementation.contributor_id AS cont_id, 
  			Implementation.programminglanguage AS planguage, 
  			Implementation.date AS date_added, 
  			Implementation.description AS description 
     		FROM Algorithm, Implementation, Contributor 
        WHERE Algorithm.id = Implementation.algorithm_id 
        AND Contributor.id = Implementation.contributor_id 
        AND Algorithm.id= :algorithm_id');

	  $query->execute(array('algorithm_id' => $algorithm_id));
	  $rows = $query->fetchAll();
  	$implementations = array();

		foreach ($rows as $row) {
			$implementations[] = new Implementation(array(
	       	'id' => $row['id'],
	       	'name' => $row['added_by'],
	       	'algorithm_id' => $algorithm_id,
	       	'contributor_id' => $row['cont_id'],
	       	'planguage' => $row['planguage'],
	       	'date' => $row['date_added'],
	       	'description' => $row['description']
	       	));
		}

		return $implementations;
	}

  public static function fetch($id){
    $query = DB::connection()->prepare('
      SELECT Algorithm.id AS algorithm_id, 
        Contributor.name AS added_by, 
        Implementation.contributor_id AS cont_id, 
        Implementation.programminglanguage AS planguage, 
        Implementation.date AS date_added, 
        Implementation.description AS description 
        FROM Algorithm, Implementation, Contributor 
        WHERE Algorithm.id = Implementation.algorithm_id 
        AND Contributor.id = Implementation.contributor_id 
        AND Implementation.id= :Implementation_id');

    $query->execute(array('Implementation_id' => $id));
    $row = $query->fetch();

    if($row){
      $Implementation = new Implementation(array(
        'id' => $id,
        'name' => $row['added_by'],
        'algorithm_id' => $row['algorithm_id'],
        'contributor_id' => $row['cont_id'],
        'planguage' => $row['planguage'],
        'date' => $row['date_added'],
        'description' => $row['description']
      ));
    }

    return $Implementation;
  }

  public function save(){
    $query = DB::connection()->prepare('
        INSERT INTO Implementation (algorithm_id, contributor_id, programminglanguage, description, date)
          VALUES (:algorithm_id, :contributor_id, :programminglanguage, :description, CURRENT_DATE)
          RETURNING id, date');

    $query->execute(array(
      'algorithm_id' => $this->algorithm_id,
      'contributor_id' => $this->contributor_id,
      'programminglanguage' => $this->planguage,
      'description' => $this->description
      ));

    $row = $query->fetch();
      $this->id = $row['id'];
      $this->date = $row['date'];
  }

  public function update(){
    $query = DB::connection()->prepare('
        UPDATE Implementation SET 
          programminglanguage = :programminglanguage, 
          description = :description
        WHERE id= :implementation_id 
        AND contributor_id= :contributor_id');

    $query->execute(array(
      'programminglanguage' => $this->planguage,
      'description' => $this->description,
      'implementation_id' => $this->id,
      'contributor_id' => $this->contributor_id
      ));
  }

  public function delete(){
    	$query = DB::connection()->prepare('
      		DELETE FROM Implementation WHERE id= :id');
    	$query->execute(array('id' => $this->id));
  }

  public static function deleteByAlgorithmId($algorithm_id){
    $query = DB::connection()->prepare('DELETE FROM Implementation WHERE algorithm_id= :algorithm_id');
    $query->execute(array('algorithm_id' => $algorithm_id));
  }

  public function validate_description(){
    $errors = array();
    $string_max_length = 4000;

    $errors = array_merge($errors, $this->validate_not_null($this->description));
    $errors = array_merge($errors, $this->validate_string_max_length($this->description, $string_max_length));
    
    return $errors;
  }

  public function validate_programminglanguage(){
    $errors = array();
    $string_max_length = 30;

    $errors = array_merge($errors, $this->validate_not_null($this->planguage));
    $errors = array_merge($errors, $this->validate_string_max_length($this->planguage, $string_max_length));
    
    return $errors;
  }

  public function validate_contributor(){
    $errors = array();
    $string_max_length = 30;

    if(!User::fetchUserById($this->contributor_id)){
      $errors[] = "User does not exist!";
    }    
    return $errors;
  }
}