<?php

	class DB {
		
		public $mysqli = NULL;
		public $conf = NULL;
		
		public function __construct() {
			$this->dbConnect(); // Initiate Database connection
		}
		
		/* Connect to Database */
		private function dbConnect() {
			require_once ("../includes/config.php");
			$this->mysqli = new mysqli($host, $user, $pass, $database);
			$this->mysqli->query('SET CHARACTER SET utf8');
		}
		
		/* Api Checker */
		public function checkResponse_Impl() {
			if (mysqli_ping($this->mysqli)){
				echo "Database Connection : Success";
			}else {
				echo "Database Connection : Error";
			}
		}
		
		/* String mysqli_real_escape_string */
		public function real_escape($s) {
			return mysqli_real_escape_string($this->mysqli, $s);
		}
		
	}
		
?>