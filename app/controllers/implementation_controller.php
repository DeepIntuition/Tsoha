<?php

class ImplementationController extends BaseController{

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

    $Implementation = new Implementation($attributes);
    // $errors = $Implementation->errors();
    $errors = array();

    if(count($errors) == 0){
      $Implementation->save();
      $algorithm = new Algorithm(array('id' => $algorithm_id));
      Redirect::to('/algorithm/:id' . $algorithm->id, array('message' => 'Implementation has been successfully added to the database!'));
    } else {
      View::make('Implementation/new.html', array('errors' => $errors, 'attributes' => $attributes));
    }
  }

  public static function edit($id){
    self::check_logged_in();
    $Implementation = Implementation::fetchSingleImplementation($id);
    $params = ImplementationController::fetchGlobalParams();  

    View::make('Implementation/Implementation_modify.html', array('Implementation' => $Implementation, 'params' => $params));
  }

  public static function show($algorithm_id, $planguage){
    $implementations = Implementation::fetchAllByAlgorithm($algorithm_id);
    $algorithm = Algorithm::fetchSingleAlgorithm($algorithm_id);

    View::make('implementation/implementations_show.html', array('implementations' => $implementations, 'selected_planguage' => $planguage, 'algorithm' => $algorithm, 'back_path' => $back_path));
  }

  public static function update($id){
    self::check_logged_in();
    $params = $_POST;

    $Implementation = new Implementation(array(
      'name' => $params['name'],
      'class' => $params['class'],
      'timecomplexity' => $params['timecomplexity'],
      'year' => $params['year'],
      'tags' => $tags,
      'author' => $params['author'],
      'description' => $params['description'],
      'similar' => $similar 
      ));

    $errors = $Implementation->errors();
    // Kint::dump($errors);
    
    
    if(count($errors) > 0){
      View::make('Implementation/Implementation_modify.html', array('errors' => $errors, 'attributes' => $attributes));
    }else{
      $Implementation->update();

      Redirect::to('/algorithm/:id' . $id, array('message' => 'Implementation was successfully modified!'));
    }
  }

  public static function new($algorithm_id){
    self::check_logged_in();
    View::make('Implementation/new.html', array('algorithm_id' => $algorithm_id));
  }

  public static function delete($id, $algorithm_id){
    self::check_logged_in();
    $Implementation = new Implementation(array('id' => $id));
    $Implementation->delete();

    $algorithm = new Algorithm(array('id' => $algorithm_id));
      Redirect::to('/algorithm/:id' . $algorithm->id, array('message' => 'Implementation has been removed successfully!'));
  }
 
}
