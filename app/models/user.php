<?php

class User extends BaseModel{
	
	public $id, $username, $password, $administrator;

	public function __construct($attributes){
		parent::__construct($attributes);
		$this->validators = array('validate_username');
	}

	public static function authenticate($username, $password){
		$query = DB::connection()->prepare('
			SELECT * FROM Contributor 
				WHERE name = :name 
				AND password = :password LIMIT 1');

		$query->execute(array('name' => $username, 'password' => $hashed));
		$row = $query->fetch();

		if($row){
			$user = new User(array(
				'id' => $row['id'],
				'username' => $row['username'],
				'password' => $row['password'],
				'administrator' => $row['administrator']
			));
			return $user; 
		}else{
			return null;
		}
	}

	public function save(){
		$query = DB::connection()->prepare('
			INSERT INTO Contributor (username, password) 
				VALUES (:username, :password) RETURNING id');

		$query->execute(array('name' => $this->username, 'password' => $this->password));
		$row = $query->fetch();
		$this->id = $row;
	}

	public function validate_username() {
		$errors = array();
		$errors[] = validate_not_null($this->username);
		$errors = validate_not_null($this->username);
	}
}