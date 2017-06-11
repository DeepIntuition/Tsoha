<?php

class PasswordTools {

	public static function hash($password) {
  		return crypt($password, Salt::getSalt());
  	}

  	public static function verify($password, $hashedPassword){
  		if(PasswordTools::hash($password) == $hashedPassword) {
  			return TRUE;
  		} else {
  			return FALSE;
  		}
  	}

  	public static function validate_password($password) {	  
      $errors = array();
      if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z!@#$%]{8,30}$/', $password)) {
        $errors[] = 'Password does not meet the required criteria! (8-30 characters long, containing one or more numbers, at least one uppercase character and one lowercase and special characters.';
      }

      return $errors;
    }
}
