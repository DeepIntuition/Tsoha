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

		$query->execute(array('name' => $username, 'password' => $password));
		$row = $query->fetch();

		if($row){
			$user = new User(array(
				'id' => $row['id'],
				'username' => $row['name'],
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
			INSERT INTO Contributor (name, password) 
			VALUES (:username, :password) RETURNING id');

		$query->execute(array('username' => $this->username, 'password' => $this->password));
		$row = $query->fetch();
		$this->id = $row;
	}

	public function validate_username() {
		$errors = array();
		$max_length = 3;
		$errors[] = self::validate_not_null($this->username);
		$errors = array_merge($errors, self::validate_string_max_length($this->username, 25));
		$errors = array_merge($errors, self::validate_string_length_at_least($this->username, $max_length));
	}

	public static function check_administrator_rights($user_id){
		$query = DB::connection()->prepare('
			SELECT administrator FROM Contributor
			WHERE id= :user_id');
		$query->execute(array('user_id' => $user_id));
		$rights = $query->fetch();

		return $rights;
	}

	public static function check_name_available($new_username){
		$query = DB::connection()->prepare('
			SELECT * FROM Contributor
			WHERE name= :new_username');
		$query->execute(array('new_username' => $new_username));
		$row = $query->fetch();
		$errors = array();
		if($row){
			$errors[] = 'Username is in use. Choose another username.';
			return $errors;
		}else{
			return $errors;	
		}
	}

	public static function fetchUserById($user_id){
		$query = DB::connection()->prepare('
			SELECT * FROM Contributor
			WHERE id= :user_id');
		$query->execute(array('user_id' => $user_id));
		$rows = $query->fetchAll();

		foreach($rows as $row) {
			$user = new User(array(
				'id' => $row['id'],
				'username' => $row['name'],
				'administrator' => $row['administrator']
				));
		}
		return $user;
	}
}