<?php

class Algorithm extends BaseModel{
  
  public $id, $class, $name, $timecomplexity, $year, $author, $description, $implementations, $analyses, $similar, $tags;
  
  	public function __construct($attributes){
    	parent::__construct($attributes);
      $this->validators = array('validate_name','validate_year','validate_description','validate_class');
  	}

    public function save(){
      $query = DB::connection()->prepare('
        INSERT INTO Algorithm (class_id, name, timecomplexity, year, author, description) 
          VALUES ((SELECT id FROM Class WHERE name= :class), :name, :timecomplexity, :year, :author, :description) RETURNING id');

      $query->execute(array('class' => $this->class, 'name' => $this->name, 'timecomplexity' => $this->timecomplexity, 'year' => $this->year, 'author' => $this->author, 'description' => $this->description));
      
      $row = $query->fetch();
      $this->id = $row['id'];

      if(isset($this->tags)) {
        Tagobject::saveByName($this->id, $this->tags);
      }
      if(isset($this->similar)) {
        AlgorithmLink::saveByName($this->id, $this->similar);
      }
    }

  	public static function fetchAll(){
  		$query = DB::connection()->prepare('SELECT * FROM Algorithm');
 
  		$query->execute();
  		$rows = $query->fetchAll();
    	$algorithms = array();

    	foreach ($rows as $row) {
    		$row_id = $row['id'];
    		$implementations = Algorithm::fetchImplementedLanguages($row_id);
        $class = AClass::fetchClassByAlgorithm($row_id);

    		$algorithms[] = new Algorithm(array(
    			'id' => $row_id,
    			'class' => $class,
    			'name' => $row['name'],
    			'timecomplexity' => $row['timecomplexity'],
    			'year' => $row['year'],
    			'author' => $row['author'],
    			'description' => $row['description'],
    			'implementations' => $implementations
    		));
    	}

    	return $algorithms;
    }

	public static function fetchImplementedLanguages($algorithm_id){

    $implementations = array();
   	$query = DB::connection()->prepare('
      SELECT Implementation.programminglanguage AS language FROM Algorithm, Implementation 
      WHERE Algorithm.id = Implementation.algorithm_id 
      AND Algorithm.id= :algorithm_id');

 		$query->execute(array('algorithm_id' => $algorithm_id));
  	$rows = $query->fetchAll();
    	
    foreach ($rows as $row) {
    	$implementations[] = $row['language'];
    }

    return $implementations;
  }

  public static function fetchSingleAlgorithm($algorithm_id){
  	$query = DB::connection()->prepare('
      SELECT * FROM Algorithm WHERE id= :algorithm_id');
  	$query->execute(array('algorithm_id' => $algorithm_id));
  	$row = $query->fetch();

  	if($row){
			$row_id = $row['id'];
    		
    	$implementations = Algorithm::fetchImplementedLanguages($row_id);
    	$analyses = Analysis::fetchAnalyses($row_id);
      $similar = Algorithm::fetchSimilar($row_id);
      $class = AClass::fetchClassByAlgorithm($row_id);
      $tags = Tag::fetchTagsByAlgorithm($row_id);


    	$algorithm = new Algorithm(array(
    		'id' => $row_id,
  			'class' => $class,
  			'name' => $row['name'],
  			'timecomplexity' => $row['timecomplexity'],
        'year' => $row['year'],
  			'tags' => $tags,
    		'author' => $row['author'],
    		'description' => $row['description'],
  			'implementations' => $implementations,
  			'analyses' => $analyses,
        'similar' => $similar 
    	));

      return $algorithm;
  	}
  }

  public static function fetchSimilar($algorithm_id){
    $query = DB::connection()->prepare('
      SELECT Algorithm.name AS algorithm, 
        Algorithm.id AS id FROM Algorithmlink, Algorithm 
      WHERE Algorithmlink.algorithmto_id = Algorithm.id AND Algorithmlink.algorithmfrom_id = :algorithm_id');

    $query->execute(array('algorithm_id' => $algorithm_id));
    $rows = $query->fetchAll();
    $similar = array();

    foreach ($rows as $row) {
      $similar[] = $row['algorithm'];        
    }

    return $similar;
  } 

  public static function fetchByTag($tag_id){
     $query = DB::connection()->prepare('
      SELECT Algorithm.id AS id, Algorithm.name AS name 
        FROM Algorithm, Tagobject 
        WHERE Tagobject.algorithm_id = Algorithm.id 
        AND Tagobject.tag_id = :tag_id');

    $query->execute(array('tag_id' => $tag_id));
    $rows = $query->fetchAll();
    $algorithms = array();

    foreach ($rows as $row) {
      $algorithms[] = new Algorithm(array(
        'id' => $row['id'],
        'name' => $row['name']
      ));
    }

    return $algorithms;
  }

  public static function fetchNames() {
    $query = DB::connection()->prepare('
      SELECT name FROM Algorithm');
    $query->execute();

    $rows = $query->fetchAll();
    $names = array();

    foreach ($rows as $row) {
      $names[] = $row['name'];
    }
    return $names;
  }

  public function delete(){
    $query = DB::connection()->prepare('
      DELETE FROM Algorithm WHERE id= :algorithm_id');
    $query->execute(array('algorithm_id' => $this->id));

    AlgorithmLink::deleteByAlgorithmId($this->id);
    Analysis::deleteByAlgorithmId($this->id);
    Tagobject::deleteByAlgorithmId($this->id);
  }

  public function update(){
    $query = DB::connection()->prepare('
        UPDATE Algorithm SET
          class_id = :class_id,
          name = :name,
          timecomplexity = :timecomplexity, 
          year = :year, 
          author = :author, 
          description = :description
        WHERE id= :algorithm_id');

    $query->execute(array(
      'algorithm_id' => $this->id,
      'class_id' => $this->class_id,
      'name' => $this->name,
      'timecomplexity' => $this->timecomplexity,
      'year' => $this->year,
      'author' => $this->author,
      'description' => $this->description));

    Tagobject::update($this->id, $this->tags);
    AlgorithmLink::update($this->id, $this->similar);
  }

  public function validate_name(){
    $errors = array();
    $name_max_length = 120; 
    $name_min_length = 5; 

    $errors = array_merge($errors, $this->validate_not_null($this->name));
    $errors = array_merge($errors, $this->validate_string_length_at_least($this->name, $name_min_length));
    $errors = array_merge($errors, $this->validate_string_max_length($this->name, $name_max_length));

    return $errors;
  }

  public function validate_year(){
    $errors = array();
    $string_max_length = 4;

    $errors = array_merge($errors, $this->validate_not_null($this->year));
    $errors = array_merge($errors, $this->validate_string_max_length($this->year, $string_max_length));
    $errors = array_merge($errors, $this->validate_string_length_at_least($this->year, $string_max_length));
    $errors = array_merge($errors, $this->validate_string_contains_only_numbers($this->year));          
      
    return $errors;
  }

  public function validate_class(){
    $errors = array();
    if(!AClass::contains($this->class)){
      $errors[] = "Class ".$this->class." does not exist!";
    }
    $errors = array_merge($errors, $this->validate_not_null($this->class));
    
    return $errors;
  } 

  public function validate_description(){
    $errors = array();
    $string_max_length = 4000;

    $errors = array_merge($errors, $this->validate_not_null($this->class));
    $errors = array_merge($errors, $this->validate_string_max_length($this->class, $string_max_length));
    
    return $errors;
  }
}	

