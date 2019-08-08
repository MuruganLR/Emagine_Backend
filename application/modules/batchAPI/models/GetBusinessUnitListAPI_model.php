<?php
//require_once APPPATH.'config/database_constants.php';
class GetBusinessUnitListAPI_model extends CI_Model
{
	
	public function saveBusinessUnitDetails($input)
	{		

		date_default_timezone_set('Asia/Calcutta');
		$now = new DateTime();
		$dt = $now->format('Y-m-d H:i:s');
		
		
		$this->db->trans_start();
		
	    for($i=0;$i<sizeof($input);$i++){	
		
	    $sql ="INSERT INTO business_unit_list (buId,buName,enabled,API_called_date) values 
		( '" . $input[$i]['buId']. "','" . $input[$i]['buName']. "','" . $input[$i]['enabled']. "','" . $dt. "' ) 
		ON DUPLICATE KEY UPDATE buName = '". $input[$i]['buName'] ."' , enabled = '" .$input[$i]['enabled']."', API_called_date = '".$dt."'";
		   
		   
		$result = $this->db->query($sql);
		
	   }
	
	   if($result === false){
	   	$this->db->trans_rollback();
	   }else{
	   	$this->db->trans_complete();
	   }
	   
	        $id=$this->db->insert_id();
			return (string)$id;			
	}
	
	public function getTokenDetails(){	
		$select =   array(
				'IFNULL (`id`,"") as id',
				'IFNULL (`access_token`,"") as access_token'			
		);
		$arrTables=array(
				'`token_details`'
		);
		
		$this->db->select($select);
		$this->db->from($arrTables);
		$db_select_query=$this->db->get()->result_array();
		if (!empty($db_select_query))
			return $db_select_query;
		else
			return false;
	}
	
	
}
// End of file CheckRoleDetails_model.php
// Location: .\application\modules\role\models\CheckRoleDetails_model.php