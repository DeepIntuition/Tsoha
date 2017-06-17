<?php

class BaseController{

  public static function get_user_logged_in(){
    if(isset($_SESSION['user'])){
      $user = User::fetchUserById($_SESSION['user']);
      return $user;
    } else {
      return null;
    }
  }

  public static function check_logged_in(){
    if (!isset($_SESSION['user'])) {
      Redirect::to('/login', array('message' => 'Kirjaudu ensin sisään!'));
    }else{
      return TRUE;
    } 
  }
}

