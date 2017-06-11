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
      $errors = array();
      foreach($this->validators as $validator){
        $errors = array_merge($errors, $this->{$validator}());
      }

      return $errors;
    }

    public function validate_not_null($string){
      $errors = array();
      if($string == '' || $string == null) {
        $errors[] = 'String should not be empty!';
      }
      return $errors;
    }

    public function validate_string_length_at_least($string, $length){
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

  }

