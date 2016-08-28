<?php 

class Database {
	
	private $db_server = "localhost";
	private $db_user = "artemis_admin";
	private $db_password = "";
	private $db_name = "new_artemis";
	
	/*
	private $db_server = "localhost";
	private $db_user = "artemis";
	private $db_password = "3JCmgEKJB2SFHU4";
	private $db_name = "new_artemis";
	*/
	
	private $__connection;
	private static $_instance;
	
	public static function getInstance(){
		if(!self::$_instance)
		{
			self::$_instance = new self();	
		}
		
		return self::$_instance;
	}
	
	public function __construct(){
		
		/*
		$DBServer = 'localhost';
		$DBUser   = 'artemis';
		$DBPass   = '3JCmgEKJB2SFHU4';
		$DBName   = 'artemis';
		*/
		
		$this->connect();	
	}
	
	public function connect($the_db_server = false, $the_db_user = false, $the_db_password = false, $the_db_name = false){
		
		$the_db_server = $the_db_server ? $the_db_server : $this->db_server;
		$the_db_user = $the_db_user ? $the_db_user : $this->db_user;
		$the_db_password = $the_db_password ? $the_db_password : $this->db_password; 
		$the_db_name = $the_db_name ? $the_db_name : $this->db_name;
		
		$this->__connection = new mysqli($the_db_server, $the_db_user, $the_db_password, $the_db_name);
		 
		if($this->getConnection()->connect_error) 
		{
		  return trigger_error('Database connection failed: ' . $this->getConnection()->connect_error, E_USER_ERROR);
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
		return $this->__connection;
	}
	
	/* 
		Santization
	*/
	public function sanitize_db($string){
		
		$string = (string)trim($string);
		return $this->getConnection()->real_escape_string($string);
	}
	
	/* 
		Query Function
	*/
	public function query($sql){
		
		return $this->getConnection()->query($sql);
	}
	
	
	/*
		Insert Database Query
	*/
	public function insert($tableName = false, $paramsType = false, $params = array()) {
		
		$count = count($params);
		if(!$tableName || !$paramsType || $count <= 0)
		{
			return false;
		}
		
		/* 
			Prepare Insert Statement Keys
		*/
		$prepStatement = "INSERT INTO {$tableName} (";
		$i = 0;
		foreach($params as $key => $value)
		{	
			$i++;
			$prepStatement .= $key;
			$i < $count ? $prepStatement .= ", " : NULL;
		}
		
		/* 
			Prepare Insert Statement Values
		*/
		$prepStatement .= ") VALUES (";
		$i = 0;
		foreach($params as $key => $value)
		{	
			$i++;
			$prepStatement .= "?";
			$i < $count ? $prepStatement .= ", " : NULL;
		}
		
		$prepStatement .= ")";
		$preparedStatement = $this->getConnection()->prepare($prepStatement);
		
		if($preparedStatement === false) 
		{
			return false;
			trigger_error('Statement failed! ' . $this->getConnection()->error, E_USER_ERROR);
		}

		/* 
			Prepare bind_param Function
		*/	
		$paramsValueArray = array();
		foreach($params as $key => $value)
		{
			 $paramsValueArray[$key] = &$params[$key];
		}
		call_user_func_array(array($preparedStatement, "bind_param"), array_merge(array($paramsType), $paramsValueArray));
		
		/*
			Execute statement
		*/
		return $preparedStatement->execute();
		
		/*
			We're done, Close Connections
		*/
		$preparedStatement->close();
		$this->getConnection()->close();
	}
	
	
	/*
		Select Database Query
	*/
	public function select($tableName = false, $paramsType = false, $params = array()){
		
		$SQL_keys = array("RELATION", "ORDERBY");
		$SQL_relations = array("AND", "OR");
		
		$params_noSQL = $params;
		foreach($params as $key => $value)
		{
			if(in_array($key, $SQL_keys)) 
			{
				unset($params_noSQL[$key]);	
			}
		}
		
		$count_noSQL = count($params_noSQL);
		if(!$tableName || !$paramsType || $count_noSQL <= 0)
		{
			return false;
		}
		
		$prepStatement = "SELECT * FROM {$tableName} WHERE ";
		
		$i = 0;
		$relation_added = true;
		foreach($params_noSQL as $key => $value)
		{
			$i++;
			if(in_array($key, $SQL_keys) && in_array($value, $SQL_relations)) 
			{
				$prepStatement .= " ".$value." ";
				$relation_added = true;
			}
			else
			{
				if(!$relation_added)
				{	
					$prepStatement .= " AND ".$key."=?";
				}
				else
				{
					$prepStatement .= $key."=?";	
				}
				$relation_added = false;
			}
		}
		
		if(isset($params["ORDERBY"]))
		{
			$prepStatement .= " ORDER BY ".$params["ORDERBY"]." DESC";		
		}
		
		$preparedStatement = $this->getConnection()->prepare($prepStatement);
		
		if($preparedStatement === false) 
		{
			//return false;
			trigger_error('Statement failed! ' . $this->getConnection()->error, E_USER_ERROR);
		}
		
		/* 
			Prepare bind_param Function
		*/	
		
		$paramsValueArray = array();
		foreach($params_noSQL as $key => $value)
		{
			 $paramsValueArray[$key] = &$params_noSQL[$key];
		}
		
		call_user_func_array(array($preparedStatement, "bind_param"), array_merge(array($paramsType), $paramsValueArray));
		
		/*
			Execute statement
		*/
		$preparedStatement->execute();
		
		/* Fetch the value */
		
		return $this->get_results($preparedStatement);
		
		/*
			We're done, Close Connections
		*/
		$preparedStatement->close();
		$this->getConnection()->close();
		
	}

	
	public function get_results($Statement) {
		$RESULT = array();
		$Statement->store_result();
		for ( $i = 0; $i < $Statement->num_rows; $i++ ) {
			$Metadata = $Statement->result_metadata();
			$PARAMS = array();
			while ( $Field = $Metadata->fetch_field() ) {
				$PARAMS[] = &$RESULT[ $i ][ $Field->name ];
			}
			call_user_func_array( array( $Statement, 'bind_result' ), $PARAMS );
			$Statement->fetch();
		}
		return $RESULT;
	}
	
}

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	


?>