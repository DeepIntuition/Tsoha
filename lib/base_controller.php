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
      Redirect::to('/login', array('message' => "You don't have the necessary rights. Please login first."));
    } 
  }

  public static function check_administrator_rights($redirect){
    if(!User::check_administrator_rights($_SESSION['user'])){
      $errors = array();
      $errors[] = "Permission denied. You don't have the necessary rights.";
      Redirect::to($redirect, array('errors' => $errors));
    } else {
      return TRUE;
    }
  }
}

