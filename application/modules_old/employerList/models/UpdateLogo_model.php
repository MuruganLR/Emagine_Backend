<?php
//require_once APPPATH.'config/database_constants.php';
require_once APPPATH.'helpers/mime2ext_helper.php';
require_once APPPATH.'helpers/utility_helper.php';

class UpdateLogo_model extends CI_Model
{
	
	public function updateLogo($input)
	{		
		if(isset($input['id']) && isset($input['img']) && !empty($input['id']) && !empty($input['img']))
		{
			try{
				$doc_image = $input['img'];
				$logoFor = $input['logoFor'];
				
				$this->unlinkFile($input['id'],$logoFor);  //If logo is already exist then to delete old one.
				
				$imagestrng = str_replace(' ','+', $doc_image);
				//$imagestrng = urlencode($doc_image);
				$decoded_file = base64_decode($imagestrng); // decode the file
				$mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type
				$extension = mime2ext($mime_type); // extract extension from mime type
				
// 				chmod('./assets/', 0777);
// 				chmod('./assets/images/', 0777);
// 				chmod('./assets/images/'.$logoFor.'/', 0777);
				
				$image_name= $logoFor."_".$input['id']."_".date("Ymd-His").".".$extension;
				$image_path = "./assets/images/$logoFor/".basename($image_name);
			//	$image_folder_path = "images/$logoFor/".basename($image_name);
				$dbImgPath = "images/$logoFor/".basename($image_name);
				
				file_put_contents($image_path, $decoded_file);		//upload file
	
				$this->updateLogoInDB($input['id'],$dbImgPath);
				
				return true;
			}
			catch(Exception $e){
				echo 'Message: ' .$e->getMessage();
			}
		}
		
		//asset_url().$db_select_query[$i]['performance_parameter_value'] -- select image code
		
// 		return (string)$id;
		
			
	}
	
	
	public function  updateLogoInDB($id,$image_path){
		
		$this->load->helper('url');
		$url =asset_url();
		
		$logo_data=array(
				'id'  =>$id,
				'logo_path' => $url.$image_path
		);
					
		$customer_table=array(
				'`business_unit_list` '
		);
		
		//$arrtables[0]="`" . ACADEMY_CENTER_PLAYER_TABLE. "` "." `p`";
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