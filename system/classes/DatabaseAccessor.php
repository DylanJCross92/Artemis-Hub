<?php 

class DatabaseAccessor {
	
	private $_connection;
	private static $_instance;
	
	public static function getInstance(){
		if(!self::$_instance)
		{
			self::$_instance = new self();	
		}
		return self::$_instance;
	}
	
	public function __construct(){
		
		
		$DBServer = 'localhost';
		$DBUser   = 'artemis';
		$DBPass   = '3JCmgEKJB2SFHU4';
		$DBName   = 'artemis';
		
		/*
		$DBServer = 'localhost';
		$DBUser   = 'root';
		$DBPass   = '';
		$DBName   = 'artemis_db';
		*/
		
		$this->connect($DBServer, $DBUser, $DBPass, $DBName);
			
	}
	
	public function connect($DBServer, $DBUser, $DBPass, $DBName){
		
		$this->_connection = new mysqli($DBServer, $DBUser, $DBPass, $DBName);
		 
		if($this->getConnection()->connect_error) 
		{
		  return trigger_error('Database connection failed: ' . $this->_connection->connect_error, E_USER_ERROR);
		}
		else
		{
			return true;
		}
	}
	
	/*
		Empty clone magic method to prevent duplication
	*/
	private function __clone(){}
	
	public function getConnection(){
		return $this->_connection;
	}
	
	public function query($sql){
		
		return $this->getConnection()->query($sql);
	}
	
	public function sanitize_db($string){
		
		$string = (string)trim($string);
		return "'".$this->getConnection()->real_escape_string($string)."'";
	}
		
	
}

?>