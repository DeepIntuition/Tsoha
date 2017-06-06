<?php

class Algorithm extends BaseModel{
  
  public $id, $class, $name, $timecomplexity, $year, $author, $description, $implementations, $analyses, $similar, $tags;
  
  	public function __construct($attributes){
    	parent::__construct($attributes);
  	}

    public function save(){
      $query = DB::connection()->prepare('INSERT INTO Algorithm (class_id, name, timecomplexity, year, author, description) VALUES ((SELECT id FROM Class WHERE name= :class), :name, :timecomplexity, :year, :author, :description) RETURNING id');
      $query->execute(array('class' => $this->class, 'name' => $this->name, 'timecomplexity' => $this->timecomplexity, 'year' => $this->year, 'author' => $this->author, 'description' => $this->description));
      $row = $query->fetch();
      $this->id = $row['id'];

      if($this->tags != NULL) {
        Tagobject::saveByAlgorithmId($this->id, $this->tags);
      }

      if($this->similar != NULL) {
        Algorithmlink::saveByAlgorithmId($this->id, $this->similar);
      }
    }

    public function newTagNames() {
      $allTags = Tag::fetchNames();
      $newTags = array();
      
      foreach ($this->tags as $tag) {
        if(!(in_array($tag, $allTags))) {
          $newTags[] = $tag;
        }
      }      

      return $newTags;
    }

  	public static function fetchAll(){
  		$query = DB::connection()->prepare('SELECT * FROM Algorithm');
 
  		$query->execute();
  		$rows = $query->fetchAll();
    	$algorithms = array();

    	foreach ($rows as $row) {
    		$row_id = $row['id'];
    		$implementations = Algorithm::fetchImplementedLanguages($row_id);
        $class = Algorithm::fetchClass($row_id);

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
   	$query = DB::connection()->prepare('SELECT Implementation.programminglanguage AS language FROM Algorithm, Implementation WHERE Algorithm.id = Implementation.algorithm_id AND Algorithm.id= :algorithm_id');

 		$query->execute(array('algorithm_id' => $algorithm_id));
  	$rows = $query->fetchAll();
    	
    foreach ($rows as $row) {
    	$implementations[] = $row['language'];
    }

    return $implementations;
  }

  public static function fetchAnalyses($algorithm_id){

    $analyses = array();
   	$query = DB::connection()->prepare('SELECT Contributor.name AS added_by FROM Algorithm, Analysis, Contributor WHERE Algorithm.id = analysis.algorithm_id AND Contributor.id = analysis.contributor_id AND Algorithm.id= :algorithm_id');

 	  $query->execute(array('algorithm_id' => $algorithm_id));
		$rows = $query->fetchAll();
    	
  	foreach ($rows as $row) {
  		$analyses[] = $row['added_by'];
  	}

    return $analyses;
  }

  public static function fetchSingleAlgorithm($algorithm_id){
  	$query = DB::connection()->prepare('SELECT * FROM Algorithm WHERE id= :algorithm_id');
  	$query->execute(array('algorithm_id' => $algorithm_id));
  	$row = $query->fetch();

  	if($row){
			$row_id = $row['id'];
    		
    	$implementations = Algorithm::fetchImplementedLanguages($row_id);
    	$analyses = Algorithm::fetchAnalyses($row_id);
      $similar = Algorithm::fetchSimilar($row_id);
      $class = Algorithm::fetchClass($row_id);
      $tags = Algorithm::fetchTags($row_id);


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
    $query = DB::connection()->prepare('SELECT Algorithm.name AS algorithm, Algorithm.id AS id FROM Algorithmlink, Algorithm WHERE Algorithmlink.algorithmto_id = Algorithm.id AND Algorithmlink.algorithmfrom_id = :algorithm_id');

    $query->execute(array('algorithm_id' => $algorithm_id));
    $rows = $query->fetchAll();
    $similar = array();

    foreach ($rows as $row) {
      $similar[] = $row['algorithm'];        
    }

    return $similar;
  } 

  public static function fetchClass($algorithm_id){
    $query = DB::connection()->prepare('SELECT Class.name AS name FROM Algorithm, Class WHERE Algorithm.class_id = Class.id AND Algorithm.id = :algorithm_id');

    $query->execute(array('algorithm_id' => $algorithm_id));
    $row = $query->fetch();
    $class = array();

    if($row){      
      $class = $row['name'];
    }

    return $class;
  }

  public static function fetchTags($algorithm_id){
    $query = DB::connection()->prepare('SELECT Tag.name AS tag, Tagobject.tag_id AS id FROM Tagobject, Tag WHERE Tagobject.tag_id = Tag.id AND Tagobject.algorithm_id = :algorithm_id');

    $query->execute(array('algorithm_id' => $algorithm_id));
    $rows = $query->fetchAll();
    $tags = array();

    foreach ($rows as $row) {
      $tags[] = $row['tag'];
    }

    return $tags;
  }

  public static function fetchNames() {
    $query = DB::connection()->prepare('SELECT name FROM Algorithm');
    $query->execute();

    $rows = $query->fetchAll();
    $names = array();

    foreach ($rows as $row) {
      $names[] = $row['name'];
    }

    return $names;
  }
}	
