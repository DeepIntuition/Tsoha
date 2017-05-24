<?php

  class HelloWorldController extends BaseController{

    public static function index(){     
   	  View::make('suunnitelmat/home.html');
    }

    public static function sandbox(){
      View::make('helloworld.html');
    }

    public static function algorithm_list(){
      View::make('suunnitelmat/algorithm_list.html');
    }

    public static function algorithm_show(){
      View::make('suunnitelmat/algorithm_show.html');
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
