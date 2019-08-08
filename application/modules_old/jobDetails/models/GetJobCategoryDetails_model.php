<?php
//require_once APPPATH.'config/database_constants.php';
class GetJobCategoryDetails_model extends CI_Model
{
	
	public function getGetJobCategoryDetails($input)
	{					
		
		//year: 2018, month: 7, day: 9
		
		$selectfields[0]="IFNULL (`job_type_id`,'') as job_type_id";
		$selectfields[1]="IFNULL (date(`start_date`),'') as startDate";
		$selectfields[2]="IFNULL (date(`end_date`),'') as endDate";
		$selectfields[3]="IFNULL (`comment`,'') as comment";
		$selectfields[4]="IFNULL (`title`,'') as title";
		
		
		$arrTables[0] =  "`job_category_details` ";	
		
		$query=$this->db->select($selectfields);	
		$this->db->where('job_id', $input['jobid'])->where("CURRENT_DATE BETWEEN DATE(start_date) AND DATE(end_date)",NULL,False);	
		if(isset($input['jobtypeIdVal']) && $input['jobtypeIdVal']!=0 && $input['jobtypeIdVal']!='' && $input['jobtypeIdVal']!= NULL)
		$this->db->where('job_type_id', $input['jobtypeIdVal']);
		
		$this->db->from($arrTables[0]);
		$this->db->order_by('job_type_id','asc');
		$this->db->limit(1, 0);								
		$query = $this->db->get();		
		$result= $query->result();
		
		for($j=0;$j<sizeof($result);$j++){
			$sdtstr ='';
			$edtstr ='';
			
			$sdtArr = array();
			
			if(isset($result[$j]->startDate) && $result[$j]->startDate != '' && $result[$j]->startDate != NULL){
				 $sdateData = explode("-",$result[$j]->startDate);
				 $result[$j]->startDate = $sdateData;
			}
			
			if(isset($result[$j]->endDate) && $result[$j]->endDate != '' && $result[$j]->endDate != NULL){
				$edateData = explode("-",$result[$j]->endDate);
				$result[$j]->endDate = $edateData;
			}
			
		}
	//	echo "<pre> here it is:"; print_r($result);die();
		
		if(!empty($result))
		 return $result;
		else
		 return '';
	}
	
			
}
