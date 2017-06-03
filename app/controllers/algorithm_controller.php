<?php

  class AlgorithmController extends BaseController{

    public static function home(){                 
   	  View::make('suunnitelmat/home.html');
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
