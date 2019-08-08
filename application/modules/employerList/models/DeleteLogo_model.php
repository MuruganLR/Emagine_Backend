<?php
//require_once APPPATH.'config/database_constants.php';
require_once APPPATH.'helpers/mime2ext_helper.php';
require_once APPPATH.'helpers/utility_helper.php';

class DeleteLogo_model extends CI_Model
{
	
	public function DeleteLogo($input)
	{		
		if(isset($input['id']) && !empty($input['id']))
		{
			try{
				$logoFor = $input['logoFor'];				
				$this->unlinkFile($input['id'],$logoFor);  //If logo is already exist then to delete old one.												
				
				$this->UpdateLogoInDB($input['id']);				
				return true;
			}
			catch(Exception $e){
				echo 'Message: ' .$e->getMessage();
			}
		}							
	}
	
	
	public function  UpdateLogoInDB($id){
		
		$this->load->helper('url');
		$url =asset_url();
		
		$logo_data=array(
				'id'  =>$id,
				'logo_path' => NULL
		);
					
		$customer_table=array(
				'`business_unit_list` '
		);
		
		$this->db->set($logo_data);
		$result = $this->db->update($customer_table[0], $logo_data, array('id'=>$id));
					
		return (string)$id;
	}
	
	
	public function unlinkFile($id,$logoFor){
		$select_query=array();
		$select =   array(
				'IFNULL (`id`,"") as id',
				'IFNULL (`logo_path`,"") as logo_path'
				);
		
		$arrTables=array(
				'`business_unit_list`'
		);
		
		$this->db->select($select);
		$this->db->from($arrTables);
		$this->db->where('`id`', $id);
		
		$db_select_query=$this->db->get()->result_array();
		
		if (!empty($db_select_query) && isset($db_select_query[0]['logo_path']) && $db_select_query[0]['logo_path'] != '' && $db_select_query[0]['logo_path'] != NULL)
		{
			$this->load->helper('url');
		    $logo_path = explode('/',$db_select_query[0]['logo_path']);
		    $indexVal = sizeof($logo_path) - 1;
			$path = './assets/images/'.$logoFor.'/'.$logo_path[$indexVal];
			unlink($path);			
		}
			
		return true;
		
	}
	
}