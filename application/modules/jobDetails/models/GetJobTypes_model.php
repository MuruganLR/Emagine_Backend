<?php
//require_once APPPATH.'config/database_constants.php';
class GetJobTypes_model extends CI_Model
{
	
	public function getjobTypes()
	{					
		
		$selectfields[0]="IFNULL (`id`,'') as id";
		$selectfields[1]="IFNULL (`job_type`,'') as job_type";
		
		$arrTables[0] =  "`job_types` ";				
		$query=$this->db->select($selectfields);	
		$this->db->from($arrTables[0]);
		$this->db->where('`status`', '1');
		$query = $this->db->get();		
		$result= $query->result();
		if(!empty($result))
		 return $result;
		else
		 return '';
	}
	
			
}
