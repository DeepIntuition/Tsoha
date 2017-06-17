<?php

class Analysis extends BaseModel{
	public $id, $name, $algorithm_id, $contributor_id, $timecomplexity, $date, $description;

	public function __construct($attributes){
		parent::__construct($attributes);
	}

	public static function fetchAnalyses($algorithm_id){
   		$query = DB::connection()->prepare('
	   		SELECT Analysis.id AS id,
	   			Contributor.name AS added_by, 
	   			Analysis.contributor_id AS cont_id, 
				Analysis.timecomplexity AS time_comp, 
				Analysis.date AS date_added, 
				Analysis.description AS description 
	   		FROM Algorithm, Analysis, Contributor WHERE Algorithm.id = analysis.algorithm_id AND Contributor.id = analysis.contributor_id AND Algorithm.id= :algorithm_id');

 	  	$query->execute(array('algorithm_id' => $algorithm_id));
		$rows = $query->fetchAll();
    	$analyses = array();

  		foreach ($rows as $row) {
  			$analyses[] = new Analysis(array(
 	       	'id' => $row['id'],
 	       	'name' => $row['added_by'],
 	       	'algorithm_id' => $algorithm_id,
 	       	'contributor_id' => $row['cont_id'],
 	       	'timecomplexity' => $row['time_comp'],
 	       	'date' => $row['date_added'],
 	       	'description' => $row['description']
 	       	));
  		}

  		return $analyses;
  	}

  	public function delete(){
    	$query = DB::connection()->prepare('
      		DELETE FROM Analysis WHERE id= :id');
    	$query->execute(array('id' => $this->id));
  	}

  	public function save(){
  		$query = DB::connection()->prepare('
      		INSERT INTO Analysis (algorithm_id, contributor_id, timecomplexity, description, date)
      			VALUES (:algorithm_id, :contributor_id, :timecomplexity, :description, CURRENT_DATE)
      			RETURNING id, date');

    	$query->execute(array(
    		'algorithm_id' => $this->algorithm_id,
    		'contributor_id' => $this->contributor_id,
    		'timecomplexity' => $this->timecomplexity,
    		'description' => $this->description
    		));

    	$row = $query->fetch();
      	$this->id = $row['id'];
      	$this->date = $row['date'];
  	}

  	public static function deleteByAlgorithmId($algorithm_id){
	    $query = DB::connection()->prepare('DELETE FROM Analysis WHERE algorithm_id= :algorithm_id');
	    $query->execute(array('algorithm_id' => $algorithm_id));
	}
}