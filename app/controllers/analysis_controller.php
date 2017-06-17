<?php

class AnalysisController extends BaseController{

  public static function store($algorithm_id) {
    self::check_logged_in();
    $user = self::get_user_logged_in();
    $params = $_POST;
    $attributes = array(
      'algorithm_id' => $algorithm_id,
      'contributor_id' => $user->id,
      'timecomplexity' => $params['timecomplexity'],
      'description' => $params['description']
      );

    $Analysis = new Analysis($attributes);
    // $errors = $Analysis->errors();
    $errors = array();

    if(count($errors) == 0){
      $Analysis->save();
      Redirect::to('/algorithm/:id' . $algorithm_id, array('message' => 'Analysis has been successfully added to the database!'));
    } else {
      View::make('analysis/new.html', array('errors' => $errors, 'attributes' => $attributes));
    }
  }

  public static function edit($id){
    self::check_logged_in();
    $Analysis = Analysis::fetchSingleAnalysis($id);
    $params = AnalysisController::fetchGlobalParams();  

    View::make('Analysis/Analysis_modify.html', array('Analysis' => $Analysis, 'params' => $params));
  }

  public static function update($id){
    self::check_logged_in();
    $params = $_POST;

    $Analysis = new Analysis(array(
      'name' => $params['name'],
      'class' => $params['class'],
      'timecomplexity' => $params['timecomplexity'],
      'year' => $params['year'],
      'tags' => $tags,
      'author' => $params['author'],
      'description' => $params['description'],
      'similar' => $similar 
      ));

    $errors = $Analysis->errors();
    // Kint::dump($errors);
    
    
    if(count($errors) > 0){
      View::make('Analysis/Analysis_modify.html', array('errors' => $errors, 'attributes' => $attributes));
    }else{
      $Analysis->update();

      Redirect::to('/algorithm/:id' . $id, array('message' => 'Analysis was successfully modified!'));
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

    Redirect::to('/algorithm/:id' . $algorithm_id, array('message' => 'Analysis has been removed successfully!'));
  }
 
}
