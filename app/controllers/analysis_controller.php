<?php

class AnalysisController extends BaseController{

  public static function store($algorithms_id) {
    self::check_logged_in();
    $user = self::get_user_logged_in();
    $params = $_POST;
    $errors = array();

    $timecomplexity = "";
    if((strlen($params['timecomplexity_other']) > 0) && $params['timecomplexity'] == 'Other (write below)'){
      $timecomplexity = $params['timecomplexity_other'];
    }elseif($params['timecomplexity_other'] == "" && isset($params['timecomplexity'])) {
      $timecomplexity = $params['timecomplexity'];
    }else{
      $errors[] = AnalysisController::handleErrorMessage($params);   
    }

    $attributes = array(
      'algorithm_id' => $algorithms_id,
      'contributor_id' => $user->id,
      'timecomplexity' => $timecomplexity,
      'description' => $params['description']
      );

    $analysis = new Analysis($attributes);
    $errors = array_merge($errors, $analysis->errors());

    if(count($errors) == 0){
      $analysis->save();
      Redirect::to('/algorithm/' . $algorithms_id, array('message' => 'Analysis has been successfully added to the database!'));
    }else{
      View::make('analysis/new.html', array('errors' => $errors, 'attributes' => $attributes));
    }
  }

  public static function edit($algorithm_id, $analysis_id){
    self::check_logged_in();
    $analysis = Analysis::fetch($analysis_id);
    View::make('analysis/analysis_modify.html', array('analysis' => $analysis));
  }

  public static function update($algorithm_id, $analysis_id){
    self::check_logged_in();
    $params = $_POST;
    $errors = array();

    $timecomplexity = "";
    if((strlen($params['timecomplexity_other']) > 0) && $params['timecomplexity'] == 'Other (write below)'){
      $timecomplexity = $params['timecomplexity_other'];
    }elseif($params['timecomplexity_other'] == "" && isset($params['timecomplexity'])) {
      $timecomplexity = $params['timecomplexity'];
    }else{
      $errors[] = AnalysisController::handleErrorMessage($params);   
    }

    $attributes = array(
      'id' => $analysis_id,
      'algorithm_id' => $algorithm_id,
      'contributor_id' => $params['contributor_id'],
      'timecomplexity' => $timecomplexity,
      'description' => $params['description']
      );

    $analysis = new Analysis($attributes);
    $errors = array_merge($errors, $analysis->errors());
    
    if(count($errors) > 0){
      View::make('analysis/analysis_modify.html', array('errors' => $errors, 'analysis' => $attributes, 'timecomplexity_other' => $params['timecomplexity_other']));
    }else{
      $analysis->update();

      Redirect::to('/algorithm/' . $algorithm_id, array('message' => 'Analysis was successfully modified!'));
    }
  }

  public static function new($algorithm_id){
    self::check_logged_in();
    View::make('analysis/new.html', array('algorithm_id' => $algorithm_id));
  }

  public static function delete($id, $algorithm_id){
    self::check_logged_in();
    $analysis = new Analysis(array('id' => $id));
    $analysis->delete();

    Redirect::to('/algorithm/' . $algorithm_id, array('message' => 'Analysis has been removed successfully!'));
  }

  private static function handleErrorMessage($params){
    return "Can't choose both custom time complexity classification - " . $params['timecomplexity_other'] . " and one from the dropdown select menu at the same time. If you want to add a custom time complexity classification, select 'Other (write below)' from the dropdown selector and write your input to the field: Choose other";
  }
}
