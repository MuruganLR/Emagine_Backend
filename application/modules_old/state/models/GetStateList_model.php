<?php
//require_once APPPATH.'config/database_constants.php';
class GetStateList_model extends CI_Model
{
	public function getStateList($input)
	{		
		$select_query=array();
		$select =   array(
				'IFNULL (`id`,"") as id',
				'IFNULL (`state_name`,"") as state_name'
		);
		$arrTables=array(
					'`states`' 		
		);
		        
		$this->db->select($select);
		$this->db->from($arrTables);
		if(!empty($input[0]))
			$this->db->where('`country_id`', $input[0]);		
		$db_select_query=$this->db->get()->result_array();

		if (!empty($db_select_query))
			return $db_select_query;
		else
			return false;	
	}
	
}
// End of file CheckRoleDetails_model.php
// Location: .\application\modules\role\models\CheckRoleDetails_model.php