<?php

class ImplementationController extends BaseController{

  public static function store($algorithm_id) {
    self::check_logged_in();
    $user = self::get_user_logged_in();
    $params = $_POST;
    $errors = array();
    $planguage = "";
    
    if((strlen($params['planguage_other']) > 0) && $params['planguage_select'] == 'Other (write below)'){
      $planguage = $params['planguage_other'];
    }elseif($params['planguage_other'] == "" && isset($params['planguage_select'])) {
      $planguage = $params['planguage_select'];
    }else{
      $errors[] = ImplementationController::handleErrorMessage($params);   
    }

    $attributes = array(
      'algorithm_id' => $algorithm_id,
      'contributor_id' => $user->id,
      'planguage' => $planguage,
      'description' => $params['description']
      );

    $implementation = new Implementation($attributes);
    $errors = array_merge($errors, $implementation->errors());

    if(count($errors) == 0){
      $implementation->save();
      Redirect::to('/algorithm/' . $algorithm_id . '/implementation/all', array('message' => 'Implementation has been successfully added to the database!'));
    } else {
      View::make('implementation/new.html', array('errors' => $errors, 'algorithm_id' => $algorithm_id, 'attributes' => $attributes));
    }
  }

  public static function edit($algorithm_id, $implementation_id){
    self::check_logged_in();
    $implementation = Implementation::fetch($implementation_id);
    View::make('implementation/modify.html', array('implementation' => $implementation));
  }

  public static function show($algorithm_id, $planguage){
    $all_implementations = Implementation::fetchByAlgorithm($algorithm_id);
    $algorithm = Algorithm::fetchSingleAlgorithm($algorithm_id);
    $implementations = array();
    
    if($planguage != 'all'){
      foreach ($all_implementations as $key) {
        if($key->planguage == $planguage) {
          $implementations[] = $key;
        }
      }  
    }else{
      $implementations = $all_implementations;
    }

    View::make('implementation/implementations_show.html', array('implementations' => $implementations, 'selected_planguage' => $planguage, 'algorithm' => $algorithm));
  }

  public static function update($algorithm_id, $implementation_id){
    self::check_logged_in();
    $user = self::get_user_logged_in();
    $params = $_POST;
    $errors = array();
    $planguage = "";

    if((strlen($params['planguage_other']) > 0) && $params['planguage_select'] == 'Other (write below)'){
      $planguage = $params['planguage_other'];
    }elseif($params['planguage_other'] == "" && isset($params['planguage_select'])) {
      $planguage = $params['planguage_select'];
    }else{
      $errors[] = ImplementationController::handleErrorMessage($params);   
    }

    $attributes = array(
      'id' => $implementation_id,
      'algorithm_id' => $algorithm_id,
      'contributor_id' => $user->id,
      'planguage' => $planguage,
      'description' => $params['description']
      );

    $implementation = new Implementation($attributes);
    $errors = array_merge($errors, $implementation->errors());
    
    if(count($errors) > 0){
      View::make('implementation/modify.html', array('errors' => $errors, 'implementation' => $attributes));
    }else{
      $implementation->update();
      Redirect::to('/algorithm/' . $algorithm_id . '/implementation/all', array('message' => 'Implementation was successfully modified!'));
    }
  }

  public static function new($algorithm_id){
    self::check_logged_in();
    View::make('implementation/new.html', array('algorithm_id' => $algorithm_id));
  }

  public static function delete($algorithm_id, $id){
    self::check_logged_in();
    $implementation = new Implementation(array('id' => $id));
    $implementation->delete();

    Redirect::to('/algorithm/' . $algorithm_id . '/implementation/all', array('message' => 'Implementation has been removed successfully!'));
  }

  private static function handleErrorMessage($params){
    return "Can't choose both custom programming language - " . $params['planguage_other'] . " and one from the dropdown select menu at the same time. If you want to add a custom programming language, select 'Other (write below)' from the dropdown selector and write your language to the field: Custom Language";
  }
 
}
