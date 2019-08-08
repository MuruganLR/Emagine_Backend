<?php
//require_once APPPATH.'config/database_constants.php';
class GetSession_model extends CI_Model
{
	
	public function getSessionDetails($input)
	{				
		$select =   array(
				'IFNULL (`id`,"") as id',
				'IFNULL (`uniqueId`,"") as uniqueId'		
		);
		$arrTables=array(
					'`users`' 		
		);
		        
		$this->db->select($select);
		$this->db->from($arrTables);
		//if(!empty($input['id']))
		$this->db->where('`id`', $input['id']);
		
		$db_select_query=$this->db->get()->result_array();
		
		if (!empty($db_select_query)){
			return $db_select_query;
		}
		else
			return false;	
	}
		
}
// End of file CheckRoleDetails_model.php
// Location: .\application\modules\role\models\CheckRoleDetails_model.php