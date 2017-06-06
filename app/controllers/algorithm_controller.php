<?php

  class AlgorithmController extends BaseController{

    public static function store() {
      $params = $_POST;
      $algorithm = new Algorithm(array(
        'name' => $params['name'],
        'class' => $params['class'],
        'timecomplexity' => $params['timecomplexity'],
        'year' => $params['year'],
        'tags' => $params['tags'],
        'author' => $params['author'],
        'description' => $params['description'],
        'similar' => $params['similar'] 
      ));

      $newTags = $algorithm->newTagNames();
      Tag::saveNewTags($newTags);

      $algorithm->save();
      Redirect::to('/algorithm/' . $algorithm->id, array('message' => 'Algorithm has been successfully added to the database!'));
    }

    public static function home(){                 
   	  View::make('suunnitelmat/home.html');
    }

    public static function new(){
      $algorithms = Algorithm::fetchNames();      
      $tags = Tag::fetchNames();
      $classes = AClass::fetchNames();      

      $params = array(
        'algorithms' => $algorithms,
        'tags' => $tags,
        'classes' => $classes
      );           

      View::make('algorithm/new.html', array('params' => $params));
    }

    public static function index(){      
      $algorithms = Algorithm::fetchAll();
      View::make('algorithm/index.html', array('algorithms' => $algorithms));
    }

    public static function algorithm_show($id){
      $algorithm = Algorithm::fetchSingleAlgorithm($id);
      View::make('algorithm/algorithm_show.html', array('algorithm' => $algorithm));
    }

    public static function algorithm_modify(){
      View::make('suunnitelmat/algorithm_modify.html');
    }

    public static function implementation_show(){
      View::make('suunnitelmat/implementation_show.html');
    }

    public static function implementation_modify(){
      View::make('suunnitelmat/implementation_modify.html');
    }

    public static function login(){
      View::make('suunnitelmat/login.html');
    }
    
  }
