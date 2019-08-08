<?php
//require_once APPPATH.'config/database_constants.php';
class GetEmpRegistration_model extends CI_Model
{
	
	public function getRegistrationDetails($input,$uniqSessionID,$userId)
	{		
		$noData = array();
		$usrID = $this->checkUserID($userId,$uniqSessionID);
		
		if($usrID != '0') {
		
		$select_query=array();
		$select =   array(
				'IFNULL (`id`,"") as id',
				'IFNULL (`firstname`,"") as firstname',
				'IFNULL (`lastname`,"") as lastname',
				'IFNULL (`companyname`,"") as companyname',			
				'IFNULL (`company_website`,"") as company_website',					
				'IFNULL (`company_address`,"") as company_address',
				'IFNULL (`city`,"") as city',
				'IFNULL (`state`,"") as state',
				'IFNULL (`country`,"") as country',
				'IFNULL (`pincode`,"") as pincode',
				'IFNULL (`contact_number`,"") as contact_number',
				'IFNULL (`mobile_number`,"") as mobile_number',		
				'IFNULL (`email`,"") as email',	
				'IFNULL (`SPOC_name`,"") as SPOC_name',
				'IFNULL (`SPOC_mobile_number`,"") as SPOC_mobile_number',
				'IFNULL (`SPOC_email`,"") as SPOC_email',
				'IFNULL (`industry`,"") as industry',
				'IFNULL (`employee_number`,"") as employee_number',				
				'IFNULL (`status`,"") as status',
				'if(status ="Approve","Approved","Approve") as appStatustxt',
				'if(status ="Reject","Rejected","Reject") as rejStatustxt'
				
		);
		$arrTables=array(
					'`employer`' 		
		);
		        
		$this->db->select($select);
		$this->db->from($arrTables);
		$this->db->order_by("FIELD (status, 'Pending', 'Approve', 'Reject')");
		
		$db_select_query=$this->db->get()->result_array();
		
		if (!empty($db_select_query))
			return $db_select_query;
		else
			return $noData;	
		
		}else 
		{
			return 'Session MisMatch';
		}
		
	}
	
	
	
	public function checkUserID($userId,$uniqSessionID){
		
		$select_query=array();
		$select =   array(
				'IFNULL (`id`,"") as id',
				'IFNULL (`user_name`,"") as username' 	
		);
		$arrTables=array(
				'`users`'
		);
		
		$this->db->select($select);
		$this->db->from($arrTables);
		//if(!empty($input['id']))
		$this->db->where('`id`', $userId)->where('uniqueId',$uniqSessionID);
		
		$db_select_query=$this->db->get()->result_array();
		
		if (!empty($db_select_query)){
			return $db_select_query[0]['id'];	
		}
		else {
			return '0';
		}
		
	}
	
}
// End of file CheckRoleDetails_model.php
// Location: .\application\modules\role\models\CheckRoleDetails_model.php