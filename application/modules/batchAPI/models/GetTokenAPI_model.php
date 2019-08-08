<?php
//require_once APPPATH.'config/database_constants.php';
class GetTokenAPI_model extends CI_Model
{
	
	public function saveToken($input)
	{	
		$this->deletePreviousTokens();
			
		date_default_timezone_set('Asia/Calcutta');
		$now = new DateTime();
		$dt = $now->format('Y-m-d H:i:s');
		
		   $token_data=array(
		        'access_token' => $input['access_token'],
				'token_type' =>$input['token_type'],
				'refresh_token' =>$input['refresh_token'],
				'expires_in' =>$input['expires_in'],
			    'scope' =>$input['scope'],
				'created_datetime'  => $dt
			);
	
			$token_table= '`token_details` ';
					
			$result =$this->db->insert($token_table,$token_data);
			$id=$this->db->insert_id();
			
			return (string)$id;
			
	}
	
	public function deletePreviousTokens(){
		$this->db->truncate('token_details');
	}
	
	
	public function saveMasterData($inputMasterData){
		
		if(!empty($inputMasterData) && isset($inputMasterData['careerStreams'])){
			
			$this->deletePreviousMasterData();
			
			date_default_timezone_set('Asia/Calcutta');
			$now = new DateTime();
			$dt = $now->format('Y-m-d H:i:s');
			
			
			for($i=0;$i<sizeof($inputMasterData['careerStreams']);$i++){
				$careerStreams_data=array(
						'crStreamId' => $inputMasterData['careerStreams'][$i]['crStreamId'],
						'crStreamName' => $inputMasterData['careerStreams'][$i]['crStreamName'],
						'API_called_date'  => $dt
				);
			
				$careerStreams_table= '`career_streams_master`';
				$result =$this->db->insert($careerStreams_table,$careerStreams_data);
			}
			
			$id=$this->db->insert_id();
			return (string)$id;							
		}else 
		{
			return false;
		}
		
	}
	
	public function deletePreviousMasterData(){
		$this->db->truncate('career_streams_master');
	}
	
}
// End of file CheckRoleDetails_model.php
// Location: .\application\modules\role\models\CheckRoleDetails_model.php