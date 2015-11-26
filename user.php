<?php
	/**
	* This is the user object [we call this for some other purpose]
	*/
	class User {

		private $username;	
		private $password;
		private $email;
		private $isManager;
		
		public function __construct($username, $password, $email) {
			$this->username = $username;
			$this->password = $password;
			$this->email = $email;
			if (!$this->email) {
				$this->isManager = true;
			} else {
				$this->isManager = false;
			}
		}

		public function checkStatus() {
			return $this->isManager;
		}
	
		public function toString() {
			return "username: " . $this->username
					. "password: " . $this->password
					. "email: " . $this->email;
		}
	}
?>