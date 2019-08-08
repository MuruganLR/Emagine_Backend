<?php
//require_once APPPATH.'config/database_constants.php';
class SaveJobCategoryDetails_model extends CI_Model
{
	
	public function SaveJobCategoryDetails($input)
	{	
		
		// 
			
		if(!empty($input))
		{
			$id =  $this->checkIdExist($input['jobid'],$input['jobtype']);
			//print_r($id);die();
			$JobCategory_data=array(
					'job_id'  =>$input['jobid'],
					'job_type_id' =>$input['jobtype'],
					'start_date'  =>$input['startDate'],
					'end_date'  =>$input['endDate'],
					'comment'  =>$input['txtComment'],
					'title'  =>$input['txtTitle'],
			);
				
			$job_category_table = '`job_category_details` ';
			
			
			$this->db->set($JobCategory_data);
			
			if($id != '')
			{
				$result = $this->db->update($job_category_table, $JobCategory_data, array('id'=>$id));				
			}
			else
			{
				$result =$this->db->insert($job_category_table,$JobCategory_data);
				$id=$this->db->insert_id();
				
			}
			return (string)$id;
		
		}
			
	
	}

	
	public function checkIdExist($jobid,$jobTypeId){
		
		$selectfields[0]="IFNULL (`id`,'') as id";		
		$selectfields[1]="IFNULL (`job_id`,'') as job_id";
		$selectfields[2]="IFNULL (`job_type_id`,'') as job_type_id";
		$selectfields[3]="IFNULL (`start_date`,'') as start_date";
		$selectfields[4]="IFNULL (`end_date`,'') as end_date";
		$selectfields[5]="IFNULL (`comment`,'') as comment";
		$selectfields[6]="IFNULL (`title`,'') as title";

		
		$arrTables[0] =  "`job_category_details` ";
		$query=$this->db->select($selectfields);
		$this->db->from($arrTables[0]);
		$this->db->where('`job_id`', $jobid)->where('job_type_id',$jobTypeId);
		$query = $this->db->get();
		$result= $query->result();
		if(!empty($result)){
			$this->insertLogs($result);
			return $result[0]->id; //insert in log tbl		
		}
		else
			return '';
	}
				
	
	public function insertLogs($result){
	//echo"<pre>";	print_r($result);die();	
		date_default_timezone_set('Asia/Calcutta');
		$now = new DateTime();
	    $dt =  $now->format('Y-m-d H:i:s');
	    
		$JobCategory_log_data=array(
				'category_detail_id'  =>$result[0]->id,
				'job_id'  =>$result[0]->job_id,
				'job_type_id' =>$result[0]->job_type_id,
				'start_date'  =>$result[0]->start_date,
				'end_date'  =>$result[0]->end_date,
				'comment'  =>$result[0]->comment,
				'title'  =>$result[0]->title,
				'added_date'  => $dt
		);
		
		$job_category_logs_table = '`job_category_logs` ';					
		$this->db->set($JobCategory_log_data);				
		$result =$this->db->insert($job_category_logs_table,$JobCategory_log_data);
		
		return true;
	}
	
				
}


