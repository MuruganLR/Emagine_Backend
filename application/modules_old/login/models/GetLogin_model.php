<?php
//require_once APPPATH.'config/database_constants.php';
class GetLogin_model extends CI_Model
{
	
	public function getLoginDetails($input)
	{		
		$pwd = md5(trim($input['password']));		
		//session_start();
		$uniqID = '0';						
		$select_query=array();
		$select =   array(
				'IFNULL (`id`,"") as id',
				'IFNULL (`user_name`,"") as username', //md5(trim($password)
				'IFNULL (`password`,"") as password',
				'IFNULL (`usertype`,"") as usertype'			
		);
		$arrTables=array(
					'`users`' 		
		);
		        
		$this->db->select($select);
		$this->db->from($arrTables);
		//if(!empty($input['id']))
		$this->db->where('`user_name`', $input['username'])->where('password',$pwd)->where('`usertype`', $input['loginAs']);
		
		$db_select_query=$this->db->get()->result_array();
		
		if (!empty($db_select_query)){
		$uid = $db_select_query[0]['id'].'|';
		$uniqID = uniqid($uid);
		$this->updateSession($uniqID,$db_select_query[0]['id']);
		}
		
		if (!empty($db_select_query)){
			
			$data[0]['id'] = $db_select_query[0]['id'];
			$data[0]['uniqueId'] = $uniqID;
			$data[0]['usertype'] = $db_select_query[0]['usertype'];
			return $data;
		}
		else
			return false;	
	}
	
	public function updateSession($uniqID,$uid){	
		$updateData=array(
				'uniqueId' =>$uniqID	
		);
		$arr_tables[0] = "`users`";
		$where_array = array('id' => $uid);
		$this->db->where($where_array);
		$result1 = $this->db->update($arr_tables[0],$updateData);
		
	}
	
}
// End of file CheckRoleDetails_model.php
// Location: .\application\modules\role\models\CheckRoleDetails_model.php