<?php

class AlgorithmController extends BaseController{

  public static function store() {
    $params = $_POST;
    $tags = array();
    $similar = array();
      
    if(isset($params['tags']))  {
      $tags = $params['tags'];  
    }
    if(isset($params['inputTags']))  {
      Tag::saveNewTags($params['inputTags']);
      $tags = array_merge($tags, $params['inputTags']);
    }
    if(isset($params['similar'])) {
      $similar = $params['similar'];  
    }
    
    $attributes = array(
      'name' => $params['name'],
      'class' => $params['class'],
      'timecomplexity' => $params['timecomplexity'],
      'year' => $params['year'],
      'tags' => $tags,
      'author' => $params['author'],
      'description' => $params['description'],
      'similar' => $similar 
      );

    $algorithm = new Algorithm($attributes);
    $errors = $algorithm->errors();

    if(count($errors) == 0){
      $algorithm->save();
      Redirect::to('/algorithm/' . $algorithm->id, array('message' => 'Algorithm has been successfully added to the database!'));
    } else {
      View::make('algorithm/new.html', array('errors' => $errors, 'attributes' => $attributes));
    }
  }

  public static function edit($id){
    $algorithm = Algorithm::fetchSingleAlgorithm($id);
    $params = AlgorithmController::fetchGlobalParams();  

    View::make('algorithm/algorithm_modify.html', array('algorithm' => $algorithm, 'params' => $params));
  }

  public static function update($id){
    $params = $_POST;

    $algorithm = new Algorithm(array(
      'name' => $params['name'],
      'class' => $params['class'],
      'timecomplexity' => $params['timecomplexity'],
      'year' => $params['year'],
      'tags' => $tags,
      'author' => $params['author'],
      'description' => $params['description'],
      'similar' => $similar 
      ));

    $errors = $algorithm->errors();
    // Kint::dump($errors);
    
    
    if(count($errors) > 0){
      View::make('algorithm/algorithm_modify.html', array('errors' => $errors, 'attributes' => $attributes));
    }else{
      $algorithm->update();

      Redirect::to('/algorithm/:id' . $algorithm->id, array('message' => 'Algorithm was successfully modified!'));
    }
    
  }

  public static function home(){
    if(!isset($_SESSION['user'])){
      $_SESSION['user'] = null;                 
    }
    View::make('home.html');
  }

  public static function new(){
    $params = AlgorithmController::fetchGlobalParams();          
    View::make('algorithm/new.html', array('params' => $params));
  }

  public static function delete($id){
    $algorithm = new Algorithm(array('id' => $id));
    $algorithm->delete();

    Redirect::to('/index', array('message' => 'Algorithm has been removed successfully!'));
  }

  public static function index(){      
    $algorithms = Algorithm::fetchAll();
    View::make('algorithm/index.html', array('algorithms' => $algorithms));
  }

  public static function algorithm_show($id){
    $algorithm = Algorithm::fetchSingleAlgorithm($id);
    View::make('algorithm/algorithm_show.html', array('algorithm' => $algorithm));
  }

  public static function implementation_show(){
    View::make('suunnitelmat/implementation_show.html');
  }

  public static function implementation_modify(){
    View::make('suunnitelmat/implementation_modify.html');
  }

  private static function fetchGlobalParams(){
    $algorithms = Algorithm::fetchNames();
    $tags = Tag::fetchNames();
    $classes = AClass::fetchNames();      

    $params = array(
      'algorithms' => $algorithms,
      'tags' => $tags,
      'classes' => $classes
      );

    return $params;           
  } 
}
