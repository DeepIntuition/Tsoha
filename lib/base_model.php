<?php

  class BaseModel{
    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null){
      // Käydään assosiaatiolistan avaimet läpi
      foreach($attributes as $attribute => $value){
        // Jos avaimen niminen attribuutti on olemassa...
        if(property_exists($this, $attribute)){
          // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
          $this->{$attribute} = $value;
        }
      }
    }

    public function errors(){
      // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
      $errors = array();

      foreach($this->validators as $validator){
        // Kutsu validointimetodia tässä ja lisää sen palauttamat virheet errors-taulukkoon
      }

      return $errors;
    }

    public function validate_string_length_at_least($string, $length){
      $errors = array();
      if($string == '' || $string == null) {
        $errors[] = 'String should not be empty!';
      }
      if(strlen($string) < $length){
        $errors[] = "String should be at least ".$length." characters long.";
      }

      return $errors;
    }

    public function validate_string_max_length($string, $length){
      $errors = array();
      if(strlen($string) > $length){
        $errors[] = "String should be at most ".$length." characters long.";
      }

      return $errors;
    }

    public function validate_string_contains_only_numbers($string){
      $errors = array();
      if(!preg_match('/(?=.*\d)/', $string)) {
        $errors[] = "String should contain only numbers.";
      }

      return $errors;
    }

    public function validate_password($password){
      $errors = array();
      if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z!@#$%]{8,30}$/', $password)) {
        $errors[] = 'Password does not meet the required criteria! (8-30 characters long, containing one or more numbers, at least one uppercase character and one lowercase and special characters.';
      }

      return $errors;
    }

    public function validate_year($string){
      $errors = array();
      $string_max_length = 4;
      
      $errors[] = $this->validate_string_max_length($string, $string_max_length);

      $errors[] = $this->validate_string_length_at_least($string, $string_max_length);

      $errors[] = $this->validate_string_contains_only_numbers($string);          
      return $errors;
    }

  }

