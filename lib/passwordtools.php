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
      $uppercase = preg_match('@[A-Z]@', $password);
      $lowercase = preg_match('@[a-z]@', $password);
      $number = preg_match('@[0-9]@', $password);
      $special = preg_match('@[\W]@', $password);
      
      if(!$uppercase) {
        $errors[] = 'Password does not meet the required criteria for a strong password. Please include at least one uppercase character.'; 
      }

      if(!$lowercase) {
        $errors[] = 'Password does not meet the required criteria for a strong password. Please include at least one lowercase character.';  
      }

      if(!$number) {
        $errors[] = 'Password does not meet the required criteria for a strong password. Please include at least one number.';  
      }

      if(!$special) {
        $errors[] = 'Password does not meet the required criteria for a strong password. Please include at least one special character.';  
      }

      if(strlen($password) < 8) {
        $errors[] = 'Password does not meet the required criteria for a strong password. Please increase the length to at least 8 characters.';  
      }

      return $errors;
    }

    public static function validate_password_check($password, $password_check){
      $errors = array();
      if(strcmp($password, $password_check) !== 0){
        $errors[] = 'Password does not match with the given password check.';
      }

      return $errors; 
    }
}
