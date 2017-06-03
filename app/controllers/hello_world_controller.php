<?php

  class HelloWorldController extends BaseController{

    public static function sandbox(){
      View::make('helloworld.html');
    }    
  } 
