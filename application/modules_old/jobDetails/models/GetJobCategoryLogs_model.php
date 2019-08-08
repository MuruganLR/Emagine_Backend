<?php
//require_once APPPATH.'config/database_constants.php';
class GetJobCategoryLogs_model extends CI_Model
{
	
	public function getJobCategoryLogsData()
	{					
// 		$selectfields[0]="IFNULL (jcl.`ids`,'') as id";
// 		$selectfields[1]="IFNULL (b.`buName`,'') as compnyname";
// 		$selectfields[2]="IFNULL (o.`openTitle`,'') as openTitle";	
// 		$selectfields[3]="IFNULL (jt.`job_type`,'') as job_type";
// 		$selectfields[4]="IFNULL (Date(jcl.`start_date`),'') as start_date";		
// 		$selectfields[5]="IFNULL (Date(jcl.`end_date`),'') as end_date";				
// 		$selectfields[6]="IFNULL (jcl.`comment`,'') as comment";
// 		$selectfields[7]="IFNULL (jcl.`title`,'') as title";
		
// 		$arrTables[0] =  "`business_unit_list` "." `b`";
// 		$arrTables[1] = "`opening_details` "." `o`";
// 		$arrTables[2] =  "`job_category_logs`"." `jcl`";
// 		$arrTables[3] = "`job_types` "." `jt`";
			
// 		$join_condition[0]="b.buId= o.buId";	
// 		$join_condition[1]="jcl.job_id= o.id";
// 		$join_condition[2]="jcl.job_type_id= jt.id";
		
// 		$query=$this->db->select($selectfields);
		
// 		$this->db->from($arrTables[0]);
// 		$this->db->join($arrTables[1],$join_condition[0],'inner')->join($arrTables[2],$join_condition[1],'inner',false)->join($arrTables[3],$join_condition[2],'inner',false);
// 		$this->db->where('o.`statusId`', '3');		
		
		$noData = array();
		$sql = "SELECT IFNULL(jcl.`id`,'') as id, IFNULL(b.`buName`,'') as compnyname, IFNULL(o.`openTitle`,'') as openTitle, 
		IFNULL(jt.`job_type`,'') as job_type, IFNULL(Date(jcl.`start_date`),'') as start_date, IFNULL(Date(jcl.`end_date`),'') as end_date, 
		IFNULL(jcl.`comment`,'') as comment, IFNULL(jcl.`title`,'') as title 
		FROM `business_unit_list` `b` 
		INNER JOIN `opening_details` `o` ON `b`.`buId`= `o`.`buId` 
		INNER JOIN `job_category_logs` `jcl` ON jcl.job_id= o.id INNER JOIN `job_types` `jt` ON jcl.job_type_id= jt.id
		union
		SELECT IFNULL(jcl.`id`,'') as id, IFNULL(b.`buName`,'') as compnyname, IFNULL(o.`openTitle`,'') as openTitle, 
		IFNULL(jt.`job_type`,'') as job_type, IFNULL(Date(jcl.`start_date`),'') as start_date, IFNULL(Date(jcl.`end_date`),'') as end_date, 
		IFNULL(jcl.`comment`,'') as comment, IFNULL(jcl.`title`,'') as title 
		FROM `business_unit_list` `b` 
		INNER JOIN `opening_details` `o` ON `b`.`buId`= `o`.`buId` 
		INNER JOIN `job_category_details` `jcl` ON jcl.job_id= o.id INNER JOIN `job_types` `jt` ON jcl.job_type_id= jt.id "; //WHERE `o`.`statusId` = '3'
								
		$query = $this->db->query($sql);
		$result= $query->result();
		
		if(!empty($result))
		 return $result;
		else
		 return $noData;
	}
	
			
}
