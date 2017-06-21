<?php

class AnalysisController extends BaseController{

  public static function store($algorithms_id) {
    self::check_logged_in();
    $user = self::get_user_logged_in();
    $params = $_POST;
    $attributes = array(
      'algorithm_id' => $algorithms_id,
      'contributor_id' => $user->id,
      'timecomplexity' => $params['timecomplexity'],
      'description' => $params['description']
      );

    $analysis = new Analysis($attributes);
    $errors = $analysis->errors();

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

    $attributes = array(
      'id' => $analysis_id,
      'algorithm_id' => $algorithm_id,
      'contributor_id' => $params['contributor_id'],
      'timecomplexity' => $params['timecomplexity'],
      'description' => $params['description']
      );

    $analysis = new Analysis($attributes);
    $errors = $analysis->errors();
    
    if(count($errors) > 0){
      View::make('analysis/analysis_modify.html', array('errors' => $errors, 'analysis' => $attributes));
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
    $Analysis = new Analysis(array('id' => $id));
    $Analysis->delete();

    Redirect::to('/algorithm/' . $algorithm_id, array('message' => 'Analysis has been removed successfully!'));
  }
 
}
