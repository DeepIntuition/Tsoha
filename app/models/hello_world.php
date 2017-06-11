<?php

class HelloWorld extends BaseModel{

    public static function say_hi(){
      return 'Hello World!';
    }

    public static function sandbox(){
        $fulkerun = new Algorithm(array(
        'name' => 'dwe',
        'class' => 'trumpetirump',
        'description' => '',
        'year' => 'Booom!'
        ));
        $errors = $fulkerun->errors();

        Kint::dump($errors);
    }
}
