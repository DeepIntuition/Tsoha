<?php

class Algorithm extends BaseModel{
  
  public $id, $class_id, $name, $timecomplexity, $year, $author, $description, $implementations, $analyses;
  
  	public function __construct($attributes){
    	parent::__construct($attributes);
  	}

  	public static function fetchAll(){
  		$query = DB::connection()->prepare('SELECT * FROM Algorithm');

  		$query->execute();
  		$rows = $query->fetchAll();
    	$algorithms = array();

    	foreach ($rows as $row) {
    		$row_id = $row['id'];
    		$implementations = fetchImplementedLanguages($row_id);

    		$algorithms[] = new Algorithm(array(
    			'id' => $row_id,
    			'class_id' => $row['class_id'],
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
   		$query = DB::connection()->prepare('SELECT Implementation.programminglanguage AS Language FROM Algorithm, Implementation WHERE Algorithm.id = Implementation.algorithm_id AND Algorithm.id= :algorithm_id');

 		$query->execute(array('algorithm_id' => $algorithm_id));
  		$rows = $query->fetchAll();
    	
    	foreach ($rows as $row) {
    		$implementations[] = $row['Language'];
    	}

    	return $implementations;
  	}

  	public static function fetchAnalyses($algorithm_id){

    	$analyses = array();
   		$query = DB::connection()->prepare('SELECT Contributor.name AS added_by FROM Algorithm, Implementation, Contributor WHERE Algorithm.id = Implementation.algorithm_id AND Contributor.id = Implementation.contributor_id AND Algorithm.id= :algorithm_id');

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
    		
    		$implementations = fetchImplementations($row_id);
    		$analyses = fetchAnalyses($row_id);

    		$algorithm = new Algorithm(array(
    			'id' => $row_id,
    			'class_id' => $row['class_id'],
    			'name' => $row['name'],
    			'timecomplexity' => $row['timecomplexity'],
    			'year' => $row['year'],
    			'author' => $row['author'],
    			'description' => $row['description'],
    			'implementations' => $implementations,
    			'analyses' => $analyses
    		));

        return $algorithm;  
  		}
  	}
  }	
}