<?php

class HelloWorld extends BaseModel{

    public static function say_hi(){
      return 'Hello World!';
    }

    public static function sandbox(){
    	$fulkerson = Algorithm::fetchSingleAlgorithm(1);
    	$algorithms = Algorithm::fetchAll();
    	// Kint-luokan dump-metodi tulostaa muuttujan arvon
    	Kint::dump($fulkerson);
    	Kint::dump($algorithms);
  	}
}
