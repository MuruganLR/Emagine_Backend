<?php
//require_once APPPATH.'config/database_constants.php';
class GetHotJobs_model extends CI_Model
{	

	public function getHotJobsLists($ibuId){
				
		$selectfields[0]="IFNULL (o.`id`,'') as id";
		$selectfields[1]="IFNULL (o.`openTitle`,'') as openTitle";
		$selectfields[2]="IFNULL (concat(concat(SUBSTRING_INDEX(o.`ctcCurrency`, '_', -1),'',':'),' ',o.`ctcBandLowEnd`,'-',o.`ctcBandHighEnd`),'') as minCurrencyVal"; // Total Opened Positions
		$selectfields[3]="IFNULL (b.`logo_path`,'') as logo_path";
		$selectfields[4]="IFNULL (o.`address`,'') as addressCity";
		$selectfields[5]="IFNULL (o.`employmentType`,'') as employmentType";
		$selectfields[6]="IFNULL (b.`buName`,'') as compnyname";
		$selectfields[7]="IFNULL (j.`comment`,'') as comment";
		$selectfields[8]="IFNULL (o.`noPositionsTotal` - o.`noPositionsClosed`,'') as totalPositions"; // Total Opened Positions
		$selectfields[9]="IFNULL (j.`title`,'') as title";
		
		
		$arrTables[0] =  "`business_unit_list` "." `b`";
		$arrTables[1] = "`opening_details` "." `o`";
		$arrTables[2] = "`job_category_details` "." `j`";
		
		$join_condition[0]="b.buId= o.buId";
		$join_condition[1]="o.id= j.job_id";
		
		$query=$this->db->select($selectfields);
		
		$this->db->from($arrTables[0]);
		$this->db->join($arrTables[1],$join_condition[0],'inner');
		$this->db->join($arrTables[2],$join_condition[1],'inner');
		//'j.`job_type_id`', '1' -- is for hot hob type
		$this->db->where('o.`statusId`', '3')->where('o.`buId`',$ibuId)->where('j.`job_type_id`', '1')->where("(o.`noPositionsTotal` - o.`noPositionsClosed`)!= 0")->where("CURRENT_DATE BETWEEN DATE(start_date) AND DATE(end_date)",NULL,False);
		$this->db->group_by('o.id');
		$query = $this->db->get();
		$result= $query->result();
		if(!empty($result))
			return $result;
		else
			return '';
	}
				
}
?>