<?php
class Staff {
	
	protected $_Database;
	
	public function __construct(){
		$this->_Database = new Database;
	}
	
	public function create_staff(array $data) {
		
		$User = new User;
		$loggedInCompanyID = $User->get_user_company_id();
		
		$StaffID = $User->create_user($data["email"]);
		
		if($StaffID)
		{
			unset($data["email"]);
		
			$Company = new Company;
			$Company->assign_user_to_company($loggedInCompanyID, $StaffID);
			
			//TODO: Create code for user types and permissions to store in database as numericals, but reference as "staff", "admin", etc.
			$data["user_type"] = "staff";
			$User->update_usermeta($StaffID, $data);
			
			return true;
		}
		
		return false;	
	}
}

?>