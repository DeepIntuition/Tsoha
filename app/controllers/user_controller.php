<?php

class UserController extends BaseController{

	public static function login(){
    	View::make('login.html');
    }

    public static function verify_user(){
    	$params = $_POST;
    	$pwHash = PasswordTools::hash($params['password']);
    	$user = User::authenticate($params['username'], $pwHash);

    	if(!$user){
    		View::make('algorithm/login.html', array('error' => 'Wrong username or password!', 'username' => $params['username']));
    	}else{
    		$_SESSION['user'] = $user->id;
    		Redirect::to('/index/' . $algorithm->id, array('message' => 'Login successful! Welcome back to AlgorithmDB!'));
    	}
    }

    public static function save_new_user(){
    	$params = $_POST;
    	$pwHash = PasswordTools::hash($params['password']);
    	$user = new User(array(
    		'name' => $params['name'],
    		'password' => $pwHash
    	));

    	$errors = $user->errors();
    	$errors = array_merge($errors, PasswordTools::validate_password($user->password));

    	if(count($errors > 0)){
    		View::make('register.html', array('error' => 'Wrong username or password!', 'username' => $params['username']));
    	}else{
    		$_SESSION['user'] = $user->id;
    		$user->save();
    		Redirect::to('/index/' . $algorithm->id, array('message' => 'Welcome to AlgorithmDB '.{$user->username}.'! Time to explore the world of algorithms!'));
    	}
    }

    public static function register() {
    	View::make('register.html');	
    }
}