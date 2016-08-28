<?php 

class Company {
	
	protected $_Database;
	
	public function __construct(){
		$this->_Database = new Database;
	}
	
	public function create_company() {
	
		$this->_Database->insert("companies", "i", array(
			"join_date" => time()
		));
		
		$company_id = $this->_Database->getConnection()->insert_id;	
		return isset($company_id) ? $company_id : false;
	}
	
	public function assign_user_to_company($companyID, $user_id) {
		
		$this->_Database->insert("company_users", "iii", array(
			"companyID" => $companyID,
			"userID" => $user_id,
			"join_date" => time()
		));
	}
	
	public function update_company_name($companyID, $company_name) {
		
		$db_safe_companyID = $this->_Database->sanitize_db($companyID);
		$db_safe_company_name = $this->_Database->sanitize_db($company_name);
		
		return $this->_Database->query("UPDATE companies SET name='{$db_safe_company_name}' WHERE ID='{$db_safe_companyID}'") ? true : false;
	}
	
	public function get_company_info($companyID) {
		$db_safe_companyID = $this->_Database->sanitize_db($companyID);
		$companies_info = $this->_Database->select("companies", "i", array("ID" => $db_safe_companyID, "ORDERBY" => "ID"));
		
		return count($companies_info) > 0 ? $companies_info[0] : false;
	}
	
	public function update_business_info($companyID = false, $params = array()) {
		
		/* 
			TODO: Don't insert records if no change from previous record.
		*/
		
		$count = count($params);
		if($companyID || $count >= 0)
		{
			$FullParamsArray = array_merge(array("companyID" => $companyID, "last_update" => time()), $params);
			
			$paramTypes = "ii";
			foreach($params as $key => $value) {
				$paramTypes .= "s";	
			}
			
			$this->_Database->insert("business_info", $paramTypes, $FullParamsArray);
		}
		else
		{
			return false;
		}
			
	}
	
	public function get_business_info($companyID) {
		$db_safe_companyID = $this->_Database->sanitize_db($companyID);
		$business_info = $this->_Database->select("business_info", "i", array("companyID" => $db_safe_companyID, "ORDERBY" => "ID"));
		
		return count($business_info) > 0 ? $business_info[0] : false;
	}
	
	
	
}

?>