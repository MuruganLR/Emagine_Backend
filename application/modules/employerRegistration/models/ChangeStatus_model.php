<?php
//require_once APPPATH.'config/database_constants.php';
class ChangeStatus_model extends CI_Model
{
	
	public function ChangeStatus($input)
	{		
		//print_r($input);die();
		$id='';
		$registration_data=array(
				'id'  =>$input['id'],
				'status' =>$input['status']										
		);
					
		$customer_table=array(				
				'`employer` '			
		);

		//$arrtables[0]="`" . ACADEMY_CENTER_PLAYER_TABLE. "` "." `p`";
		$this->db->set($registration_data);	
		if(!empty($input['id']))
		{
			$result = $this->db->update($customer_table[0], $registration_data, array('id'=>$input['id']));
			$id=$input['id'];							
		}
		
		return (string)$id;
					
	}
	
	
}
// End of file CheckRoleDetails_model.php
// Location: .\application\modules\role\models\CheckRoleDetails_model.php