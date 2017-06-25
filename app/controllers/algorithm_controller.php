<?php

class AlgorithmController extends BaseController{

  public static function home(){
    if(!isset($_SESSION['user'])){
      $_SESSION['user'] = null;
    }
    View::make('home.html');
  }

  public static function algorithm_show($id){
    $algorithm = Algorithm::fetchSingleAlgorithm($id);
    View::make('algorithm/algorithm_show.html', array('algorithm' => $algorithm));
  }

  public static function new(){
    self::check_logged_in();
    $redirect_path = "/index";
    self::check_administrator_rights($redirect_path);

    $params = AlgorithmController::fetchGlobalParams();          
    View::make('algorithm/new.html', array('params' => $params));
  }


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

    $timecomplexity = $params['timecomplexity'];

    if(strlen($params['timecomplexity_other']) > 2) {
      $timecomplexity = $params['timecomplexity_other'];  
    }

    $attributes = array(
      'name' => $params['name'],
      'class' => $params['class'],
      'timecomplexity' => $timecomplexity,
      'year' => $params['year'],
      'tags' => $tags,
      'author' => $params['author'],
      'description' => $params['description'],
      'similar' => $similar 
      );

    $algorithm = new Algorithm($attributes);
    
    $errors = $algorithm->errors();

    if(count($errors) > 0){
      $tags_single_string = "";

      $i = 0;
      foreach ($tags as $tag) {
        if($i == (count($tags) - 1)){
          $tags_single_string = $tags_single_string . $tag;  
        }else{
          $tags_single_string = $tags_single_string . $tag . ", ";
        }
        $i += 1;  
      }
      $params = AlgorithmController::fetchGlobalParams();
      View::make('algorithm/new.html', array('errors' => $errors, 'attributes' => $attributes, 'tags_single_string' => $tags_single_string, 'params' => $params));
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

    $tags_single_string = self::array_to_string($algorithm->tags);

    $params = AlgorithmController::fetchGlobalParams();  
    View::make('algorithm/algorithm_modify.html', array('algorithm' => $algorithm, 'params' => $params, 'tags_single_string' => $tags_single_string));
  }

  public static function update($id){
    self::check_logged_in();
    $redirect_path = "/algorithm/" . $id;
    self::check_administrator_rights($redirect_path);
    
    $params = $_POST;
    $tags = array();
    $similar = array();
    
    if(isset($params['similar'])){
      $similar = $params['similar'];
    }

    $tags = AlgorithmController::updateMergeTags($params); 

    $algorithm = new Algorithm(array(
      'id' => $id,
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

      Redirect::to('/algorithm/' . $algorithm->id, array('message' => 'Algorithm was successfully modified!'));
    }
    
  }
  
  public static function delete($id){
    self::check_logged_in();
    $redirect_path = "/algorithm/" . $id;
    self::check_administrator_rights($redirect_path);

    $algorithm = new Algorithm(array('id' => $id));
    $algorithm->delete();

    Redirect::to('/index', array('message' => 'Algorithm has been removed successfully!'));
  }

  public static function deleteLink($algorithm_id, $algorithm_to_id){
    self::check_logged_in();
    AlgorithmLink::deleteLink($algorithm_id, $algorithm_to_id);
    Redirect::to('/algorithm/modify/' . $algorithm_id, array('message' => 'Link removed.'));
  }

  public static function index(){      
    $algorithms = Algorithm::fetchAll();
    View::make('algorithm/index.html', array('algorithms' => $algorithms));
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
    $allTags = Tag::fetchNames();

    if(isset($params['tags']))  {
      $tags = $params['tags'];  
    }
    if($params['inputTags']['0'] != null)  {
      $separatedNewTags = explode(",", $params['inputTags'][0]);
      $checkedNewTags = array();
      foreach ($separatedNewTags as $string) {
        $string = trim($string);
        # Check in case of duplicates
        if(!in_array($string, $allTags)){
          $checkedNewTags[] = trim($string);  
        }
      }
      Tag::saveNewTags($checkedNewTags);
      $tags = array_merge($tags, $checkedNewTags);
    }
    return $tags;
  }

  private static function updateMergeTags($params){
    $tags = array();
    $allTags = Tag::fetchNames();

    if(isset($params['tags']))  {
      $tags = $params['tags'];  
    }
    if($params['inputTags']['0'] != null)  {
      $separatedNewTags = explode(",", $params['inputTags'][0]);
      $uniqueTags = array();
      $otherTags = array();

      foreach ($separatedNewTags as $string) {
        $string = trim($string);
        # Take out duplicates in case of double adding
        if(!in_array($string, $tags)){
          $otherTags[] = $string;  
        }

        # Differentiate those that are not in the DB
        if(!in_array($string, $allTags)){
          $uniqueTags[] = $string;  
        }
      }
      Tag::saveNewTags($uniqueTags);
      $tags = array_merge($tags, $othertags);
      $tags = array_merge($tags, $uniqueTags);
    }
    return $tags;
  }

  private static function array_to_string($stringarray){
    $tags_single_string = "";

    foreach ($stringarray as $tag) {
      $tags_single_string = $tags_single_string . $tag->name . ", ";  
    }

    return $tags_single_string;
  }
}
