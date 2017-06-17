<?php


class AClassController extends BaseController{

	public static function new() {
		View::make('class/new_class.html');
	}

	public static function store() {
		$class = new AClass(array(
			'name' => $_POST['class_name']));

		$errors = array();
		$errors = array_merge($errors, $class->validate_class_name());

		if(count($errors) < 1){
			$class->save();
			Redirect::to('/index', array('message' => 'New class was successfully added to the Database!'));
		}else{
			View::make('class/new_class.html', array('errors' => $errors));
		}
	}
}