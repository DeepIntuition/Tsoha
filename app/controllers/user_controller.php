<?php

class UserController extends BaseController{

	public static function login(){
		View::make('login.html');
	}

	public static function logout() {
		$_SESSION['user'] = null;
		$_SESSION['administrator'] = null;

		Redirect::to('/', array('message' => "You've been successfully logged out. Hope to see you again soon!"));
	}

	public static function verify_user(){
		$params = $_POST;
		$pwHash = PasswordTools::hash($params['password']);
		$user = User::authenticate($params['username'], $pwHash);

		if($user){
			$_SESSION['user'] = $user->id;
			Redirect::to('/index', array('message' => 'Login successful! Welcome back to AlgorithmDB!'));
		}else{
			View::make('login.html', array('error' => 'Wrong username or password!', 'username' => $params['username']));
		}
	}

	public static function save_new_user(){
		$params = $_POST;
		$pwHash = PasswordTools::hash($params['password']);
		$user = new User(array(
			'username' => $params['username'],
			'password' => $pwHash
			));

		//$errors = $user->errors();
		$errors = array();
		$errors = array_merge($errors, User::check_name_available($user->username));
		$errors = array_merge($errors, PasswordTools::validate_password($params['password']));
		$errors = array_merge($errors, PasswordTools::validate_password_check($params['password'], $params['password_check']));		

		if(count($errors) > 0){
			View::make('register.html', array('errors' => $errors, 'username' => $user->username));
		}else{
			$user->save();
			$_SESSION['user'] = $user->id;
			Redirect::to('/index', array('message' => 'Your registration was successful, welcome to AlgorithmDB!'));
		}
		
	}

	public static function register() {
		View::make('register.html', array(''));	
	}
	
}