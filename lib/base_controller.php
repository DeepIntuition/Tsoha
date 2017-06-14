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
    return isset($_SESSION['user']);
  }

  public static function check_administrator_rights(){
    $adminRights = User::check_administrator_rights($_SESSION['user']);
    if(!isset($_SESSION['administrator']) && $adminRights){
      $_SESSION['administrator'] = TRUE;
    }

    return $adminRights;
  }

}

