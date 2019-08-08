<?php
//require_once APPPATH.'config/database_constants.php';
class GetEmployerList_model extends CI_Model
{
	
	public function getEmployerList($input,$uniqSessionID,$userId)
	{		
		$usrID = $this->checkUserID($userId,$uniqSessionID);
		
		if($usrID != '0') {
		
		$select_query=array();
		$select =   array(
				'IFNULL (`id`,"") as id',
				'IFNULL (`buId`,"") as buId',											
				'IFNULL (`buName`,"") as buName',
				'IFNULL (`logo_path`,"") as logo_path'
		);
		$arrTables=array(
					'`business_unit_list`' 		
		);
		        
		$this->db->select($select);
		$this->db->from($arrTables);
		$db_select_query=$this->db->get()->result_array();
		if (!empty($db_select_query))
			return $db_select_query;
		else
			return false;	
		
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