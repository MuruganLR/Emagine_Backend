<?php
//require_once APPPATH.'config/database_constants.php';
class DeleteJobCategoryDetails_model extends CI_Model
{
	
	public function DeleteJobCategoryDetails($input)
	{	
		if(!empty($input))
		{						
			$this->db->where('job_id', $input['jobid'])->where('job_type_id',$input['jobTypeId']);
			$result = $this->db->delete('job_category_details');
			
			if($result)
			{
				return '1';			
			}
			else
			{
				return '';				
			}					
		}	
	}
	
				
}


