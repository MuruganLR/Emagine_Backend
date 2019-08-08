<?php
//require_once APPPATH.'config/database_constants.php';
class GetEmpRegistrationDetails_model extends CI_Model
{
	
	public function getEmployerRegistrationDetails($input)
	{		
		$noData = array();
		$select_query=array();
		$select =   array(
				'IFNULL (e.`id`,"") as id',
				'IFNULL (e.`firstname`,"-") as firstname',
				'IFNULL (e.`lastname`,"-") as lastname',
				'IFNULL (e.`companyname`,"-") as companyname',			
				'IFNULL (e.`company_website`,"-") as company_website',					
				'IFNULL (e.`company_address`,"-") as company_address',
				'IFNULL (c.`city_name`,"-") as city',
				'IFNULL (s.`state_name`,"-") as state',
				'IFNULL (cn.`country_name`,"-") as country',
				'IFNULL (e.`pincode`,"-") as pincode',
				'IFNULL (e.`contact_number`,"-") as contact_number',
				'IFNULL (e.`mobile_number`,"-") as mobile_number',		
				'IFNULL (e.`email`,"-") as email',	
				'IFNULL (e.`SPOC_name`,"-") as SPOC_name',
				'IFNULL (e.`SPOC_mobile_number`,"-") as SPOC_mobile_number',
				'IFNULL (e.`SPOC_email`,"-") as SPOC_email',
				'IFNULL (e.`industry`,"-") as industry',
				'IFNULL (e.`employee_number`,"-") as employee_number',				
				'IFNULL (e.`status`,"-") as status',
				'if(e.status ="Approve","Approved","Approve") as appStatustxt',
				'if(e.status ="Reject","Rejected","Reject") as rejStatustxt'
				
		);
		
// 		$arrTables=array(
// 					'`employer`' 		
// 		);
		
		$arrTables[0] =  "`employer` "." `e`";
		$arrTables[1] = "`cities` "." `c`";
		$arrTables[2] = "`countries` "." `cn`";
		$arrTables[3] = "`states` "." `s`";
		
		
		$join_condition[0]="e.city= c.id";
		$join_condition[1]="e.country= cn.id";
		$join_condition[2]="e.state= s.id";
		        
		$this->db->select($select);
		$this->db->from($arrTables[0]);
		$this->db->join($arrTables[1],$join_condition[0],'left');
		$this->db->join($arrTables[2],$join_condition[1],'left');
		$this->db->join($arrTables[3],$join_condition[2],'left');
		
		$this->db->where('e.id', $input['id']);
		
		
		$db_select_query=$this->db->get()->result_array();
		
		if (!empty($db_select_query))
			return $db_select_query;
		else
			return $noData;	
		
		
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