<?php


class AClassController extends BaseController{

	public static function new() {
		View::make('class/new_class.html');
	}

	public static function save_new_class() {
		$class = new AClass(array(
			'name' => $_POST['class_name']));

		$errors = array();
		$errors = $class->validate_class_name();

		if(count($errors) < 1){
			AClass::save($class_name);
			Redirect::to('/index', array('message' => 'New class was successfully added to the Database!'));
		}else{
			View::make('class/new_class.html', array('errors' => $errors));
		}
	}
}