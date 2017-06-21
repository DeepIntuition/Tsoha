<?php

class AlgorithmController extends BaseController{

  public static function store() {
    self::check_logged_in();
    $redirect_path = "/index";
    self::check_administrator_rights($redirect_path);

    $params = $_POST;
    $tags = array();
    $similar = array();
      
    $tags = self::mergeTags($params);    
    
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

    if(count($errors) > 0){
      View::make('algorithm/new.html', array('errors' => $errors, 'attributes' => $attributes));
    } else {
      $algorithm->save();
      Redirect::to('/algorithm/' . $algorithm->id, array('message' => 'Algorithm has been successfully added to the database!'));
    }
  }

  public static function edit($id){
    self::check_logged_in();
    $redirect_path = "/algorithm/" . $id;
    self::check_administrator_rights($redirect_path);
    
    $algorithm = Algorithm::fetchSingleAlgorithm($id);
    $params = AlgorithmController::fetchGlobalParams();  

    View::make('algorithm/algorithm_modify.html', array('algorithm' => $algorithm, 'params' => $params));
  }

  public static function update($id){
    self::check_logged_in();
    $redirect_path = "/algorithm/" . $id;
    self::check_administrator_rights($redirect_path);
    
    $params = $_POST;
    $tags = array();
    $similar = array();
      
    $tags = self::mergeTags($params); 

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
    self::check_logged_in();
    $redirect_path = "/index";
    self::check_administrator_rights($redirect_path);

    $params = AlgorithmController::fetchGlobalParams();          
    View::make('algorithm/new.html', array('params' => $params));
  }

  public static function delete($id){
    self::check_logged_in();
    $redirect_path = "/algorithm/" . $id;
    self::check_administrator_rights($redirect_path);

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
    $algorithms = Algorithm::fetchSimple();
    $tags = Tag::fetchNames();
    $classes = AClass::fetchNames();      

    $params = array(
      'algorithms' => $algorithms,
      'tags' => $tags,
      'classes' => $classes
      );

    return $params;           
  } 

  private static function mergeTags($params){
    $tags = array();
    if(isset($params['tags']))  {
      $tags = $params['tags'];  
    }
    if($params['inputTags']['0'] != null)  {
      $separatedNewTags = explode(",", $params['inputTags'][0]);

      $i = 0;
      foreach ($separatedNewTags as $string) {
        $separatedNewTags[$i] = trim($string);
        $i += 1;
      }
      
      $uniqueTags = Tag::saveNewTags($separatedNewTags);
      $tags = array_merge($tags, $uniqueTags);
    }
    return $tags;
  }

}
