<?php
//require_once APPPATH.'config/database_constants.php';
class GetCityList_model extends CI_Model
{
	public function getCityList($input)
	{		
		$select_query=array();
		$select =   array(
				'IFNULL (`id`,"") as id',
				'IFNULL (`city_name`,"") as city_name'
		);
		$arrTables=array(
					'`cities`' 		
		);
		        
		$this->db->select($select);
		$this->db->from($arrTables);
		if(!empty($input[0]))
			$this->db->where('`state_id`', $input[0]);		
		$db_select_query=$this->db->get()->result_array();

		if (!empty($db_select_query))
			return $db_select_query;
		else
			return false;	
	}
	
}
// End of file CheckRoleDetails_model.php
// Location: .\application\modules\role\models\CheckRoleDetails_model.php